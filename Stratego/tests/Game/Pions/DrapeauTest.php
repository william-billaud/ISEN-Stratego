<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 07/05/18
 * Time: 14:51
 */

namespace App\Tests\Game\Pions;


use App\Game\Pions\Drapeau;
use App\Game\Pions\Sergent;
use App\Game\Tablier;
use PHPUnit\Framework\TestCase;

class DrapeauTest extends TestCase
{
    /**
     * @dataProvider MobiliteProvider
     * @expectedException \InvalidArgumentException
     * @param $x_o
     * @param $y_o
     * @param $x_a
     * @param $y_a
     */
    public function testMobilite($x_o,$y_o,$x_a,$y_a)
    {
        $tab=new Tablier();
        $mi=new Drapeau($tab,$x_o,$y_o);
        $mi->seDeplaceEn($x_a,$y_a);
    }

    public function MobiliteProvider(){
        return [
            [0,0,0,1],
            [0,0,1,0],
            [1,1,0,1],
            [0,0,0,1],
        ];
    }
    /**
     * @dataProvider MobiliteProvider
     * @expectedException \InvalidArgumentException
     * @param $x_o
     * @param $y_o
     * @param $x_a
     * @param $y_a
     */
    public function testMobiliteChangementPlace($x_o,$y_o,$x_a,$y_a)
    {
        $tab=new Tablier();
        $mi=new Drapeau($tab,$x_o,$y_o);
        $mi->changePlacePion($x_a,$y_a);
    }

    /**
     * @dataProvider MobiliteProvider
     * @expectedException \InvalidArgumentException
     * @param $x_o
     * @param $y_o
     * @param $x_a
     * @param $y_a
     */
    public function testMobiliteDistanceDeplacement($x_o,$y_o,$x_a,$y_a)
    {
        $tab=new Tablier();
        $mi=new Drapeau($tab,$x_o,$y_o);
        $mi->DistanceDeplacementEstValide($x_a,$y_a);
    }

    /**
     * @dataProvider MobiliteProvider
     * @expectedException \InvalidArgumentException
     * @param $x_o
     * @param $y_o
     * @param $x_a
     * @param $y_a
     */
    public function testAttaque($x_o,$y_o,$x_a,$y_a)
    {
        $tab=new Tablier();
        $se=new Sergent($tab,$x_a,$y_a);
        $mi=new Drapeau($tab,$x_o,$y_o);
        $mi->attaque($se);
    }

}