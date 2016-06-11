<?php

namespace Scubs\CoreDomain\Core;

use Scubs\CoreDomain\Core\Resource as BaseResource;

interface ResourceRepository
{
    public function find(ResourceId $resourceId);

    public function findAll();

    public function add(BaseResource $resource);

    public function remove(BaseResource $resource);

    public function exists(BaseResource $resource);
}