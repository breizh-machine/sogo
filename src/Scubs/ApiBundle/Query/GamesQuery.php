<?php

namespace Scubs\ApiBundle\Query;

use Scubs\ApiBundle\Query\Query as BaseQuery;

class GamesQuery extends BaseQuery
{
    public $userId;
    public $offset;
    public $quantity;
}