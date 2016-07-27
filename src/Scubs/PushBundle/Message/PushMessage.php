<?php

namespace Scubs\PushBundle\Message;

class PushMessage
{
    protected $channel;
    protected $data;

    public function __construct($channel, $data)
    {
        $this->channel = $channel;
        $this->data = $data;
    }

    public function getChannel()
    {
        return $this->channel;
    }

    public function getData()
    {
        $this->data['channel'] = $this->channel;
        return $this->data;
    }
}