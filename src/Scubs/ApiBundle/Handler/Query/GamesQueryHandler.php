<?php

namespace Scubs\ApiBundle\Handler\Query;

use Scubs\ApiBundle\Query\Query;
use Scubs\CoreDomain\Game\GameRepository;

class GamesQueryHandler implements QueryHandler
{

    private $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function handle(Query $query)
    {
        $this->validate($query);
        
        
    }
    
    public function validate(Query $query)
    {

    }
}