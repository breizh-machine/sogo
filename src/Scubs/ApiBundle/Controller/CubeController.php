<?php

namespace Scubs\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\View;
use Scubs\ApiBundle\Query\CubesQuery;
use Scubs\ApiBundle\Query\CubesByUserQuery;

class CubeController extends Controller
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
    public function byUserAction($userId)
    {
        $query = new CubesByUserQuery();

        $query->userId = $userId;

        $handler = $this->get('scubs.api.handler.query.cubes_by_user');

        return $handler->handle($query);
    }
}