<?php

namespace Scubs\PushBundle\Message;

class PushMessage
{
    protected $channel;
    protected $data;
    protected $type;

    public function __construct($channel, array $data, $type)
    {
        $this->channel = $channel;
        $this->data = $data;
        $this->type = $type;
    }

    public function getChannel()
    {
        return $this->channel;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getType()
    {
        return $this->type;
    }
}