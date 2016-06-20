<?php

namespace Scubs\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\View;
use Scubs\ApiBundle\Query\GamesQuery;

class GameController extends Controller
{
    /**
     * @Rest\View
     */
    public function allAction($userId)
    {
        $query = new GamesQuery();
        $query->userId = $userId;
        $handler = $this->get('scubs.api.handler.query.games');
        return $handler->handle($query);
    }
}