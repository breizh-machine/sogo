<?php

namespace Scubs\CoreDomain\Cube;

use Scubs\CoreDomain\Core\Resource;

class Cube extends Resource {

    protected $texture;
    protected $thumbnail;
    protected $rarity;
    protected $description;

    public function __construct(CubeId $id, $texture, $thumbnail, $rarity, $description)
    {
        parent::__construct($id);
        $this->texture = $texture;
        $this->rarity  = $rarity;
        $this->description  = $description;
        $this->thumbnail  = $thumbnail;
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

}

