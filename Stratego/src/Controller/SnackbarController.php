<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SnackbarController extends Controller
{
    /**
     * !!!! Before testing this page, launch the ChatServerCommand with this command line : " php bin/console afsy:app:chat-server " !!!!
     * @Route("/snackbar", name="snackbar")
     */
    public function index()
    {
        return $this->render('snackbar/index.html.twig', [
            'controller_name' => 'SnackbarController',
            'ws_url' => 'localhost:8080',
        ]);
    }
}
