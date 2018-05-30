<?php

namespace App\Controller;

use App\Entity\Partie;
use App\Game\CasesVide;
use App\Game\Pions\Pions;
use App\Security\Voter\PartieVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ApiController
 * @package App\Controller
 * @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * @Route("/getTab/{id}", name="api_get_tab",requirements={"id": "\d+"})
     * @param EntityManagerInterface $em
     * @param int $id identifiant de la partie
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(EntityManagerInterface $em, int $id)
    {
        $partie=$em->find(Partie::class,$id);
        if($partie==null)
        {
            return $this->json(["error"=>"la partie n'existe pas"]);
        }
        $numero=0;
        if($this->isGranted(PartieVoter::Joueur1,$partie))
        {
            $numero=1;
        }elseif ($this->isGranted(PartieVoter::Joueur2,$partie))
        {
            $numero=-1;
        }

        $arr=$partie->getTablier()->getTabJoueur($numero);
        return $this->json(["tab"=>$arr,"peut_jouer"=>$this->isGranted(PartieVoter::PeutJouer,$partie),"derniereAttaque"=>$partie->getTablier()->dernierCombat]);
    }

    /**
     * @Route("/joue/{id}",name="api_joue_coup",requirements={"id": "\d+"}),
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param Partie $partie
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function joueCoup(EntityManagerInterface $em,Request $request,Partie $partie)
    {
        $error=null;
        if($partie==null)
        {
            return $this->json(["error"=>"la partie n'existe pas"]);
        }
        $joueur=$partie->getTourJoueur();
        try{
            if($this->isGranted(PartieVoter::PeutJouer,$partie))
            {
                throw new \InvalidArgumentException("Ce n'est pas votre tour de jouer");
            }
            if(!$partie->getTablier()->getTabValeurs($request->get("x_o"),$request->get("y_o"))->getProprietaire()==$partie->getTourJoueur() && $joueur!=0)
            {
                throw  new \InvalidArgumentException("Cette piece n'est pas à vous");
            }

            $partie->getTablier()->getTabValeurs($request->get("x_o"),$request->get("y_o"))->seDeplaceEn($request->get("x_a"),$request->get("y_a"));
            $partie->setNumeroTour($partie->getNumeroTour()+1);
            $partie->setTourJoueur(-$partie->getTourJoueur());
        }catch (\InvalidArgumentException $e)
        {
            $error=$e->getMessage();
        }
        if($partie->getTablier()->estFini!=0) {
            $partie->setEtatPartie(Partie::FINI);
            $partie->setTourJoueur($joueur);
        }
        $em->flush();
        return $this->json(["error"=>$error,"tab"=>$partie->getTablier()->getTabJoueur($joueur),"peut_jouer"=>$this->isGranted(PartieVoter::PeutJouer,$partie),"derniereAttaque"=>$partie->getTablier()->dernierCombat]);
    }


    /**
     * @Route("/coupValide/{id}",name="verifie_coup_valide",requirements={"id": "\d+"}),
     * @param Request $request
     * @param Partie|null $partie
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function verifieCoupValide(Request $request,Partie $partie=null)
    {
        $joueur=$partie->getTourJoueur();
        $validite=true;
        $error="";
        try{
            if($this->isGranted(PartieVoter::PeutJouer,$partie))
            {
                throw new \InvalidArgumentException("Ce n'est pas votre tour de jouer");
            }
            if(!$partie->getTablier()->getTabValeurs($request->get("x_o"),$request->get("y_o"))->getProprietaire()==$joueur || $joueur==0)
            {
                throw  new \InvalidArgumentException("Cette piece n'est pas à vous");
            }
            /** @var Pions $pion */
            $pion=$partie->getTablier()->getTabValeurs($request->get("x_o"),$request->get("y_o"));
            if(!($pion instanceof Pions))
            {
                throw new \InvalidArgumentException("Ceci n'est pas un pions");
            }
            if(!$pion->DistanceDeplacementEstValide($request->get("x_a"),$request->get("y_a"))){
                throw new \InvalidArgumentException("Distance de deplacement invalide");
            }
            $cible=$partie->getTablier()->getTabValeurs($request->get("x_a"),$request->get("y_a"));
            //Joueur Rouge =-1, joueur Bleu =1
            if(!($cible instanceof Pions || $cible instanceof CasesVide) || $cible->getProprietaire()==$pion->getProprietaire() )
            {
                throw new \InvalidArgumentException("La destination n'est pas un cible valide");
            }
        }catch (\InvalidArgumentException $e)
        {
            $validite =false;
            $error=$e->getMessage();
        }
        return $this->json(["error"=>$error,"valide"=>$validite],200,["Access-Control-Allow-Origin"=>"*"]);
    }
}
