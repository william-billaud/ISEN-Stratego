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
     * @Security("has_role('ROLE_USER')")
     * @Route("/tab",name="tablier_ex")
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function afficheTablier(EntityManagerInterface $em)
    {

        $tab = new Tablier();
        //placement de 8 Soldats
        for ($i = 0; $i < 8; $i++) {
            new Soldats($tab, $i, 0, 1);
            new Soldats($tab, $i, 9, -1);
        }
        //placement de 4 Sergent/capitaine/lieutenenant + 4 démineurs
        for ($i = 0; $i < 4; $i++) {
            new Sergent($tab, $i, 1, 1);
            new Sergent($tab, $i, 8, -1);
            new Capitaine($tab, $i + 4, 1, 1);
            new Capitaine($tab, $i + 4, 8, -1);
            new Lieutenants($tab, $i, 2, 1);
            new Lieutenants($tab, $i, 7, -1);
            new Demineurs($tab, $i, 3, 1);
            new Demineurs($tab, $i, 6, -1);
        }
        //Placement du dernier demineurs :
        new Demineurs($tab, 4, 3, 1);
        new Demineurs($tab, 4, 6, -1);
        //Placement des 3 lieutenants_colonnel et 6 mines
        for ($i = 0; $i < 3; $i++) {
            new Mines($tab, $i + 4, 2, 1);
            new Mines($tab, $i + 7, 2, 1);
            new Mines($tab, $i + 4, 7, -1);
            new Mines($tab, $i + 7, 7, -1);
            new Lieutenants_Colonels($tab, $i + 5, 3, 1);
            new Lieutenants_Colonels($tab, $i + 5, 6, -1);
        }
        //1 Drapeau par equipes
        new Drapeau($tab, 8, 1, 1);
        new Drapeau($tab, 8, 8, -1);
        //1 Espion par équipe
        new Espions($tab, 9, 1, 1);
        new Espions($tab, 9, 8, -1);
        //1 General par equipe
        new General($tab, 8, 0, 1);
        new General($tab, 8, 9, -1);
        //1 Marechal par équipe
        new Marechal($tab, 9, 0, 1);
        new Marechal($tab, 9, 9, -1);
        //2 Colonels par équipe
        new Colonels($tab, 8, 3, 1);
        new Colonels($tab, 9, 3, 1);
        new Colonels($tab, 8, 6, -1);
        new Colonels($tab, 9, 6, -1);
        $p = new Partie();
        $p->setTablier($tab);
        $p->setDateDebut(new \DateTime());
        $p->setEtatPartie("DEBUT");
        $p->setJoueur1($em->find(User::class,1));
        $p->setJoueur2($em->find(User::class,2));
        $em->persist($p);
        $em->flush();
        dump($p->getJsonTab());
        return $this->render('base/afficheTablier.html.twig', [
            'tablier' => $p->getTablier()->getNormalizedTab(),
        ]);

    }


    /**
     * @Route("/affiche/{id}",name="affiche_tab",requirements={"id": "\d+"}),
     * @param EntityManagerInterface $em
     * @param UserInterface $user
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function afficheTab(EntityManagerInterface $em,UserInterface $user=null,int $id){
        $partie=$em->find(Partie::class,$id);
        if($this->isGranted(PartieVoter::Joueur1,$partie))
        {
            $numero=1;
        }elseif ($this->isGranted(PartieVoter::Joueur2,$partie))
        {
            $numero=-1;
        }else{
            $numero=0;
        }
        return $this->render('base/afficheTablier.html.twig', [
            'tablier' => $partie->getTablier()->getTabJoueur($numero)
        ]);
    }
}