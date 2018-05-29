<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 05/05/18
 * Time: 17:11
 */

namespace App\Tests\Game\Pions;



use App\Game\CasesVide;
use App\Game\Pions\Sergent;
use App\Game\Tablier;
use PHPUnit\Framework\TestCase;

class PionsTest extends TestCase
{

    /**
     * @dataProvider distanceValideProvider
     * @param $x_o
     * @param $y_o
     * @param $x_a
     * @param $y_a
     * @param $tab
     */
    public function testDistanceValide($x_o,$y_o,$x_a,$y_a,$tab){
        $se=new Sergent($tab,$x_o,$y_o);
        $this->assertTrue($se->DistanceDeplacementEstValide($x_a,$y_a));

    }

    public function distanceValideProvider()
    {
        $tab=new Tablier();
        return [
            [0,0,0,1,$tab],
            [0,0,1,0,$tab],
            [5,5,4,5,$tab],
            [5,5,5,4,$tab],
        ];
    }

    /**
     * @dataProvider distanceInvalideProvider
     * @param $x_o
     * @param $y_o
     * @param $x_a
     * @param $y_a
     * @param $tab
     */
    public function testDistanceInvalide($x_o,$y_o,$x_a,$y_a,$tab)
    {
        $se=new Sergent($tab,$x_o,$y_o);
        $this->assertFalse($se->DistanceDeplacementEstValide($x_a,$y_a));
    }

    /**
     * @dataProvider distanceInvalideProvider
     * @param $x_o
     * @param $y_o
     * @param $x_a
     * @param $y_a
     * @param $tab
     * @expectedException \InvalidArgumentException
     */
    public function testDistanceInvalideSeDeplace($x_o,$y_o,$x_a,$y_a,$tab)
    {
        $se=new Sergent($tab,$x_o,$y_o);
        $se->seDeplaceEn($x_a,$y_a);
    }

    public function distanceInvalideProvider()
    {
        $tab=new Tablier();
        return [
            //distance >1
            [0,0,0,2,$tab],
            [0,0,2,0,$tab],
            [5,5,3,5,$tab],
            [5,5,5,3,$tab],
            //deplacement en diagonale
            [5,5,4,4,$tab],
            [5,5,6,6,$tab],
            [5,5,6,4,$tab],
            [5,5,4,6,$tab],
            [5,5,5,3,$tab],
        ];
    }

    /**
     * @dataProvider liberationProvider
     * @param $x
     * @param $y
     */
    public function testLiberation($x,$y){
        $tab = new Tablier();
        $se=new Sergent($tab,$x,$y);
        $se->libereCase();
        $this->assertEquals(new CasesVide($tab,$x,$y),$tab->getTabValeurs($x,$y));
    }

    public function liberationProvider()
    {
        return [
            [0,0],
            [7,7],
            [3,3],
            [9,9],
        ];
    }

    /**
     * @dataProvider valideProprioProvider
     * @param $val
     * @param $tab
     */
    public function testValidePropio($val,$tab)
    {
        $se=new Sergent($tab,0,0);
        $se->setProprietaire($val);
        $this->addToAssertionCount(1);
    }

    public function valideProprioProvider(){
        $tab=new Tablier();
        return [
            [1,$tab],
            [-1,$tab],
        ];
    }


    /**
     * @dataProvider invalideProprioProvider
     * @expectedException \InvalidArgumentException
     * @param $val
     * @param $tab
     */
    public function testInvalidePropio($val,$tab)
    {
        $se=new Sergent($tab,0,0);
        $se->setProprietaire($val);
        $this->addToAssertionCount(1);
    }

    public function invalideProprioProvider(){
        $tab=new Tablier();
        return [
            [0,$tab],
            [-2,$tab],
            [2,$tab],
        ];
    }


    /**
     * @dataProvider deplacementValideProvider
     * @param $x_o
     * @param $y_o
     * @param $x_a
     * @param $y_a
     */
    public function testDeplacementBaseOK($x_o,$y_o,$x_a,$y_a){
        $tab=new Tablier();
        $se=new Sergent($tab,$x_o,$y_o);
        $se->setProprietaire(1);
        self::assertTrue($se->seDeplaceEn($x_a,$y_a));
    }

    public function deplacementValideProvider(){
        return [
            [0,0,0,1],
            [9,9,8,9],
            [9,8,9,9],
            [1,0,0,0]
        ];
    }

    /**
     * @dataProvider deplacementLacsProvider
     * @param $x_o
     * @param $y_o
     * @param $x_a
     * @param $y_a
     * @expectedException \InvalidArgumentException
     */
    public function testDeplacementLacs($x_o,$y_o,$x_a,$y_a){
        $tab=new Tablier();
        $se=new Sergent($tab,$x_o,$y_o);
        $se->setProprietaire(1);
        self::assertFalse($se->seDeplaceEn($x_a,$y_a));
    }

    public function deplacementLacsProvider(){
        return [
            [1,4,2,4],
            [2,6,2,5],
            [4,4,3,4],
            [4,5,3,5],
            [5,4,6,4],
            [5,5,6,5],
            [8,4,7,4],
            [8,5,7,5]];
    }
}