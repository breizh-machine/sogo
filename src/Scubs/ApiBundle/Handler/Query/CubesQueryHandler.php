<?php

namespace Scubs\ApiBundle\Handler\Query;

use Scubs\CoreDomain\Cube\CubeRepository;
use Scubs\ApiBundle\Query\CubesQuery;

class CubesQueryHandler
{
    private $cubeRepository;

    public function __construct(CubeRepository $cubeRepository)
    {
        $this->cubeRepository = $cubeRepository;
    }

    public function handle(CubesQuery $query)
    {
        return $this->cubeRepository->findAll();
    }
}