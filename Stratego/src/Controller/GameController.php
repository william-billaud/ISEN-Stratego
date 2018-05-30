<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GameController extends Controller
{
    /**
     * @Route("/game", name="game")
     */
    public function index()
    {
        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }

    /**
     * @Route("/game/{id}", name="play_game", requirements={"id": "\d+"})
     */
    public function gameAction(int $id)
    {
        $response =new Response();
        $response->headers->set("Access-Control-Allow-Origin","*");
        return $this->render('game/index.html.twig',[],$response);
    }

    /**
     * @Route("/game/init/{id}", name="init_game", requirements={"id": "\d+"})
     */
    public function initGameAction(int $id)
    {
        return $this->render('game/init.html.twig');
    }
}
