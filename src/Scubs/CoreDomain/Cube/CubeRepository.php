<?php

namespace Scubs\CoreDomain\Cube;

interface CubeRepository
{
    public function find(CubeId $cubeId);

    public function findAll();

    public function add(Cube $cube);

    public function remove(Cube $cube);
}