<?php

namespace Scubs\PushBundle\Console;

use Scubs\PushBundle\Server\PushServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PushServerLauncher extends Command
{
    protected $pushServer;

    public function __construct(PushServer $pushServer = null)
    {
        parent::__construct(null);
        $this->pushServer = $pushServer;
    }

    protected function configure()
    {
        $this->setName('scubs:push_server:start');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->pushServer->start();
    }
}