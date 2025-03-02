<?php

namespace WebSocketChannels;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class WebSocketServer implements MessageComponentInterface
{
    protected $clients;
    protected $channels;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->channels = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $conn->channel = null;
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg);

        if (isset($data->action) && $data->action === 'subscribe') {
            $channelId = $data->channel_id;

            $from->channel = $channelId;

            if (!isset($this->channels[$channelId])) {
                $this->channels[$channelId] = [];
            }

            $this->channels[$channelId][] = $from;
            return;
        }

        if (!isset($data->channel_id)) {
            foreach ($this->clients as $client) {
                if ($from !== $client) {
                    $client->send($msg);
                }
            }
        } else {
            $channelId = $data->channel_id;
            if (isset($this->channels[$channelId])) {
                foreach ($this->channels[$channelId] as $client) {
                    if ($from !== $client) {
                        $client->send($msg);
                    }
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        if ($conn->channel) {
            $channelId = $conn->channel;
            if (isset($this->channels[$channelId])) {
                $this->channels[$channelId] = array_filter(
                    $this->channels[$channelId],
                    function ($client) use ($conn) {
                        return $client !== $conn;
                    }
                );
            }
        }

        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }

    public function run(string|int $port = 8080): void
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new WebSocketServer()
                )
            ),
            $port
        );

        $this->info("Websocket running on port $port");

        $server->run();
    }

    private function info(string $msg) : void
    {
        echo $msg . PHP_EOL;
    }
}
