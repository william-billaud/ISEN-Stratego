<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 05/05/18
 * Time: 14:32
 */

namespace App\Tests\Game;


use App\Game\CasesVide;
use App\Game\Lacs;
use App\Game\Tablier;
use PHPUnit\Framework\TestCase;

class TablierTest extends TestCase
{
    /**
     * @dataProvider invalideProvider
     * @expectedException \InvalidArgumentException
     */
    public function testInvalideArgument($x,$y)
    {
        $tab=new Tablier();
        $this->assertEmpty($tab->getTabValeurs($x,$y));

    }
    /**
     * @return array
     */
    public function invalideProvider()
    {
        return[
            [-1,-1],
            [-1,0],
            [0,-1],
            [10,10],
            [10,9],
            [9,10],
        ];
    }

    /**
     * @dataProvider valideCaseVideProvider
     */
    public function testValideArgumentCaseVide($x,$y){
        $tab=new Tablier();
        $this->assertEquals(new CasesVide($tab,$x,$y),$tab->getTabValeurs($x,$y));
    }

    public function valideCaseVideProvider()
    {
        return [
            [0,0],
            [9,0],
            [0,9],
            [9,9],
            [4,2],
            [5,3]
        ];
    }

    /**
     * @dataProvider lacsProvider
     */
    public function testCaseLacs($x,$y){
        $tab=new Tablier();
        $this->assertEquals(new Lacs($tab,$x,$y),$tab->getTabValeurs($x,$y));
    }

    public function lacsProvider()
    {
        return [
            [2,4],
            [2,5],
            [3,4],
            [3,5],
            [6,4],
            [6,5],
            [7,4],
            [7,5],
        ];
    }
}