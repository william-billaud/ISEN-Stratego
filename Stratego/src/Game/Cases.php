<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 05/05/18
 * Time: 10:52
 */

namespace App\Game;


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

abstract class Cases
{
    /**
     * @var int abscisse de la case
     */
    private $X;
    /**
     * @var int ordonnée de la case
     */
    private $Y;
    /**
     * @var Tablier tableau dans laquelle est situé la cases
     */
    protected $tablier;
    protected $value=-1;

    protected $name ="?";

    /**
     * @var int Numero indiquant le propriétaire de la case
     * 0 => Le jeu (case vide/lacs)
     * 1 => Le joueur bleu
     * -1 => Le joueur rouge
     */
    protected $proprietaire =0;

    /**
     * Cases constructor.
     * @param $X
     * @param $Y
     * @param Tablier $tablier
     */
    public function __construct(Tablier $tablier,$X, $Y )
    {
        $this->tablier = $tablier;
        $this->setPosition($X,$Y);
    }


    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->X;
    }

    /**
     * @param mixed $X
     */
    private function setX($X): void
    {
        if($X>=0 && $X<10)
        {
            $this->X = $X;
        }else{
            throw new \InvalidArgumentException("X (l'abscisse doit etre compris entre 0 et 9");
        }
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->Y;
    }

    /**
     * @param mixed $Y
     */
    private function setY($Y): void
    {
        if($Y>=0 && $Y<10)
        {
            $this->Y = $Y;
        }else{
            throw new \InvalidArgumentException("Y (l'ordonne doit être compris entre 0 et 9");
        }
    }

    protected function setPosition($X,$Y){
        $this->setX($X);
        $this->setY($Y);
        $this->tablier->setTabValeurs($X,$Y,$this);
    }

    public function __toString()
    {
        return $this->name." J".$this->getProprietaire();
    }

    /**
     * @return int
     */
    public function getProprietaire(): int
    {
        return $this->proprietaire;
    }

    /**
     * @param int $pro
     */
    public function setProprietaire(int $pro): void
    {
        if($pro==1 || $pro ==-1){
            $this->proprietaire=$pro;
        }else{
            throw new \InvalidArgumentException("Proprietaire autorisée : 1 joueur Bleu, -1 joueur Rouge");
        }
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     */
    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    public function seDeplaceEn(?int $x,?int $y):bool{
        throw new \InvalidArgumentException("les cases".$this->name." ne peuvent etre deplace");
    }


    public static function pionsFactory($tab,$x,$y,$value,$proprio=0)
    {
        switch ($value)
        {
            case -1:
                new CasesInconnue($tab,$x,$y,$proprio);
                break;
            case -2:
                new CasesVide($tab,$x,$y);
                break;
            case -3:
                new Lacs($tab,$x,$y);
                break;
            case 0:
                new Drapeau($tab,$x,$y,$proprio);
                break;
            case 1:
                new Espions($tab,$x,$y,$proprio);
                break;
            case 2:
                new Soldats($tab,$x,$y,$proprio);
                break;
            case 3:
                new Demineurs($tab,$x,$y,$proprio);
                break;
            case 4:
                new Sergent($tab,$x,$y,$proprio);
                break;
            case 5:
                new Lieutenants($tab,$x,$y,$proprio);
                break;
            case 6:
                new Capitaine($tab,$x,$y,$proprio);
                break;
            case 7:
                new Lieutenants_Colonels($tab,$x,$y,$proprio);
                break;
            case 8:
                new Colonels($tab,$x,$y,$proprio);
                break;
            case 9:
                new General($tab,$x,$y,$proprio);
                break;
            case 10:
                new Marechal($tab,$x,$y,$proprio);
                break;
            case 11:
                new Mines($tab,$x,$y,$proprio);
                break;
        }
    }
}