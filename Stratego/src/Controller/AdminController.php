<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $userManager = $this->getDoctrine()->getRepository(User::class);
        $users = $userManager->findAll();

        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/banned/{id_user}/{banned}",name="banned",requirements={"id_user": "\d+", "banned": "\d+"})
     * @param $id_user
     * @param $banned
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function banned($id_user, $banned, EntityManagerInterface $em)
    {
        $user = $em->find(User::class, $id_user);

        if ($banned) {
            $user->setRoles(["ROLE_BANNED"]);
        } else {
            $user->setRoles(["ROLE_USER"]);
        }
        $em->flush();

        return $this->redirectToRoute('admin');
    }
}
