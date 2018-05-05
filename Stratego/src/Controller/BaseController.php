<?php

namespace App\Controller;

use App\Game\Tablier;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    /**
     * @Route("/tr/{_locale}", name="base")
     */
    public function index()
    {
        return $this->render('base/index.html.twig', [
            'controller_name' => 'BaseController',
        ]);
    }

    /**
     * @Route("/tab",name="affiche_tab")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function afficheTablier()
    {

        $tab=new Tablier();
        return $this->render('base/afficheTablier.html.twig', [
            'tablier' => $tab,
        ]);
    }
}
