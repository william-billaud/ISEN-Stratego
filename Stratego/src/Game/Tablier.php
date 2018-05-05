<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 05/05/18
 * Time: 11:11
 */

namespace App\Game;


class Tablier
{

    /**
     * @var array Cases
     */
    private $tabValeurs;

    /**
     * Tablier constructor.
     */
    public function __construct()
    {
        //Remplis le tableau de cases vides
        $this->tabValeurs=array_fill(0,10,array_fill(0,10,new CasesVide($this)));
        //Positionne les lacs
        for($i=2;$i<4;$i++)
        {
            for($j=4;$j<6;$j++){
                $this->tabValeurs[$i][$j]=new Lacs($this);
                $this->tabValeurs[$i+4][$j]=new Lacs($this);
            }
        }

        for($i=0;$i<10;$i++) {
            for ($j = 0; $j < 10; $j++) {
                /**
                 * @var Cases
                 */
                    $this->tabValeurs[$i][$j]->setPosition($i,$j);
            }
        }
    }

    /**
     * @param $x int
     * @param $y int
     * @return Cases
     */
    public function getTabValeurs(int $x,int $y): Cases
    {
        if($x>=0 && $x<10 && $y>=0 && $y<10)
        {
            return $this->tabValeurs[$x][$y];
        }else{
            throw new \InvalidArgumentException("les coordonnées doivent etres comprises entre 0 et 10");
        }

    }
}