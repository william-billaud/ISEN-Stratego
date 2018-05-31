<?php

namespace App\Controller;

use App\Entity\Partie;
use App\Security\Voter\PartieVoter;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GameController extends Controller
{
    /**
     * @Route("/game", name="game")
     */
    public function index()
    {
        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }

    /**
     * @Route("/game/{id}", name="play_game", requirements={"id": "\d+"})
     */
    public function gameAction(int $id)
    {
        return $this->render('game/index.html.twig',[
            "idPartie"=>$id
        ]);
    }

    /**
     * @Route("/game/init/{id}", name="init_game", requirements={"id": "\d+"})
     */
    public function initGameAction(int $id)
    {
        return $this->render('game/init.html.twig',[
            "idPartie"=>$id
        ]);
    }

    /**
     * @Route("/game/abandone/{id}", name="leaves_game", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_USER')")
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
}
