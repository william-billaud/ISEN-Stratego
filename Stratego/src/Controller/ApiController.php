<?php

namespace App\Controller;

use App\Entity\Partie;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/getTab", name="api_get_tab")
     */
    public function index(EntityManagerInterface $em)
    {
        $partie=$em->find(Partie::class,27);
        $arr=$partie->getTablier()->getTabJoueur(1);
        return $this->json($arr);
    }

}
