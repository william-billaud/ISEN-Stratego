<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 07/05/18
 * Time: 09:49
 */

namespace App\Game\Pions;


class Demineurs extends Pions
{
    protected $name ="Demineurs";
    protected $value =3;

    public function attaque(Pions $pion)
    {
        if($pion instanceof Mines)
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