<?php

namespace Scubs\CoreDomain\Cube;

use Scubs\CoreDomain\Core\Resource;

class Cube extends Resource {

    private $texture;
    private $rarity;
    private $name;

    public function __construct(CubeId $id, $texture, $rarity, $name)
    {
        parent::__construct($id);
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
}

