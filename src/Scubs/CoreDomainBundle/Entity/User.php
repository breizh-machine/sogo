<?php

namespace Scubs\CoreDomainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Scubs\CoreDomain\User\User as BaseUser;
use Scubs\CoreDomain\Core\ResourceId;

class User extends BaseUser
{
    protected $id;
    
    public function __construct(ResourceId $playerId = null, $credits = null)
    {
        parent::__construct($playerId, $credits);
    }
}