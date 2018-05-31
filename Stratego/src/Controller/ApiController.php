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

        $arr=$partie->getTabjoueur($this->getUser());
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
            if($request->get("x_o")== null || $request->get("y_o")==null || $request->get("x_a")== null || $request->get("y_a")==null)
            {
                throw new \InvalidArgumentException("Il manque des arguments!");
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
     * @Route("/coupValide/{id}",name="verifie_coup_valide",requirements={"id": "\d+"}),
     * @param Request $request
     * @param Partie|null $partie
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function verifieCoupValide(Request $request,Partie $partie=null)
    {
        if($partie==null)
        {
            return $this->json(["error"=>"la partie n'existe pas"]);
        }
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
        return $this->json(["error"=>$error,"valide"=>$validite]);
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
        $error="";
        $x=$request->get("x");
        $y=$request->get("y");
        $value=$request->get("value");
        $side=0;
        try{
            if($this->isGranted(PartieVoter::InitJ1,$partie))
            {
                $side=1;
                if($y>=0 && $y<4&& $y!=null)
                {
                    Pions::pionsFactory($partie->getTablier(),$x,$y,$value,1);
                }else{
                    throw new \InvalidArgumentException("Vous souhaitez possitionner des pions hors de votre coté 1");
                }
            }
            elseif($this->isGranted(PartieVoter::InitJ2,$partie))
            {
                $side =-1;
                if($y>5 && $y<10)
                {
                    Pions::pionsFactory($partie->getTablier(),$x,$y,$value,-1);
                }else{
                    throw new \InvalidArgumentException("Vous souhaitez possitionner des pions hors de votre coté 2 ");

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
