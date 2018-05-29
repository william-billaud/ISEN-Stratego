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


    public function changePlacePion($x, $y)
    {
        throw new \InvalidArgumentException("Les drapeau ne peuvent pas se déplacer");
    }

    public function attaque(Pions $pion)
    {
        throw new \InvalidArgumentException("Les drapeau ne peuvent pas attaquer");
    }


    public function libereCase()
    {
        $this->tablier->estFini=-$this->getProprietaire();
        parent::libereCase();
    }


}