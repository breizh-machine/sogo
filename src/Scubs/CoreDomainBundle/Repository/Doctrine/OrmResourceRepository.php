<?php

namespace Scubs\CoreDomainBundle\Repository\Doctrine;

use Scubs\CoreDomain\Core\Resource as BaseResource;
use Scubs\CoreDomain\Core\ResourceId;
use Scubs\CoreDomain\Core\ResourceRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;

class OrmResourceRepository implements ResourceRepository
{
    private $resourceClass;
    private $resourceIdClass;
    private $registry;
    private $entityRepository;

    public function __construct(Registry $registry, $resourceClass, $resourceIdClass)
    {
        $this->entityRepository = $registry->getRepository($resourceClass);
        $this->registry = $registry;
        $this->resourceClass = $resourceClass;
        $this->resourceIdClass = $resourceIdClass;
    }

    public function find(ResourceId $resourceId)
    {
        $this->checkResourceIdClass($resourceId);
        return $this->entityRepository->find($resourceId->getValue());
    }

    public function findAll()
    {
        return $this->entityRepository->findAll();
    }

    public function add(BaseResource $resource)
    {
        $this->checkResourceClass($resource);
        $this->registry->getManager()->persist($resource);
        $this->registry->getManager()->flush();
    }

    public function remove(BaseResource $resourceToRemove)
    {
        $this->checkResourceClass($resourceToRemove);
        $this->registry->getManager()->remove($resourceToRemove);
        $this->registry->getManager()->flush();
    }

    public function exists(BaseResource $resource)
    {
        return $this->find($resource->getId()) !== null;
    }

    public function beginTransaction()
    {
        $this->registry->getConnection()->beginTransaction();
    }

    public function commit()
    {
        $this->registry->getManager()->commit();
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