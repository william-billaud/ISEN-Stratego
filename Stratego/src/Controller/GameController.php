<?php

namespace App\Controller;

use App\Entity\Partie;
use App\Entity\User;
use App\Security\Voter\PartieVoter;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;

class GameController extends Controller
{

    /**
     * !!!! Before testing this page, launch the ChatServerCommand with this command line in new terminal : " php bin/console f:a:c:m " !!!!
     * @Security("has_role('ROLE_USER')")
     * @Route("/game/{id}", name="play_game", requirements={"id": "\d+"})
     */
    public function gameAction(Partie $partie=null)
    {
        if($partie==null)
        {
            $this->addFlash('error',"la partie n'existe pas");
            return $this->redirectToRoute('base');
        }
        $channel=$partie->getJoueur1()."+".$partie->getJoueur2();
        $user = $this->getUser();
        $userName = $user->getUsername();

        return $this->render('game/index.html.twig',[
            'userName' => $userName,
            "idPartie"=>$partie->getId(),
            "gameChannel"=>$channel,
            'ws_url' => 'localhost:8080'
        ]);
    }

    /**
     * !!!! Before testing this page, launch the ChatServerCommand with this command line in new terminal : " php bin/console f:a:c:m " !!!!
     * @Security("has_role('ROLE_USER')")
     * @Route("/game/init/{id}", name="init_game", requirements={"id": "\d+"})
     */
    public function initGameAction(Partie $partie=null)
    {
        if($partie==null)
        {
            $this->addFlash('error',"la partie n'existe pas");
            return $this->redirectToRoute('base');
        }
        $channel=$partie->getJoueur1()."+".$partie->getJoueur2();
        $user = $this->getUser();
        $userName = $user->getUsername();

        return $this->render('game/init.html.twig',[
            'userName' => $userName,
            "idPartie"=>$partie->getId(),
            "gameChannel"=>$channel,
            'ws_url' => 'localhost:8080'
        ]);
    }

    /**
     * @Route("/game/abandone/{id}", name="leaves_game", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_USER')")
     * @param Partie|null $partie
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function abandonner(Partie $partie=null,EntityManagerInterface $em)
    {
        if($partie==null)
        {
            $this->addFlash('error',"la partie n'existe pas");
            return $this->redirectToRoute('base');
        }
        if($this->isGranted(PartieVoter::Joueur1,$partie))
        {
            $partie->setEtatPartie(Partie::FINI);
            $partie->setTourJoueur(-1);
        }elseif ($this->isGranted(PartieVoter::Joueur2,$partie)) {
            $partie->setEtatPartie(Partie::FINI);
            $partie->setTourJoueur(1);
        }else{
            $this->addFlash('error',"Vous n'avez pas le droit d'acceder à la partie");
            return $this->redirectToRoute('base');
        }

        $this->addFlash('notice',"partie abandonnée");
        $em->flush();
        return $this->redirectToRoute('base');
    }

    /**
     * @Route("game/valide/{id}",name="valide_positionnement_pieces",requirements={"id": "\d+"})
     * @Security("has_role('ROLE_USER')")
     * @param Partie|null $partie
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function valideInitialisation(Partie $partie=null,EntityManagerInterface $em)
    {
        if($partie==null)
        {
            $this->addFlash('error',"la partie n'existe pas");
            return $this->redirectToRoute('base');
        }
        if($this->isGranted(PartieVoter::Joueur1,$partie))
        {
            $joueur=1;
        }elseif ($this->isGranted(PartieVoter::Joueur2,$partie)) {
            $joueur=-1;
        }else{
            $this->addFlash('error',"vous n'avez pas accès à la partie");
            return $this->redirectToRoute('base');
        }


        if(!$partie->getTablier()->verifiePlacementJoueurOK($joueur))
        {
            $this->addFlash('error',"Positionnement Incorrecte");
            return $this->redirectToRoute('init_game',["id"=>$partie->getId()]);
        }

        if($partie->getTourJoueur()==0 || $partie->getTabjoueur()==null ||$partie->getTourJoueur()==-$joueur )
        {
            $partie->setTourJoueur(-$joueur);

            $em->flush();
            $this->addFlash('notice',"Validation enregistrée");
            return $this->redirectToRoute('init_game',["id"=>$partie->getId()]);
        }else if($partie->getTourJoueur()== $joueur)
        {
            $partie->setEtatPartie(Partie::ENCOUR);
            $partie->setNumeroTour(0);
            $partie->setTourJoueur(1);
            $em->flush();
            return $this->redirectToRoute('play_game',["id"=>$partie->getId()]);
        }

        return $this->redirectToRoute('base');

    }
}
