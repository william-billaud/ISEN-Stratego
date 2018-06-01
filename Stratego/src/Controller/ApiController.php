<?php

namespace App\Controller;

use App\Entity\Partie;
use App\Game\CasesVide;
use App\Game\Pions\Pions;
use App\Security\Voter\PartieVoter;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class ApiController
 * @package App\Controller
 * @Route("/api")
 */
class ApiController extends Controller
{
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
            if($partie->getEtatPartie()==Partie::FINI)
            {
                throw new \InvalidArgumentException("Partie Fini : ".(($partie->getTourJoueur()==1)?$partie->getJoueur1():$partie->getJoueur2())." a gagné !");
            }
            if($request->get("x_o")== null || $request->get("y_o")==null || $request->get("x_a")== null || $request->get("y_a")==null)
            {
                throw new \InvalidArgumentException("arguments manquants");
            }
            if(!$this->isGranted(PartieVoter::PeutJouer,$partie))
            {
                throw new \InvalidArgumentException("Ce n'est pas votre tour de jouer");
            }
            if($partie->getTablier()->getTabValeurs($request->get("x_o"),$request->get("y_o"))->getProprietaire()!=$partie->getTourJoueur() || $joueur==0)
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
        return $this->json(["error"=>$error,"tab"=>$partie->getTabjoueur($this->getUser()),"peut_jouer"=>$this->isGranted(PartieVoter::PeutJouer,$partie),"derniereAttaque"=>$partie->getTablier()->dernierCombat]);
    }

    /**
     * @Route("/init/{id}",name="positionne_pieces_depart",requirements={"id": "\d+"}),
     * @param Request $request
     * @param Partie|null $partie
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Security("has_role('ROLE_USER')")
     */
    public function positionnePieceDepart(Request $request,Partie $partie,EntityManagerInterface $em)
    {
        if($partie==null)
        {
            return $this->json(["error"=>"la partie n'existe pas"]);
        }
        $validite=true;
        $x=$request->get("x");
        $y=$request->get("y");
        $value=$request->get("value");
        $side=0;
        $side=($this->isGranted(PartieVoter::InitJ1,$partie))?1:$side;
        $side=($this->isGranted(PartieVoter::InitJ2,$partie))?-1:$side;
        try{
            if($partie->getEtatPartie()==Partie::FINI)
            {
                throw new \InvalidArgumentException("Partie Fini : ".(($partie->getTourJoueur()==1)?$partie->getJoueur1():$partie->getJoueur2())." a gagné !");
            }
            if($partie->getEtatPartie()==Partie::ENCOUR)
            {
                throw new \InvalidArgumentException("Partie en cours !");
            }
            if($x== null || $y==null || $value==null)
            {
                throw new \InvalidArgumentException("arguments manquants");
            }
            if($side==1)
            {
                if($y>=0 && $y<4&& $y!=null)
                {
                    Pions::pionsFactory($partie->getTablier(),$x,$y,$value,1);
                }else{
                    throw new \InvalidArgumentException("Vous souhaitez positionner des pions hors de votre coté 1");
                }
            }
            elseif($side==-1)
            {
                $side =-1;
                if($y>5 && $y<10)
                {
                    Pions::pionsFactory($partie->getTablier(),$x,$y,$value,-1);
                }else{
                    throw new \InvalidArgumentException("Vous souhaitez positionner des pions hors de votre coté 2 ");

                }
            }else{
                throw new \InvalidArgumentException("Ce n'est pas votre parties");
            }
        }catch (\InvalidArgumentException $e)
        {
            $validite =false;
            $error=$e->getMessage();
        }
        $em->flush();
        return $this->json(["error"=>$error,"valide"=>$validite,"tab"=>$partie->getTabjoueur($this->getUser()),"restante"=>$partie->getNbPieceAPlacer($this->getUser()),"side"=>$side]);


    }


}
