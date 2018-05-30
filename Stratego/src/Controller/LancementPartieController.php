<?php

namespace App\Controller;

use App\Entity\Partie;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;

class LancementPartieController extends Controller
{
    /**
     * @Route("/lancement/partie/{idJoueurDefie}", name="lancement_partie")
     * @Security("has_role('ROLE_USER')")
     * @param Request $request
     * @param UserInterface $user
     * @param int $idJoueurDefie
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request,UserInterface $user,int $idJoueurDefie,EntityManagerInterface $em)
    {
        $userDefie=$em->find(User::class,$idJoueurDefie);
        $partie=new Partie();
        /** @var User $user */
        $partie->setJoueur1($user);
        $partie->setJoueur2($userDefie);
        $partie->setEtatPartie($partie::ATTENTE);
        $partie->setTourJoueur(0);
        $partie->setDateDebut(new \DateTime());
        $em->persist($partie);
        $em->flush();
        return $this->render('lancement_partie/index.html.twig', [
            'controller_name' => 'LancementPartieController',
        ]);
    }

    /**
     * @Route("/accepteDefie/{idPartie}",name="accepteDefie",requirements={"id": "\d+"})
     * @Security("has_role('ROLE_USER')")
     * @param Request $request
     * @param UserInterface $user
     * @param int $idPartie
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function accepteDefie(Request $request,UserInterface $user,int $idPartie,EntityManagerInterface $em)
    {
        $partie=$em->find(Partie::class,$idPartie);
        /** @var User $user */
        if($user->isEquals($partie->getJoueur2()))
        {
            $partie->setEtatPartie(Partie::INITIALISATION);
        }

        return $this->redirectToRoute('affiche_tab',["id"=>$partie->getId()]);
    }
}
