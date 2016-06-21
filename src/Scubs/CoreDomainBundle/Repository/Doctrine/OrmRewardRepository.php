<?php
/**
 * Created by PhpStorm.
 * User: FMARTIN
 * Date: 15/06/2016
 * Time: 18:01
 */

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
}