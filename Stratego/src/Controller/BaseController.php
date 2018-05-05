<?php

namespace App\Controller;

use App\Game\Tablier;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    /**
     * @Route("/index/{_locale}", name="base",defaults={"_locale"="fr"})
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
