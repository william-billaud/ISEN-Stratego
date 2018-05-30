<?php

namespace App\Entity;

use App\Game\CasesInconnue;
use App\Game\CasesVide;
use App\Game\Lacs;
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
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartieRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Partie
{

    const ATTENTE="EN ATTENTE D ACCEPTION";
    const INITIALISATION="POSITIONNEMENT DES PIECES";
    const ENCOUR="PARTIE EN COURS";
    const FINI="PARTIE FINI";
    const DEBUT="DEBUT";
    const DECLINE="DECLINE";


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etatPartie;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="json_array")
     */
    private $jsonTab;

    private $Tablier;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="partiesJoueur1")
     * @ORM\JoinColumn(nullable=true)
     */
    private $Joueur1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="partiesJoueur2")
     * @ORM\JoinColumn(nullable=true)
     */
    private $Joueur2;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numeroTour;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tour_joueur;

    public function getId()
    {
        return $this->id;
    }


    public function getEtatPartie(): ?string
    {
        return $this->etatPartie;
    }

    public function setEtatPartie(string $etatPartie): self
    {
        $this->etatPartie = $etatPartie;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getTablier():Tablier
    {
        return $this->Tablier;
    }

    public function setTablier(Tablier $Tablier): self
    {
        $this->Tablier=$Tablier;
        return $this;
    }

    /**
     * @ORM\PreFlush()
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     */
    public function serializeTab()
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer(null,null,null,new ReflectionExtractor()));
        $serializer = new Serializer($normalizers, $encoders);
        $this->setJsonTab($serializer->serialize($this->Tablier,'json'));
    }
    /**
     * @ORM\PostLoad()
     */
    public function unserializeTab()
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer(null,null,null,new ReflectionExtractor()));
        $serializer = new Serializer($normalizers, $encoders);
        $res=$serializer->decode($this->jsonTab,'json');
        $tab=new Tablier();
        /*
         * Si il y a une erreur dans le JSOn crée un tableau vide
         */
        if(empty($res["tab"]))
        {
            $this->setTablier(new Tablier());
            return;
        }
        foreach ($res["tab"] as $value)
        {
            foreach ($value as $case)
            {
                switch ($case["value"])
                {
                    case -1:
                        new CasesInconnue($tab,$case["x"],$case["y"],$case["proprietaire"]);
                        break;
                    case -2:
                        new CasesVide($tab,$case["x"],$case["y"]);
                        break;
                    case -3:
                        new Lacs($tab,$case["x"],$case["y"]);
                        break;
                    case 0:
                        new Drapeau($tab,$case["x"],$case["y"],$case["proprietaire"]);
                        break;
                    case 1:
                        new Espions($tab,$case["x"],$case["y"],$case["proprietaire"]);
                        break;
                    case 2:
                        new Soldats($tab,$case["x"],$case["y"],$case["proprietaire"]);
                        break;
                    case 3:
                        new Demineurs($tab,$case["x"],$case["y"],$case["proprietaire"]);
                        break;
                    case 4:
                        new Sergent($tab,$case["x"],$case["y"],$case["proprietaire"]);
                        break;
                    case 5:
                        new Lieutenants($tab,$case["x"],$case["y"],$case["proprietaire"]);
                        break;
                    case 6:
                        new Capitaine($tab,$case["x"],$case["y"],$case["proprietaire"]);
                        break;
                    case 7:
                        new Lieutenants_Colonels($tab,$case["x"],$case["y"],$case["proprietaire"]);
                        break;
                    case 8:
                        new Colonels($tab,$case["x"],$case["y"],$case["proprietaire"]);
                        break;
                    case 9:
                        new General($tab,$case["x"],$case["y"],$case["proprietaire"]);
                        break;
                    case 10:
                        new Marechal($tab,$case["x"],$case["y"],$case["proprietaire"]);
                        break;
                    case 11:
                        new Mines($tab,$case["x"],$case["y"],$case["proprietaire"]);
                        break;
                }
            }
        }
        $this->setTablier($tab);
    }

    /**
     * @return mixed
     */
    public function getJsonTab()
    {
        return $this->jsonTab;
    }

    /**
     * @param mixed $jsonTab
     */
    public function setJsonTab($jsonTab): void
    {
        $this->jsonTab = $jsonTab;
    }

    public function getJoueur1(): ?User
    {
        return $this->Joueur1;
    }

    public function setJoueur1(?User $Joueur1): self
    {
        $this->Joueur1 = $Joueur1;

        return $this;
    }

    public function getJoueur2(): ?User
    {
        return $this->Joueur2;
    }

    public function setJoueur2(?User $Joueur2): self
    {
        $this->Joueur2 = $Joueur2;

        return $this;
    }

    public function getNumeroTour(): ?int
    {
        return $this->numeroTour;
    }

    public function setNumeroTour(?int $numeroTour): self
    {
        $this->numeroTour = $numeroTour;

        return $this;
    }

    public function getTourJoueur(): ?int
    {
        return $this->tour_joueur;
    }

    public function setTourJoueur(?int $tour_joueur): self
    {
        $this->tour_joueur = $tour_joueur;

        return $this;
    }
}
