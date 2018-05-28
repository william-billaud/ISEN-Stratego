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
        for($i=0;$i<10;$i++) {
            for ($j = 0; $j < 10; $j++) {
                $this->tabValeurs[$i][$j]=new CasesVide($this,$i,$j);
            }
        }
        //Positionne les lacs
        for($i=2;$i<=3;$i++)
        {
            for($j=4;$j<=5;$j++){
                $this->tabValeurs[$i][$j]=new Lacs($this,$i,$j);
                $this->tabValeurs[$i+4][$j]=new Lacs($this,$i+4,$j);
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

    public function setTabValeurs(int $x,int $y,Cases $n_Cases):bool {
        if($x>=0 && $x<10 && $y>=0 && $y<10)
        {
            $this->tabValeurs[$x][$y]=$n_Cases;
        }else {
            throw new \InvalidArgumentException("les coordonnées doivent etres comprises entre 0 et 10");
        }
        return true;
    }

    public function verifieTablierValideDepart()
    {
        //on verifie que chaque joueur à positionné le bon nombre de chaque type
        if(!$this->verifieOccurenceTypeCases(0,3) || !$this->verifieOccurenceTypeCases(6,9))
        {
            return false;
        }
        //On verifie que chaque case est occupée par le bon proprietaire
        if(!$this->verifieProprietaireRangee(0,3,1) || !$this->verifieProprietaireRangee(6,9,-1) || !$this->verifieProprietaireRangee(4,5,0)){
            return false;
        }
        //On verifie qu'il n'y à que des lacs et des case vides sur les cases du milieu
        for($i=4;$i<=5;$i++){
            for($j=0;$j<10;$j++) {

                $case=$this->getTabValeurs($j,$i);
                if(!($case instanceof CasesVide) && !($case instanceof Lacs))
                {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * verifie que toutes les cases des rangée y_o à y-o appartiennent au joueur $value
     * @param int $y_o ordonnée de depart
     * @param int $y_a ordonnée d'arrive
     * @param int $value valeur de propriétaire attendu
     * @return bool
     */
    private function verifieProprietaireRangee(int $y_o,int $y_a,int $value):bool
    {
        for($i=$y_o;$i<=$y_a;$i++){
            for($j=0;$j<10;$j++)
            {
                if($this->getTabValeurs($j,$i)->getProprietaire()!=$value)
                {
                    return false;
                }
            }
        }
        return true;
    }

    private function verifieOccurenceTypeCases(int $y_o,int $y_a)
    {
        $res=[
            0=>1,
            11=>6,
            1=>1,
            2=>8,
            3=>5,
            4=>4,
            5=>4,
            6=>4,
            7=>3,
            8=>2,
            9=>1,
            10=>1
        ];
        for ($i = $y_o; $i <= $y_a; $i++) {
            for ($j = 0; $j < 10; $j++) {
                $index=$this->getTabValeurs($j,$i)->getValue();
                if($index>=0 && $index<12)
                {
                    $res[$index]=$res[$index]-1;
                }else{
                    return false;
                }

            }
        }
        foreach ($res as $k=>$v )
        {
            if($v!=0){
                return false;
            }
        }
        return true;
    }

    public function getTab()
    {
        return $this->tabValeurs;
    }
}