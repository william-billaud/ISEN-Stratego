<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 07/05/18
 * Time: 12:45
 */

namespace App\Tests\Game\Pions;


use App\Game\CasesVide;
use App\Game\Pions\Espions;
use App\Game\Pions\Marechal;
use App\Game\Pions\Sergent;
use App\Game\Tablier;
use PHPUnit\Framework\TestCase;

class EspionTest extends TestCase
{

    /**
     * @dataProvider attaqueProvider
     * @param $x_o
     * @param $y_o
     * @param $x_a
     * @param $y_a
     */
    public function testEspionAttaqueMarchale($x_o, $y_o, $x_a, $y_a)
    {
        $tab=new Tablier();
        $espion=new Espions($tab,$x_o,$y_o);
        $espion->setProprietaire(1);
        $marechal=new Marechal($tab,$x_a,$y_a);
        $marechal->setProprietaire(-1);
        $espion->seDeplaceEn($x_a,$y_a);
        $this->assertEquals($espion,$tab->getTabValeurs($x_a,$y_a));
        $this->assertTrue($tab->getTabValeurs($x_o,$y_o) instanceof CasesVide);
    }

    /**
     * @return array
     */
    public function attaqueProvider(){
        return [
            [0,0,0,1],
            [0,0,1,0],
            [1,1,0,1],
            [0,0,0,1],
        ];
    }

    /**
     * @dataProvider attaqueProvider
     * @param $x_o
     * @param $y_o
     * @param $x_a
     * @param $y_a
     */
    public function testMarechaleAttaqueEspion($x_o, $y_o, $x_a, $y_a)
    {
        $tab=new Tablier();
        $espion=new Espions($tab,$x_a,$y_a);
        $espion->setProprietaire(1);
        $marechal=new Marechal($tab,$x_o,$y_o);
        $marechal->setProprietaire(-1);
        $marechal->seDeplaceEn($x_a,$y_a);
        $this->assertEquals($marechal,$tab->getTabValeurs($x_a,$y_a));
        $this->assertTrue($tab->getTabValeurs($x_o,$y_o) instanceof CasesVide);
    }

    /**
     * @dataProvider attaqueProvider
     * @param $x_o
     * @param $y_o
     * @param $x_a
     * @param $y_a
     */
    public function testEspionAttqueSergent($x_o, $y_o, $x_a, $y_a){
        $tab=new Tablier();
        $espion=new Espions($tab,$x_o,$y_o);
        $espion->setProprietaire(1);
        $sergent=new Sergent($tab,$x_a,$y_a);
        $sergent->setProprietaire(-1);
        $espion->seDeplaceEn($x_a,$y_a);
        $this->assertEquals($sergent,$tab->getTabValeurs($x_a,$y_a));
        $this->assertTrue($tab->getTabValeurs($x_o,$y_o) instanceof CasesVide);
    }

}