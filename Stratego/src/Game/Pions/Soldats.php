<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 07/05/18
 * Time: 09:49
 */

namespace App\Game\Pions;


use App\Game\CasesVide;

class Soldats extends Pions
{
    protected $name ="Soldat";
    protected $value =2;

    public function DistanceDeplacementEstValide(int $x, int $y): bool
    {
        if($this->getX()==$x || $this->getY()==$y)
        {
            return true;
        }else{
            return false;
        }
    }

    public function seDeplaceEn($x, $y):bool {
        if(!$this->DistanceDeplacementEstValide($x,$y)){
            return false;
        }
        for($i=min($this->getX(),$x)+1;$i<max($this->getX(),$x);$i++)
        {
            if(!($this->tablier->getTabValeurs($i,$this->getY())) instanceof CasesVide )
            {
                return false;
            }
        }

        for($i=min($this->getY(),$y)+1;$i<max($y,$this->getY());$i++)
        {
            if(!($this->tablier->getTabValeurs($this->getX(),$i)) instanceof CasesVide )
            {
                return false;
            }
        }

        $cible=$this->tablier->getTabValeurs($x,$y);
        //Joueur Rouge =-1, joueur Bleu =1
        if($cible instanceof Pions && $cible->proprietaire==-$this->proprietaire){
            $this->attaque($cible);
            return true;
        }elseif ($this->tablier->getTabValeurs($x,$y) instanceof CasesVide)
        {
            $this->changePlacePion($x,$y);
            return true;
        }
        return false;
    }


}