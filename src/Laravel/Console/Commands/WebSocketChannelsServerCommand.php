<?php

namespace WebSocketChannels\Laravel\Console\Commands;

use Illuminate\Console\Command;

class WebSocketChannelsServerCommand extends Command
{
    protected $signature = 'channels {--port=8080}';

    protected $description = 'Start the WebSocket channels server';

    public function handle()
    {
        $port = $this->option('port');

        $server = new \WebSocketChannels\WebSocketServer();
        $server->run($port);
    }
}
