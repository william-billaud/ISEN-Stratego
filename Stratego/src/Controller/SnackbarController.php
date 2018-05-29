<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SnackbarController extends Controller
{
    /**
     * !!!! Before testing this page, launch the ChatServerCommand with this command line in new terminal : " php bin/console f:a:c " !!!!
     * @Security("has_role('ROLE_USER')")
     * @Route("/snackbar", name="snackbar")
     */
    public function index()
    {
        $user = $this->getUser();
        $userName = $user->getUsername();

        dump($userName);

        return $this->render('snackbar/index.html.twig', [
            'userName' => $userName,
            'controller_name' => 'SnackbarController',
            'ws_url' => 'localhost:8080',
        ]);
    }
}
