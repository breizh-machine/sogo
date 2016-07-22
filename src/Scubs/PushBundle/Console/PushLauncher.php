<?php

namespace Scubs\PushBundle\Console;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\Wamp\WampServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\Server;
use React\ZMQ\Context;
use Scubs\PushBundle\Server\Pusher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PushLauncher extends Command
{
    protected $pusher;

    public function __construct(Pusher $pusher)
    {
        parent::__construct(null);
        $this->pusher = $pusher;
    }

    protected function configure()
    {
        $this->setName('scubs:push:start');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $loop = Factory::create();
        $context = new Context($loop);
        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
        $pull->bind('tcp://127.0.0.1:5555'); // Binding to 127.0.0.1 means the only client that can connect is itself
        $pull->on('message', array($this->pusher, 'onMessageReceived'));

        // Set up our WebSocket server for clients wanting real-time updates
        $webSock = new Server($loop);
        $webSock->listen(8080, '0.0.0.0'); // Binding to 0.0.0.0 means remotes can connect
        $webServer = new IoServer(
            new HttpServer(
                new WsServer(
                    new WampServer(
                        $this->pusher
                    )
                )
            ),
            $webSock
        );

        $loop->run();
    }
}