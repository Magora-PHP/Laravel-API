<?php


namespace App\Services;

use Config;
use Illuminate\Console\Command;
use InvalidArgumentException;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


class QueueService
{

    const QUEUE_PUSH = 'push';

    const QUEUE_MAIL = 'mail';

    /** @var AMQPChannel $mq */
    protected $mq;

    public function __construct(AMQPStreamConnection $mq)
    {
        $this->mq = $mq->channel();
    }

    /**
     * @param $msg
     * @param $queue
     */
    public function addMsg($msg, $queue)
    {
        $channel = $this->mq;
        try {
            $channel->queue_declare($queue, false, false, false, false);
            $msg = new AMQPMessage($msg);
            $channel->basic_publish($msg, '', $queue);
        } catch (\Exception $e) {
            //
        }
    }

    public function handle($queue, \Closure $callback, Command $worker)
    {
        $channel = $this->mq;
        $channel->queue_declare($queue, false, false, false, false);
        $worker->comment(" [*] $queue queue is waiting for messages. To exit press CTRL+C");
        $channel->basic_consume($queue, '', false, true, false, false, $callback);
        while(count($channel->callbacks)) {
            $channel->wait();
        }
        $channel->close();
    }

}
