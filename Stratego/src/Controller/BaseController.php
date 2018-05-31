<?php

namespace App\Controller;

use App\Entity\Partie;
use App\Entity\User;
use App\Game\Pions\Capitaine;
use App\Game\Pions\Colonels;
use App\Game\Pions\Demineurs;
use App\Game\Pions\Drapeau;
use App\Game\Pions\Espions;
use App\Game\Pions\General;
use App\Game\Pions\Lieutenants;
use App\Game\Pions\Lieutenants_Colonels;
use App\Game\Pions\Marechal;
use App\Game\Pions\Mines;
use App\Game\Pions\Sergent;
use App\Game\Pions\Soldats;
use App\Game\Tablier;
use App\Security\Voter\PartieVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\User\UserInterface;


class BaseController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/", name="base")
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(UserInterface $user=null)
    {
        $userManager = $this->getDoctrine()->getRepository(User::class);
        if($user!=null)
        {
            $users = $userManager->findAllOthers($user->getId());
        }
        return $this->render('base/index.html.twig', [
            'users' => $users,
            'controller_name' => 'BaseController',
        ]);
    }


    /**
     * @Route("/regles", name="regles")
     * @Security("has_role('ROLE_USER')")
     */
    public function regles()
    {
        return $this->render('base/regles.html.twig', [
            'controller_name' => 'ReglesController',
        ]);
    }
}