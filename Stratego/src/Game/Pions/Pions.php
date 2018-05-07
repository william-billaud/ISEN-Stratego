<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 05/05/18
 * Time: 11:02
 */

namespace App\Game\Pions;


use App\Game\Cases;
use App\Game\CasesVide;

abstract class Pions extends Cases
{
    protected $value;

    /**
     * @param $x int absisse de destination
     * @param $y int origine de destination
     * @return bool
     * true si le pions peut essayer de se déplacer sur cette case
     */
    public function seDeplaceEn($x, $y):bool {
        if(!$this->DistanceDeplacementEstValide($x,$y)){
            return false;
        }
        $cible=$this->tablier->getTabValeurs($x,$y);
        //Joueur Rouge =-1, joueur Bleu =1
        if($cible instanceof Pions && $cible->proprietaire==-$this->proprietaire && (abs($cible->proprietaire)==1)){
            $this->attaque($cible);
            return true;
        }elseif ($this->tablier->getTabValeurs($x,$y) instanceof CasesVide)
        {
            $this->changePlacePion($x,$y);
            return true;
        }
        return false;
    }
    public function changePlacePion($x,$y){
        $this->libereCase();
        $this->setPosition($x,$y);
    }
    public function attaque( Pions $pion){
        if($this->value>$pion->value)
        {
            $x=$pion->getX();
            $y=$pion->getY();
            $pion->libereCase();
            $this->changePlacePion($x,$y);
        }elseif ($this->value<$pion->value)
        {
            $this->libereCase();
        }elseif ($this->value=$pion->value)
        {
            $this->libereCase();
            $pion->libereCase();
        }
    }
    public function libereCase(){
        $this->tablier->setTabValeurs($this->getX(),$this->getY(),new CasesVide($this->tablier,$this->getX(),$this->getY()));
    }

    /**fonction permettant de determiner si la distance deplacement du Pions vers cette case est valide
     *
     * @param int $x
     * @param int $y
     * @return bool
     */
    public function DistanceDeplacementEstValide(int $x,int $y):bool {
        if($this->getX()==$x){
            return (abs($this->getY()-$y)==1)?true:false;
            }elseif ($this->getY()==$y){
            return (abs($this->getX()-$x)==1)?true:false;
        }else{
            return false;
        }
    }
    public function setProprietaire($pro)
    {
        if($pro==1 || $pro ==-1){
            $this->proprietaire=$pro;
        }else{
            throw new \InvalidArgumentException("Proprietaire authorisée : 1 joueur Bleu, -1 joueur Rouge");
        }
    }
}