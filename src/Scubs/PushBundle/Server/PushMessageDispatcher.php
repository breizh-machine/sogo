<?php

namespace Scubs\PushBundle\Server;

use Scubs\PushBundle\Message\PushMessage;

class PushMessageDispatcher
{
    protected $zmqContext;
    protected $socket;
    protected $socketUrl;
    protected $socketId;

    public function __construct($socketId, $socketUrl)
    {
        $this->zmqContext = new \ZMQContext();
        $this->socketUrl = $socketUrl;
        $this->socketId = $socketId;
        $this->socket = $this->zmqContext->getSocket(\ZMQ::SOCKET_PUSH, $this->socketId);
        $this->socket->connect($this->socketUrl);
    }

    public function dispatchMessage(PushMessage $message)
    {
        $this->socket->send(json_encode($message->getData()));
    }
}
