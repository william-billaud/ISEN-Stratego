<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ReglesController extends Controller
{
    /**
     * @Route("/regles", name="regles")
     * @Security("has_role('ROLE_USER')")
     */
    public function index()
    {
        return $this->render('regles/index.html.twig', [
            'controller_name' => 'ReglesController',
        ]);
    }
}
