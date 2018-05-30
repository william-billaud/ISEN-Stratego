<?php

namespace App\Controller;

use App\Entity\Partie;
use App\Entity\User;
use App\Security\Voter\PartieVoter;
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
     * @param UserInterface $user
     * @param int $idJoueurDefie
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(UserInterface $user, int $idJoueurDefie, EntityManagerInterface $em)
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
        return $this->redirectToRoute('base');
    }

    /**
     * @Route("/accepteDefie/{idPartie}",name="accepteDefie",requirements={"idPartie": "\d+"})
     * @Security("has_role('ROLE_USER')")
     * @param int $idPartie
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function accepteDefie(int $idPartie, EntityManagerInterface $em)
    {
        $partie=$em->find(Partie::class,$idPartie);
        /** @var User $user */
        if($this->isGranted(PartieVoter::Joueur2,$partie))
        {
            $partie->setEtatPartie(Partie::INITIALISATION);
        }
        $em->flush();

        return $this->redirectToRoute('affiche_tab',["id"=>$partie->getId()]);
    }

    /**
     * @Route("/refuseDefie/{idPartie}",name="refuseDefie",requirements={"idPartie": "\d+"})
     * @param Request $request
     * @param UserInterface $user
     * @param int $idPartie
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function refuseDefie(int $idPartie,EntityManagerInterface $em)
    {
        $partie=$em->find(Partie::class,$idPartie);
        /** @var User $user */
        if($this->isGranted(PartieVoter::Joueur2,$partie))
        {
            $partie->setEtatPartie(Partie::DECLINE);
        }
        $em->flush();

        return $this->redirectToRoute('base');
    }
    /**
     * @Route("/showDefies",name="affiche_defie"),
     * @param EntityManagerInterface $em
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showDefieEnAttente(EntityManagerInterface $em,UserInterface $user)
    {
        $parties =$em->getRepository(Partie::class)->findPartieJoueur($user);
        return $this->render('lancement_partie/showDefie.html.twig',
            [
                'parties'=>$parties
            ]);

    }

}
