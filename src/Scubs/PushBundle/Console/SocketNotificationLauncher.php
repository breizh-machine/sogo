<?php

namespace Scubs\PushBundle\Console;

use React\EventLoop\Factory;
use React\ZMQ\Context;
use Scubs\PushBundle\Server\Pusher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SocketNotificationLauncher extends Command
{
    protected $pusher;

    public function __construct(Pusher $pusher)
    {
        parent::__construct(null);
        $this->pusher = $pusher;
    }

    protected function configure()
    {
        $this->setName('scubs:socket:notif:start');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $loop = Factory::create();
        $context = new Context($loop);
        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
        $pull->bind('tcp://127.0.0.1:5555'); // Binding to 127.0.0.1 means the only client that can connect is itself

        while (true) {
            try {
                $message = $pull->recv();
                $this->pusher->onMessageReceived($message);
            } catch (\Exception $e) {
                echo "Failed to process message $message : " . $e->getMessage() . "\n";
            }

        }
    }
}