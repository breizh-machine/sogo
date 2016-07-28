<?php

namespace Scubs\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\View;
use Scubs\ApiBundle\Query\CubesQuery;
use Scubs\ApiBundle\Query\CubesByUserQuery;

class CubeController extends FOSRestController
{
    /**
     * @Rest\View
     */
    public function allAction()
    {
        $query = new CubesQuery();
        $handler = $this->get('scubs.api.handler.query.cubes');
        return $handler->handle($query);
    }

    /**
     * @Rest\View
     */
    public function byUserAction(Request $request, $userId)
    {
        $q = $request->query->get('q');

        $query = new CubesByUserQuery();

        $query->userId = $userId;
        $query->q = $q;

        $handler = $this->get('scubs.api.handler.query.cubes_by_user');

        return $handler->handle($query);
    }
}