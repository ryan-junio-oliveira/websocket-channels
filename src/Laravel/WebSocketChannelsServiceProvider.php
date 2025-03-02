<?php

namespace WebSocketChannels\Laravel;

class WebSocketChannelsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            WebSocketChannelsServerCommand::class,
        ]);
    }

    public function boot()
    {
    }
}
