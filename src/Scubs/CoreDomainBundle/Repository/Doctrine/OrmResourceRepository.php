<?php

namespace Scubs\CoreDomainBundle\Repository\Doctrine;

use Doctrine\ORM\EntityRepository;

use Scubs\CoreDomain\Core\Resource as BaseResource;
use Scubs\CoreDomain\Core\ResourceId;
use Scubs\CoreDomain\Core\ResourceRepository;

class OrmResourceRepository implements ResourceRepository
{
    private $resourceClass;
    private $resourceIdClass;
    private $entityRepository;

    public function __construct(EntityRepository $entityRepository, $resourceClass, $resourceIdClass)
    {
        $this->entityRepository = $entityRepository;
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
        $this->entityRepository->getEntityManager()->persist($resource);
        $this->entityRepository->getEntityManager()->commit();
    }

    public function remove(BaseResource $resourceToRemove)
    {
        $this->checkResourceClass($resourceToRemove);
        $this->entityRepository->getEntityManager()->remove($resourceToRemove);
        $this->entityRepository->getEntityManager()->commit();
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