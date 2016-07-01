<?php

namespace Scubs\CoreDomainBundle\Controller;

use Scubs\ApiBundle\ViewDataAggregator\UserViewDataAggregator;
use Scubs\CoreDomain\Game\GameId;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Scubs\CoreDomain\User\User;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/scubs")
 */
class AppController extends Controller
{
    
    protected function getUserView(User $user = null, $format = 'json')
    {
        if ($user === null) {
            $user = $this->getUser();
        }
        $userViewDataAggregator = new UserViewDataAggregator($user);
        $userView = $this->get('scubs.api.view_renderer.user')->renderView($userViewDataAggregator);
        return $format == 'json' ? json_encode($userView) : $userView;
    }

    /**
     * @Route("/profile", name="scoresPage")
     */
    public function scoresAction(Request $request)
    {
        //TODO
        return $this->render('home/scores.html.twig', [
            'user' => $this->getUserView()
        ]);
    }

    /**
     * @Route("/profile", name="profilePage")
     */
    public function profileAction(Request $request)
    {
        //TODO
        return $this->render('home/profile.html.twig', [
            'user' => $this->getUserView()
        ]);
    }
    
    /**
     * @Route("/games", name="gamesPage")
     */
    public function gamesAction(Request $request)
    {
        //TODO Add games and cubes
        return $this->render('home/games.html.twig', [
            'user' => $this->getUserView()
        ]);
    }

    /**
     * @Route("/games/{gameId}/play", name="playPage")
     */
    public function playAction(Request $request, $gameId)
    {
        $user = $this->getUser();
        $gameViewRenderer = $this->get('scubs.api.view_renderer.game');
        $game = $this->get('game_repository.doctrine_orm')->find(new GameId($gameId));
        if ($game == null) {
            //TODO
        } else {
            $currentGame = $gameViewRenderer->renderView($game, $user);
            $currentGame = json_encode($currentGame);
            return $this->render('home/play.html.twig', [
                'user' => $this->getUserView($user),
                'currentGame' => $currentGame
            ]);
        }
    }
}