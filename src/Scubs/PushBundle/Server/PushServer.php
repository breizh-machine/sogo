<?php

namespace Scubs\PushBundle\Server;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\Wamp\WampServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\Server;

class PushServer
{
    private $pushMessageHandler;
    private $socketUrl;
    private $webSocketPort;

    public function __construct(PushMessageHandler $pushMessageHandler, $socketUrl, $webSocketPort = 8080)
    {
        $this->pushMessageHandler = $pushMessageHandler;
        $this->socketUrl = $socketUrl;
        $this->webSocketPort = $webSocketPort;
    }

    public function start()
    {
        $loop = Factory::create();

        $context = new \React\ZMQ\Context($loop);
        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
        $pull->bind($this->socketUrl);
        $pull->on('error', function ($e) {
            echo 'Error handling message : ' . $e->getMessage() . PHP_EOL;
        });

        $pushMessageHandler = $this->pushMessageHandler;
        $pull->on('message', function ($msg) use ($pushMessageHandler) {
            echo 'Received message ' . $msg . PHP_EOL;
            $pushMessageHandler->onMessageReceived($msg);
        });

        // Set up our WebSocket server for clients wanting real-time updates
        $webSock = new Server($loop);
        $webSock->listen($this->webSocketPort, '0.0.0.0');
        $webServer = new IoServer(
            new HttpServer(
                new WsServer(
                    new WampServer(
                        $this->pushMessageHandler
                    )
                )
            ),
            $webSock
        );

        $loop->run();
    }
}