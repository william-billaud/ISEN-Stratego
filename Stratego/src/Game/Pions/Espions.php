<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 07/05/18
 * Time: 09:50
 */

namespace App\Game\Pions;


class Espions extends Pions
{
    protected $name ="Espion";
    protected $value =1;

    public function attaque(Pions $pion)
    {
        if($pion instanceof Marechal)
        {
            $x=$pion->getX();
            $y=$pion->getY();
            $pion->libereCase();
            $this->changePlacePion($x,$y);
        }else{
            parent::attaque($pion);
        }
    }


}