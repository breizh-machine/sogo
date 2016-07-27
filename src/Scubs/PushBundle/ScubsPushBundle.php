<?php

namespace Scubs\PushBundle;

use Scubs\PushBundle\DependencyInjection\ScubsPushBundleExtension;
use Symfony\Component\Console\Application;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ScubsPushBundle extends Bundle
{
    public function registerCommands(Application $application)
    {
        $launchPushService = $this->container->get('scubs.push.commands.launcher');
        $application->add($launchPushService);
    }

    public function getContainerExtension()
    {
        return new ScubsPushBundleExtension();
    }
}
