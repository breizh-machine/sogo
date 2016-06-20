<?php

namespace Scubs\CoreDomainBundle\Repository\Doctrine;

use Scubs\CoreDomain\Game\GameRepository;

class OrmGameRepository extends OrmResourceRepository implements GameRepository
{
    public function findAllByUserIdOrderedByDate($userId)
    {
        $query = $this->getManager()->createQuery('
        SELECT g
        FROM ScubsCoreDomainBundle:Game g
        WHERE g.local = :user_id OR g.visitor = :user_id
        ORDER BY g.startDate'
        )->setParameter('user_id', $userId);

        return $query->getResult();
    }
}