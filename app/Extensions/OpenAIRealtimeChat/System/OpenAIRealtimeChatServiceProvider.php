<?php

declare(strict_types=1);

namespace App\Extensions\OpenAIRealtimeChat\System;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class OpenAIRealtimeChatServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(Kernel $kernel): void
    {
        $this->registerViews();
    }

    public function registerViews(): void
    {
        $this->loadViewsFrom([__DIR__ . '/../resources/views'], 'openai-realtime-chat');
    }
}
