<?php

namespace App\Providers;

use App\Services\AI\AIServiceInterface;
use App\Services\AI\OpenRouterService;
use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AIServiceInterface::class, function ($app) {
            return new OpenRouterService(
                config('services.openrouter.api_key'),
                config('ai.default_model', 'openrouter/auto')
            );
        });

        // Register a singleton instance for the AI service
        $this->app->singleton('ai', function ($app) {
            return $app->make(AIServiceInterface::class);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
