<?php

namespace Scubs\ApiBundle\Handler\Query;

use Scubs\ApiBundle\Query\Query;

interface QueryHandler
{
    public function handle(Query $query);
    public function validate(Query $query);
}