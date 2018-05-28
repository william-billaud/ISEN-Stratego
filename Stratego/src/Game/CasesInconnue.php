<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 28/05/18
 * Time: 14:22
 */

namespace App\Game;


class CasesInconnue extends Cases
{

    protected $name ="?";

    public function __construct(Tablier $tablier, $X, $Y,$proprio=0)
    {
        parent::__construct($tablier, $X, $Y);
        if($proprio!=0)
        {
            $this->setProprietaire($proprio);
        }
    }
}