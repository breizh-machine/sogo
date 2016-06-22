<?php

namespace Tests\Scubs\CoreDomainBundle\Repository\Doctrine;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Scubs\CoreDomainBundle\Entity\User as ScubPlayer;
use Scubs\CoreDomainBundle\Entity\Cube;
use Scubs\CoreDomain\Core\ResourceId;
use Scubs\CoreDomain\Cube\CubeId;
use Scubs\CoreDomain\Game\GameId;
use Scubs\CoreDomainBundle\Entity\Turn;
use Scubs\CoreDomain\Turn\TurnId;
use Scubs\CoreDomainBundle\Entity\Game;


class BaseOrmRepository extends WebTestCase
{
    protected $cubeRepository;
    protected $turnRepository;
    protected $gameRepository;
    protected $rewardRepository;
    protected $userManager;
    protected $container;
    protected $client;

    protected function init()
    {
        if (!$this->client) {
            $this->client = static::createClient();
            $this->container = $this->client->getContainer();
        }
        $this->cubeRepository = $this->container->get('cube_repository.doctrine_orm');
        $this->turnRepository = $this->container->get('turn_repository.doctrine_orm');
        $this->gameRepository = $this->container->get('game_repository.doctrine_orm');
        $this->rewardRepository = $this->container->get('reward_repository.doctrine_orm');
        $this->userManager = $this->container->get('fos_user.user_manager');
    }

    protected function setLocal()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $local->setPlainPassword('local');
        $local->setEmail('email@gmail.com');
        $local->setUsername('local');
        $local->setEnabled(true);
        $this->userManager->updateUser($local);
        return $local;
    }

    protected function setVisitor()
    {
        $visitor = $this->userManager->findUserBy(['id' => 'VISITOR']);
        if (!$visitor) {
            $visitor = new ScubPlayer(new ResourceId('VISITOR'));
            $visitor->setPlainPassword('visitor');
            $visitor->setEmail('visitor@gmail.com');
            $visitor->setUsername('visitor');
            $visitor->setEnabled(true);
            $this->userManager->updateUser($visitor);
        }
        return $visitor;
    }

    protected function setThirdPlayer()
    {
        $visitor = $this->userManager->findUserBy(['id' => 'THIRD']);
        if (!$visitor) {
            $visitor = new ScubPlayer(new ResourceId('THIRD'));
            $visitor->setPlainPassword('THIRD');
            $visitor->setEmail('THIRD@gmail.com');
            $visitor->setUsername('THIRD');
            $visitor->setEnabled(true);
            $this->userManager->updateUser($visitor);
        }
        return $visitor;
    }

    protected function setCube($id, $texture, $thumbnail, $rarity, $description, $native)
    {
        $cube = new Cube(new CubeId($id), $texture, $thumbnail, $rarity, $description, $native);
        $this->cubeRepository->add($cube);
        return $cube;
    }

    protected function setLocalCube()
    {
        return $this->setCube('local', 'test', 0, 'test', 'test',  true);
    }

    protected function setVisitorCube()
    {
        return $this->setCube('visitor', 'test', 0, 'test', 'test',  true);
    }

    protected function getGame($gameId, $local, $visitor, $localCube, $visitorCube, $bet, $won = true)
    {
        $game = new Game(new GameId($gameId), $local, $bet, $localCube);
        $game->inviteVisitor($visitor);
        $game->visitorJoined($visitorCube);
        $game->play(new Turn(new TurnId(), $local, 0, 0, 0));
        $game->play(new Turn(new TurnId(), $visitor, 1, 0, 0));
        $game->play(new Turn(new TurnId(), $local, 1, 1, 0));
        $game->play(new Turn(new TurnId(), $visitor, 2, 0, 0));
        $game->play(new Turn(new TurnId(), $local, 3, 0, 0));
        $game->play(new Turn(new TurnId(), $visitor, 2, 1, 0));
        $game->play(new Turn(new TurnId(), $local, 2, 2, 0));
        $game->play(new Turn(new TurnId(), $visitor, 3, 1, 0));
        $game->play(new Turn(new TurnId(), $local, 3, 2, 0));
        $game->play(new Turn(new TurnId(), $visitor, 0, 1, 0));
        if ($won) {
            $game->play(new Turn(new TurnId(), $local, 3, 3, 0));
        }
        $this->gameRepository->add($game);
        return $game;
    }

    protected function getNotJoinedGame($gameId, $local, $visitor, $localCube, $bet, $alreadyInvited = false)
    {
        $game = new Game(new GameId($gameId), $local, $bet, $localCube);
        if ($alreadyInvited) {
            $game->inviteVisitor($visitor);
        }
        $this->gameRepository->add($game);
        return $game;
    }
}
