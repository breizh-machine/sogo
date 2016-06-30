<?php

namespace Scubs\CoreDomainBundle\Repository\Doctrine;

use Scubs\CoreDomain\User\UserRepository;

class OrmUserRepository extends OrmResourceRepository implements UserRepository
{
    public function getAvailablePlayersUsername($userId, $q = '')
    {
        $query = $this->getManager()->createQuery('
            SELECT u
            FROM ScubsCoreDomainBundle:User u
            WHERE u.id != :user_id AND u.username LIKE :q
            AND u.id NOT IN 
            (
              SELECT o.id 
              FROM ScubsCoreDomainBundle:User o 
              JOIN ScubsCoreDomainBundle:Game g
              WHERE (g.local = :user_id AND g.visitor = o.id) OR (g.local = o.id AND g.visitor = :user_id)
            )'
        )
            ->setParameter('user_id', $userId)
            ->setParameter('q', sprintf('%%%s%%', $q));


        return $query->getResult();
    }
}