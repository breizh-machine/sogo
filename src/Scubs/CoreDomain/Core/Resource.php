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
        if ($externalResource->getId() instanceof ResourceId && $this->id instanceof ResourceId) {
            return $this->id->equals($externalResource->getId());
        } else {
            return (string) $this->id == (string) $externalResource->getId();
        }
    }

    public function getId()
    {
        return $this->id;
    }
}