<?php

namespace Scubs\PushBundle\Server;

use Scubs\PushBundle\Message\PushMessage;

class MessageDispatcher
{
    protected $zmqContext;
    protected $socket;

    public function __construct($pusherName, $pusherUrl)
    {
        $this->zmqContext = new \ZMQContext();
        $this->socket = $this->zmqContext->getSocket(\ZMQ::SOCKET_PUSH, $pusherName);
        $this->socket->connect($pusherUrl);
    }

    public function dispatchMessage(PushMessage $message)
    {
        $this->socket->send(json_encode($message->getData()));
    }
}
