<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 05/05/18
 * Time: 10:52
 */

namespace App\Game;


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
            throw new \InvalidArgumentException("Y (l'ordonné doit être compris entre 0 et 9");
        }
    }

    protected function setPosition($X,$Y){
        $this->setX($X);
        $this->setY($Y);
        $this->tablier->setTabValeurs($X,$Y,$this);
    }

    public function __toString()
    {
        return $this->getX()." ".$this->name." J".$this->getProprietaire()." ".$this->getY();
    }

    /**
     * @return int
     */
    public function getProprietaire(): int
    {
        return $this->proprietaire;
    }

    /**
     * @param int $proprietaire
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


}