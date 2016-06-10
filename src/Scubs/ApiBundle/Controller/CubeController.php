<?php

namespace Scubs\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CubeController extends Controller
{
    /**
     * @Rest\View
     */
    public function allAction()
    {
        $cubes = $this->get('cube_repository')->findAll();

        return ['cubes' => $cubes];
    }
}