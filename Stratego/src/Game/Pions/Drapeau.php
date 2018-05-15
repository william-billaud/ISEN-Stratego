<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 07/05/18
 * Time: 14:48
 */

namespace App\Game\Pions;


class Drapeau extends Pions
{
    protected $value=0;
    protected $name="Drapeau";

    public function seDeplaceEn($x, $y): bool
    {
        throw new \InvalidArgumentException("Les drapeau ne peuvent pas se deplacer");
    }

    public function changePlacePion($x, $y)
    {
        throw new \InvalidArgumentException("Les drapeau ne peuvent pas se déplacer");
    }

    public function attaque(Pions $pion)
    {
        throw new \InvalidArgumentException("Les drapeau ne peuvent pas attaquer");
    }

    public function DistanceDeplacementEstValide(int $x, int $y): bool
    {
        throw new \InvalidArgumentException("Les drapeau ne peuvent pas se deplacer");
    }

    public function libereCase()
    {
        parent::libereCase(); // TODO: Change the autogenerated stub
        //todo signifier au tableau que c'est fini, que le joueur à perdu
    }


}