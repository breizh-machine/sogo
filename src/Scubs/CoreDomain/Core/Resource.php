<?php

namespace Scubs\CoreDomain\Core;

use Scubs\CoreDomain\Core\Resource as BaseResource;

class Resource {

    protected $id;

    public function __construct(ResourceId $id)
    {
        $this->id = $id;
    }

    public function equals(BaseResource $externalResource) {
        return $this->id->equals($externalResource->getId());
    }

    public function getId()
    {
        return $this->id;
    }
}