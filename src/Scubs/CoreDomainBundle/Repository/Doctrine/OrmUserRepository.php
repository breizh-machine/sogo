<?php

namespace Scubs\CoreDomainBundle\Repository\Doctrine;

use Scubs\CoreDomain\User\UserRepository;

class OrmUserRepository extends OrmResourceRepository implements UserRepository
{
    public function getUsersByUserAndUsername($userId, $q = '')
    {
        $query = $this->getManager()->createQuery('
            SELECT u
            FROM ScubsCoreDomainBundle:User u
            JOIN ScubsCoreDomainBundle:Game g
            WHERE u.id != g.local AND u.id != g.visitor AND u.id != :user_id AND u.username LIKE :q'
        )
            ->setParameter('user_id', $userId)
            ->setParameter('q', sprintf('%%%s%%', $q));


        return $query->getResult();
    }
}