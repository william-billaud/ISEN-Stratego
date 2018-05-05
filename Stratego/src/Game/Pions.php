<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 05/05/18
 * Time: 11:02
 */

namespace App\Game;


abstract class Pions extends Cases
{
    private $value;
    private $name;

    public abstract function SeDeplaceEn($x,$y);
    public abstract function Attaque($pion);
    public abstract function Meurt();

}