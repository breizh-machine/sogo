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


    public function findAllEndedByPlayerCouple($player1, $player2)
    {
        $query = $this->getManager()->createQuery('
        SELECT g
        FROM ScubsCoreDomainBundle:Game g
        WHERE (g.winner = :po_id OR g.winner = :pt_id) AND ((g.local = :po_id AND g.visitor = :pt_id) OR (g.local = :pt_id AND g.visitor = :po_id))'
        )
            ->setParameter('po_id', $player1)
            ->setParameter('pt_id', $player2);

        return $query->getResult();
    }

    public function findAllNotEndedByPlayerCouple($player1, $player2)
    {
        $query = $this->getManager()->createQuery('
        SELECT g
        FROM ScubsCoreDomainBundle:Game g
        WHERE (g.winner IS NULL) AND ((g.local = :po_id AND g.visitor = :pt_id) OR (g.local = :pt_id AND g.visitor = :po_id))'
        )
            ->setParameter('po_id', $player1)
            ->setParameter('pt_id', $player2);

        return $query->getResult();
    }
}