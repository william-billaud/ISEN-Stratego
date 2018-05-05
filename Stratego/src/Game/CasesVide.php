<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 05/05/18
 * Time: 11:02
 */

namespace App\Game;


class CasesVide extends Cases
{
    public function __toString()
    {
        return $this->getX()."vide".$this->getY();
    }


}