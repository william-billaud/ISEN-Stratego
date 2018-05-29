<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 07/05/18
 * Time: 11:21
 */

namespace App\Game\Pions;


class Mines extends Pions
{
    protected $value=11;
    protected $name="Mines";

    public function seDeplaceEn(?int $x,?int $y): bool
    {
        throw new \InvalidArgumentException("Les mines ne peuvent pas se deplacer");
    }

    public function changePlacePion($x, $y)
    {
        throw new \InvalidArgumentException("Les mines ne peuvent pas se déplacer");
    }

    public function attaque(Pions $pion)
    {
        throw new \InvalidArgumentException("Les mines ne peuvent pas attaquer");
    }

    public function DistanceDeplacementEstValide(int $x, int $y): bool
    {
        throw new \InvalidArgumentException("Les mines ne peuvent pas se deplacer");
    }


}