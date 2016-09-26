<?php

namespace App\Providers;

use App\Services\QueueService;
use Illuminate\Support\ServiceProvider;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class QueueServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Services\QueueService', function() {
            $params = config('queue.rabbitmq');
            return new QueueService(new AMQPStreamConnection(
                $params['host'],
                $params['port'],
                $params['user'],
                $params['pass']//,

            //http://stackoverflow.com/questions/34156499/rabbitmqbundle-consumer-exiting-with-exception-error-reading-data-received-0-i
//                $vhost = '/',
//                $insist = false,
//                $login_method = 'AMQPLAIN',
//                $login_response = null,
//                $locale = 'en_US',
//                $connection_timeout = 10,
//                $read_write_timeout = 10,
//                $context = null,
//                $keepalive = false,
//                $heartbeat = 5
            ));
        });
    }
}
