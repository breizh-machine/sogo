<?php

namespace Scubs\CoreDomainBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Scubs\CoreDomainBundle\DependencyInjection\ScubsCoreDomainBundleExtension;

class ScubsCoreDomainBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new ScubsCoreDomainBundleExtension();
    }
}
