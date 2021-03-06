<?php

namespace App\Controller;

use App\Entity\Partie;
use App\Entity\User;
use App\Security\Voter\PartieVoter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;

class LancementPartieController extends Controller
{
    /**
     * @Route("/defi/{idJoueurDefie}", name="lancementDefi")
     * @Security("has_role('ROLE_USER')")
     * @param UserInterface $user
     * @param int $idJoueurDefie
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function lanceDefi(UserInterface $user, int $idJoueurDefie, EntityManagerInterface $em)
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
     * @Route("/accepteDefi/{idPartie}",name="accepteDefie",requirements={"idPartie": "\d+"})
     * @ParamConverter("partie", options={"mapping"={"idPartie"="id"}})
     * @Security("has_role('ROLE_USER')")
     * @param Partie $partie
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function accepteDefie(Partie $partie, EntityManagerInterface $em)
    {
        /** @var User $user */
        if($partie!=null && $this->isGranted(PartieVoter::Joueur2,$partie))
        {
            $partie->setEtatPartie(Partie::INITIALISATION);
        }else{
            return $this->redirectToRoute('base');
        }
        $em->flush();

        return $this->redirectToRoute('init_game',["id"=>$partie->getId()]);
    }

    /**
     * @Route("/refuseDefi/{idPartie}",name="refuseDefie",requirements={"idPartie": "\d+"})
     * @ParamConverter("partie", options={"mapping"={"idPartie"="id"}})
     * @param Partie $partie
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_USER')")
     */
    public function refuseDefie(Partie $partie,EntityManagerInterface $em)
    {

        /** @var User $user */
        if($partie !=null && $this->isGranted(PartieVoter::Joueur2,$partie))
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
                $this->addFlash('notice',"Une recherche de partie a été lancé");
            }else{

                /** @var User $user */
                if($user->isEquals($partie->getJoueur1()))
                {
                    $this->addFlash('notice',"Vous avez déjà lancé un defis général");
                    return $this->redirectToRoute('base');
                }else{
                    /** @var User $user */
                    $partie->setJoueur2($user);
                    $partie->setEtatPartie(Partie::INITIALISATION);
                    $this->addFlash('notice',"Vous avez rejoins une partie contre ".$partie->getJoueur1());
                }
            }
            $em->persist($partie);
        } catch (NonUniqueResultException $e) {
            dump($e->getMessage());
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
