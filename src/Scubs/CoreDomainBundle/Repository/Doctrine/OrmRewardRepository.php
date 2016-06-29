<?php

namespace Scubs\CoreDomainBundle\Repository\Doctrine;

use Scubs\CoreDomain\Reward\RewardRepository;

class OrmRewardRepository extends OrmResourceRepository implements RewardRepository
{
    public function findRewardByCubeAndUser($userId, $cubeId)
    {
        $query = $this->getManager()->createQuery('
        SELECT r
        FROM ScubsCoreDomainBundle:Reward r
        JOIN r.game g
        WHERE r.cube = :cube_id AND g.winner = :user_id'
        )
            ->setParameter('cube_id', $cubeId)
            ->setParameter('user_id', $userId);

        return $query->getResult();
    }

    public function findRewardsByUser($userId, $q = null)
    {
        if ($q !== null) {
            return $this->getRewardsByUserAndName($userId, $q);
        } else {
            $query = $this->getManager()->createQuery('
            SELECT r
            FROM ScubsCoreDomainBundle:Reward r
            JOIN r.game g
            WHERE g.winner = :user_id'
                )
                    ->setParameter('user_id', $userId);
            return $query->getResult();
        }
    }

    public function findRewardByGame($gameId)
    {
        $query = $this->getManager()->createQuery('
        SELECT r
        FROM ScubsCoreDomainBundle:Reward r
        WHERE r.game = :game_id'
        )->setParameter('game_id', $gameId);

        return $query->getOneOrNullResult();
    }

    private function getRewardsByUserAndName($userId, $q)
    {
        $query = $this->getManager()->createQuery('
            SELECT r
            FROM ScubsCoreDomainBundle:Reward r
            JOIN r.game g
            JOIN r.cube c
            WHERE g.winner = :user_id AND c.description LIKE :q'
        )
            ->setParameter('user_id', $userId)
            ->setParameter('q', sprintf('%%%s%%', $q));


        return $query->getResult();
    }

}