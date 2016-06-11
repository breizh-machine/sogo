<?php

namespace Scubs\CoreDomainBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Scubs\CoreDomain\Core\Resource as BaseResource;
use Scubs\CoreDomain\Core\ResourceId;
use Scubs\CoreDomain\Core\ResourceRepository;

class InMemoryResourceRepository implements ResourceRepository
{
    protected $resources;
    private $resourceClass;
    private $resourceIdClass;

    public function __construct($resourceClass, $resourceIdClass)
    {
        $this->resourceClass = $resourceClass;
        $this->resourceIdClass = $resourceIdClass;
        $this->resources = new ArrayCollection();
    }

    public function find(ResourceId $resourceId)
    {
        $this->checkResourceIdClass($resourceId);
        foreach ($this->resources as $resource) {
            if ($resource->getId()->equals($resourceId)) {
                return $resource;
            }
        }
        return null;
    }

    public function findAll()
    {
        return $this->resources;
    }

    public function add(BaseResource $resource)
    {
        $this->checkResourceClass($resource);
        $this->resources->add($resource);
    }

    public function remove(BaseResource $resourceToRemove)
    {
        $this->checkResourceClass($resourceToRemove);
        foreach ($this->resources as $key => $resource) {
            if ($resource->equals($resourceToRemove)) {
                $this->resources->remove($key);
                return;
            }
        }
    }

    public function exists(BaseResource $resource)
    {
        return $this->find($resource->getId()) !== null;
    }

    private function checkResourceClass(BaseResource $r)
    {
        if (!($r instanceof $this->resourceClass)) {
            throw new \Exception(sprintf('Resource must be an instance of %s', $this->resourceClass));
        }
    }

    private function checkResourceIdClass(ResourceId $r)
    {
        if (!($r instanceof $this->resourceIdClass)) {
            throw new \Exception(sprintf('ResourceId must be an instance of %s', $this->resourceIdClass));
        }
    }
}