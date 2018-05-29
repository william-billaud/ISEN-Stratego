<?php

namespace App\Controller;

use App\Entity\Partie;
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
        $arr=$partie->getTablier()->getTabJoueur(1);
        return $this->json(["tab"=>$arr]);
    }

    /**
     * @Route("/joue/{id}",name="api_joue_coup",requirements={"id": "\d+"}),
     * @param EntityManagerInterface $em
     * @param int $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function joueCoup(EntityManagerInterface $em,int $id,Request $request)
    {
        $partie=$em->find(Partie::class,$id);
        $error=null;
        try{

            $partie->getTablier()->getTabValeurs($request->get("x_o"),$request->get("y_o"))->seDeplaceEn($request->get("x_a"),$request->get("y_a"));
        }catch (\InvalidArgumentException $e)
        {
            $error=$e->getMessage();
        }
        if($partie->getTablier()->estFini!=0)
        {
            $partie->setEtatPartie("J".$partie->getTablier()->estFini." à gagné la partie");
        }
        $em->flush();
        return $this->json(["error"=>$error,"tab"=>$partie->getTablier()->getTabJoueur(1)]);
    }

}
