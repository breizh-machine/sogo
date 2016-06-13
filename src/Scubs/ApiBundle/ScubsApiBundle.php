<?php

namespace Scubs\ApiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use Scubs\ApiBundle\DependencyInjection\ScubsApiBundleExtension;

class ScubsApiBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new ScubsApiBundleExtension();
    }
}
