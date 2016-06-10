<?php

namespace Scubs\CoreDomain\Cube;

class Cube {

    private $id;
    private $texture;
    private $rarity;
    private $name;

    public function __construct(CubeId $id, $texture, $rarity, $name)
    {
        $this->id        = $id;
        $this->texture = $texture;
        $this->rarity  = $rarity;
        $this->name  = $name;
    }

    /**
     * @return mixed
     */
    public function getTexture()
    {
        return $this->texture;
    }

    /**
     * @return mixed
     */
    public function getRarity()
    {
        return $this->rarity;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return CubeId
     */
    public function getId()
    {
        return $this->id;
    }
}

