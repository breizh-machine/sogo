<?php

namespace Scubs\PushBundle\Server;

class SocketNotificationListener extends \Thread
{
    private static $pusher;
    private static $socket;

    public function __construct(Pusher $pusher, $loop)
    {
        self::$pusher = $pusher;
        $context = new \React\ZMQ\Context($loop);
        self::$socket = $context->getSocket(\ZMQ::SOCKET_PULL);
        self::$socket->bind('tcp://127.0.0.1:5555');

        /*
        $this->pusher = $pusher;
        $context = new \React\ZMQ\Context($loop);
        $this->socket = $context->getSocket(\ZMQ::SOCKET_PULL);
        $this->socket->bind('tcp://127.0.0.1:5555'); // Binding to 127.0.0.1 means the only client that can connect is itself
        $this->socket->on('message', array($this->pusher, 'onMessageReceived'));
        */
    }

    public function run()
    {
        echo "Listening on backend socket events \n";
        if (self::$socket !== null) {
            $message = self::$socket->recv();
            self::$pusher->onMessageReceived($message);
        }
        /*
        return function() use($socket, $pusher) {
            $message = $socket->recv();
            $pusher->onMessageReceived($message);
        };
        */
    }

}