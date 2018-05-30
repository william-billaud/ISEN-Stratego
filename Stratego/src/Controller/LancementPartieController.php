<?php

namespace App\Controller;

use App\Entity\Partie;
use App\Entity\User;
use App\Security\Voter\PartieVoter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
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
     * @return Response
     */
    public function index(UserInterface $user, int $idJoueurDefie, EntityManagerInterface $em)
    {
        $userDefie=$em->find(User::class,$idJoueurDefie);
        $partie=new Partie();
        /** @var User $user */
        $partie->creePartie($user,Partie::ATTENTE);
        $partie->setJoueur2($userDefie);
        $em->persist($partie);
        $em->flush();
        return $this->redirectToRoute('base');
    }

    /**
     * @Route("/accepteDefie/{idPartie}",name="accepteDefie",requirements={"idPartie": "\d+"})
     * @Security("has_role('ROLE_USER')")
     * @param int $idPartie
     * @param EntityManagerInterface $em
     * @return Response
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
     * @param int $idPartie
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_USER')")
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
     * @Security("has_role('ROLE_USER')")
     * @Route("/showDefies",name="affiche_defie"),
     * @param EntityManagerInterface $em
     * @param UserInterface $user
     * @return Response
     */
    public function showDefieEnAttente(EntityManagerInterface $em,UserInterface $user)
    {
        $parties =$em->getRepository(Partie::class)->findPartieJoueurDefie($user);
        return $this->render('lancement_partie/showDefie.html.twig',
            [
                'parties'=>$parties
            ]);

    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/defieAll",name="defie_all"))
     * @param EntityManagerInterface $em
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function defieAll(EntityManagerInterface $em,UserInterface $user)
    {

        try {
            $partie = $em->getRepository(Partie::class)->findPartieEnAttenteJoueur($user);
            if($partie==null)
            {
                $partie=new Partie();
                /** @var User $user */
                $partie->creePartie($user);

            }else{
                /** @var User $user */
                $partie->setJoueur2($user);
                $partie->setEtatPartie(Partie::INITIALISATION);
            }
            $em->persist($partie);
        } catch (NonUniqueResultException $e) {
            //todo problème!
        }
        $em->flush();
        return $this->redirectToRoute('base');
    }

    /**
     * @Route("/montrePartie",name="montrePartie")
     * @param EntityManagerInterface $em
     * @param UserInterface $user
     * @return Response
     * @Security("has_role('ROLE_USER')")
     *
     */
    public function showPartie(EntityManagerInterface $em,UserInterface $user=null)
    {
        /** @var Partie[] $parties */
        $init =$em->getRepository(Partie::class)->findPartieOuJoueurEstPresent($user,Partie::INITIALISATION);
        $enCours =$em->getRepository(Partie::class)->findPartieOuJoueurEstPresent($user,Partie::ENCOUR);
        return $this->render('lancement_partie/montreParties.html.twig',
        [
           'initilisation'=>$init,
           'cours'=>$enCours
        ]);

    }
}
