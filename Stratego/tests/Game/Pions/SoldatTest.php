<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 07/05/18
 * Time: 12:02
 */

namespace App\Tests\Game\Pions;


use App\Game\CasesVide;
use App\Game\Pions\Demineurs;
use App\Game\Pions\Soldats;
use App\Game\Tablier;
use PHPUnit\Framework\TestCase;

class SoldatTest extends TestCase
{

    /**
     * @dataProvider deplacementValideProvider
     * @param $x_o
     * @param $y_o
     * @param $x_a
     * @param $y_a
     */
   public function testDeplacementValideSoldat($x_o,$y_o,$x_a,$y_a)
    {
        $tab=new Tablier();
        $soldat=new Soldats($tab,$x_o,$y_o);
        $this->assertTrue($soldat->seDeplaceEn($x_a,$y_a));
        $this->assertEquals($soldat,$tab->getTabValeurs($x_a,$y_a));
        $this->assertTrue($tab->getTabValeurs($x_o,$y_o) instanceof CasesVide);
    }
    /**
     * @dataProvider deplacementValideProvider
     * @param $x_o
     * @param $y_o
     * @param $x_a
     * @param $y_a
     */
   public function testDistanceDeplacement($x_o,$y_o,$x_a,$y_a)
   {
       $tab=new Tablier();
       $soldat=new Soldats($tab,$x_o,$y_o);
       $this->assertTrue($soldat->DistanceDeplacementEstValide($x_a,$y_a));
   }

    public function deplacementValideProvider()
    {
        return [
            [0,0,9,0],
            [0,0,1,0],
            [9,9,7,9],
            [9,9,9,0],
            [9,9,0,9]
        ];
    }

    /**
     * @dataProvider deplacementInvalideProvide
     * @param $x_o
     * @param $y_o
     * @param $x_a
     * @param $y_a
     */
    public function testDeplacementInvalideSoldat($x_o,$y_o,$x_a,$y_a)
    {
        $tab=new Tablier();
        $soldat=new Soldats($tab,$x_o,$y_o);
        $this->assertFalse($soldat->seDeplaceEn($x_a,$y_a));
        $this->assertEquals($soldat,$tab->getTabValeurs($x_o,$y_o));
        $this->assertTrue($tab->getTabValeurs($x_a,$y_a) instanceof CasesVide);
    }

    public function deplacementInvalideProvide()
    {
        return [
            //par dessus les lacs
            [2,3,2,6],
            [0,4,4,4],
            [7,2,7,8],
            [7,8,7,2],
            //diagonale
            [0,0,1,1],
            [0,0,7,8],
        ];
    }
    /**
     * @dataProvider deplacementValideProvider
     * @param $x_o
     * @param $y_o
     * @param $x_a
     * @param $y_a
     */
    public function testDeplacementPionsAllie($x_o,$y_o,$x_a,$y_a){
        $tab=new Tablier();
        $soldat=new Soldats($tab,$x_o,$y_o);
        $soldat->setProprietaire(1);
        $demineur=new Demineurs($tab,$x_a,$y_a);
        $demineur->setProprietaire(1);
        $this->assertFalse($soldat->seDeplaceEn($x_a,$y_a));
        $this->assertEquals($demineur,$tab->getTabValeurs($x_a,$y_a));
        $this->assertEquals($soldat,$tab->getTabValeurs($x_o,$y_o));
    }
    /**
     * @dataProvider deplacementValideProvider
     * @param $x_o
     * @param $y_o
     * @param $x_a
     * @param $y_a
     */
    public function testDeplacementPionsEnnemie($x_o,$y_o,$x_a,$y_a){
        $tab=new Tablier();
        $soldat=new Soldats($tab,$x_o,$y_o);
        $soldat->setProprietaire(1);
        $demineur=new Demineurs($tab,$x_a,$y_a);
        $demineur->setProprietaire(-1);
        $this->assertTrue($soldat->seDeplaceEn($x_a,$y_a));
        $this->assertEquals($demineur,$tab->getTabValeurs($x_a,$y_a));
        $this->assertTrue($tab->getTabValeurs($x_o,$y_o) instanceof CasesVide);
    }
}