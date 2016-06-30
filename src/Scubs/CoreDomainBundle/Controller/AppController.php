<?php

namespace Scubs\CoreDomainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/scubs")
 */
class AppController extends Controller
{
    /**
     * @Route("/play", name="playGamePage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    /**
     * @Route("/games", name="gamesPage")
     */
    public function gamesAction(Request $request)
    {
        $user = $this->getUser();
        $jsonUser = json_encode([
            'userId' => (string) $user->getId()
        ]);
        return $this->render('home/games.html.twig', [
            'user' => $jsonUser
        ]);
    }
}