<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 05/05/18
 * Time: 11:01
 */

namespace App\Game;


class Lacs extends Cases
{
    public function __toString()
    {
        return $this->getX()."lacs".$this->getY();
    }


}