<?php

namespace App\Providers;

use GrahamCampbell\Flysystem\FlysystemServiceProvider;
use Illuminate\Redis\RedisServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(FlysystemServiceProvider::class);
        $this->app->register(RedisServiceProvider::class);

        // Make s3 client more easily accessible
        $this->app->bind('s3Client', function () {
            return app('flysystem.connection')->getAdapter()->getClient();
        });

        if (getenv('BUGSNAG_API_KEY', null) != null) {
            $this->app->alias('bugsnag.logger', \Illuminate\Contracts\Logging\Log::class);
            $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);
            $this->app->extend(\Psr\Log\LoggerInterface::class, function ($logger, $app) {
                return new \Bugsnag\BugsnagLaravel\MultiLogger([$logger, $app['bugsnag.logger']]);
            });
        }

        // send logs to stdout
        $logger = $this->app->make(\Psr\Log\LoggerInterface::class);
        $logger->popHandler();
        $logger->pushHandler(new \Monolog\Handler\ErrorLogHandler());
    }
}
