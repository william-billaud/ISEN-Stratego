<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 07/05/18
 * Time: 11:44
 */

namespace App\Tests\Game\Pions;


use App\Game\CasesVide;
use App\Game\Pions\Demineurs;
use App\Game\Pions\Mines;
use App\Game\Pions\Sergent;
use App\Game\Pions\Soldats;
use App\Game\Tablier;
use PHPUnit\Framework\TestCase;

class DemineursTest extends TestCase
{

    /**
     * @dataProvider attaqueProvider
     * @param $x_o
     * @param $y_o
     * @param $x_a
     * @param $y_a
     */
    public function testAttaqueMineOK($x_o,$y_o,$x_a,$y_a)
    {
        $tab=new Tablier();
        $mi=new Mines($tab,$x_a,$y_a);
        $mi->setProprietaire(1);
        $demineur=new Demineurs($tab,$x_o,$y_o);
        $demineur->setProprietaire(-1);
        $demineur->seDeplaceEn($x_a,$y_a);
        $this->assertEquals($demineur,$tab->getTabValeurs($x_a,$y_a));
        $this->assertTrue($tab->getTabValeurs($x_o,$y_o) instanceof CasesVide);
    }

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
    public function testAttaqueSergent($x_o,$y_o,$x_a,$y_a){
        $tab=new Tablier();
        $se=new Sergent($tab,$x_a,$y_a);
        $se->setProprietaire(1);
        $demineur=new Demineurs($tab,$x_o,$y_o);
        $demineur->setProprietaire(-1);
        $demineur->seDeplaceEn($x_a,$y_a);
        $this->assertEquals($se,$tab->getTabValeurs($x_a,$y_a));
        $this->assertTrue($tab->getTabValeurs($x_o,$y_o) instanceof CasesVide);
    }

    /**
     * @dataProvider attaqueProvider
     * @param $x_o
     * @param $y_o
     * @param $x_a
     * @param $y_a
     */
    public function testAttaqueDemineur($x_o,$y_o,$x_a,$y_a){
        $tab=new Tablier();
        $def=new Demineurs($tab,$x_a,$y_a);
        $demineur=new Demineurs($tab,$x_o,$y_o);
        $demineur->setProprietaire(1);
        $def->setProprietaire(-1);
        $this->assertTrue($demineur->seDeplaceEn($x_a,$y_a));
        $this->assertTrue($tab->getTabValeurs($x_a,$y_a) instanceof CasesVide);
        $this->assertTrue($tab->getTabValeurs($x_o,$y_o) instanceof CasesVide);
    }

    /**
     * @dataProvider attaqueProvider
     * @param $x_o
     * @param $y_o
     * @param $x_a
     * @param $y_a
     */
    public function testAttaqueSoldat($x_o,$y_o,$x_a,$y_a){
        $tab=new Tablier();
        $def=new Soldats($tab,$x_a,$y_a);
        $def->setProprietaire(1);
        $demineur=new Demineurs($tab,$x_o,$y_o);
        $demineur->setProprietaire(-1);
        $demineur->seDeplaceEn($x_a,$y_a);
        $this->assertEquals($demineur,$tab->getTabValeurs($x_a,$y_a));
        $this->assertTrue($tab->getTabValeurs($x_o,$y_o) instanceof CasesVide);
    }

}