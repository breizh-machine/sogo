<?php

namespace Scubs\CoreDomain\Core;

use Ramsey\Uuid\Uuid;

class ResourceId {

    protected $value;

    public function __construct($value)
    {
        $this->value = $value ? (string) $value : $this->generateUuid();
    }

    public function getValue()
    {
        return $this->value;
    }

    private function generateUuid() {
        return Uuid::uuid4()->toString();
    }
}