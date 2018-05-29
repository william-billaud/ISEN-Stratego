<?php

namespace App\Controller;

use App\Entity\Partie;
use App\Security\Voter\PartieVoter;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(EntityManagerInterface $em,int $id)
    {
        $partie=$em->find(Partie::class,$id);
        $numero=0;
        if($this->isGranted(PartieVoter::Joueur1,$partie))
        {
            $numero=1;
        }elseif ($this->isGranted(PartieVoter::Joueur2,$partie))
        {
            $numero=-1;
        }
        $arr=$partie->getTablier()->getTabJoueur($numero);
        return $this->json(["tab"=>$arr]);
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
        if($this->isGranted(PartieVoter::JoueJ1,$partie))
        {
            $joueur=1;
        }elseif ($this->isGranted(PartieVoter::JoueJ2,$partie))
        {
            $joueur=-1;

        }else{
            $joueur=0;
            $error = "vous n'avez pas le droit de jouer sur cette partie";
        }
        if(!$partie->getTablier()->getTabValeurs($request->get("x_o"),$request->get("y_o"))->getProprietaire()==$joueur)
        {
            $error="Cette piece n'est pas à vous";
        }else{
            try{
                $partie->getTablier()->getTabValeurs($request->get("x_o"),$request->get("y_o"))->seDeplaceEn($request->get("x_a"),$request->get("y_a"));
                $partie->setNumeroTour($partie->getNumeroTour()+1);
            }catch (\InvalidArgumentException $e)
            {
                $error=$e->getMessage();
            }
            if($partie->getTablier()->estFini!=0)
            {
                $partie->setEtatPartie("J".$partie->getTablier()->estFini." à gagné la partie");
            }
        }
        $em->flush();
        return $this->json(["error"=>$error,"tab"=>$partie->getTablier()->getTabJoueur($joueur)]);
    }

}
