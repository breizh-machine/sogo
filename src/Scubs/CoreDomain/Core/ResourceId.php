<?php

namespace Scubs\CoreDomain\Core;

use Ramsey\Uuid\Uuid;

class ResourceId {

    protected $value;

    public function __construct($value = null)
    {
        $this->value = $value ? (string) $value : $this->generateUuid();
    }

    public function getValue()
    {
        return $this->value;
    }

    public function equals(ResourceId $externalId)
    {
        return $externalId->getValue() == $this->value;
    }

    private function generateUuid() {
        return Uuid::uuid4()->toString();
    }

    public function toString()
    {
        return $this->value;
    }
}