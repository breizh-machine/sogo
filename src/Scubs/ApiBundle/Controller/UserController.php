<?php

namespace Scubs\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\View;
use Scubs\ApiBundle\Query\PlayersQuery;

class UserController extends Controller
{
    /**
     * @Rest\View
     */
    public function byUserAction(Request $request, $userId)
    {
        $q = $request->query->get('q');

        $query = new PlayersQuery();

        $query->userId = $userId;
        $query->q = $q;

        $handler = $this->get('scubs.api.handler.query.players_by_user');

        return $handler->handle($query);
    }
}