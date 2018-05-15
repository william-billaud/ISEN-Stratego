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
use App\Game\Pions\Capitaine;
use App\Game\Pions\Colonels;
use App\Game\Pions\Demineurs;
use App\Game\Pions\Drapeau;
use App\Game\Pions\Espions;
use App\Game\Pions\General;
use App\Game\Pions\Lieutenants;
use App\Game\Pions\Lieutenants_Colonels;
use App\Game\Pions\Marechal;
use App\Game\Pions\Mines;
use App\Game\Pions\Sergent;
use App\Game\Pions\Soldats;
use App\Game\Tablier;
use PHPUnit\Framework\TestCase;

class TablierTest extends TestCase
{
    /**
     * @dataProvider invalideProvider
     * @expectedException \InvalidArgumentException
     * @param $x
     * @param $y
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
     * @param $x
     * @param $y
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
     * @param $x
     * @param $y
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


    /**
     * @dataProvider positionInvalideProvider
     * @expectedException \InvalidArgumentException
     * @param $x
     * @param $y
     * @param $tab
     */
    public function testInvalidePosition($x,$y,$tab){
        new Sergent($tab,$x,$y);
    }


    public function positionInvalideProvider()
    {
        $tab=new Tablier();
        return [
            [0,10,$tab],
            [0,-1,$tab],
            [-1,-1,$tab],
            [10,10,$tab],
            [-1,2,$tab],
            [10,2,$tab]
        ];
    }


    public function testTablierValide()
    {
        $tab=$this->creeTablierValide();
        $this->assertTrue($tab->verifieTablierValideDepart());
    }

    public function creeTablierValide()
    {
        $tab=new Tablier();
        //placement de 8 Soldats
        for($i=0;$i<8;$i++)
        {
            new Soldats($tab,$i,0,1);
            new Soldats($tab,$i,9,-1);
        }
        //placement de 4 Sergent/capitaine/lieutenenant + 4 démineurs
        for($i=0;$i<4;$i++)
        {
            new Sergent($tab,$i,1,1);
            new Sergent($tab,$i,8,-1);
            new Capitaine($tab,$i+4,1,1);
            new Capitaine($tab,$i+4,8,-1);
            new Lieutenants($tab,$i,2,1);
            new Lieutenants($tab,$i,7,-1);
            new Demineurs($tab,$i,3,1);
            new Demineurs($tab,$i,6,-1);
        }
        //Placement du dernier demineurs :
        new Demineurs($tab,4,3,1);
        new Demineurs($tab,4,6,-1);
        //Placement des 3 lieutenants_colonnel et 6 mines
        for($i=0;$i<3;$i++)
        {
            new Mines($tab,$i+4,2,1);
            new Mines($tab,$i+7,2,1);
            new Mines($tab,$i+4,7,-1);
            new Mines($tab,$i+7,7,-1);
            new Lieutenants_Colonels($tab,$i+5,3,1);
            new Lieutenants_Colonels($tab,$i+5,6,-1);
        }
        //1 Drapeau par equipes
        new Drapeau($tab,8,1,1);
        new Drapeau($tab,8,8,-1);
        //1 Espion par équipe
        new Espions($tab,9,1,1);
        new Espions($tab,9,8,-1);
        //1 General par equipe
        new General($tab,8,0,1);
        new General($tab,8,9,-1);
        //1 Marechal par équipe
        new Marechal($tab,9,0,1);
        new Marechal($tab,9,9,-1);
        //2 Colonels par équipe
        new Colonels($tab,8,3,1);
        new Colonels($tab,9,3,1);
        new Colonels($tab,8,6,-1);
        new Colonels($tab,9,6,-1);
        return $tab;
    }

    public function testInvalideTablierVide()
    {
        $tab=new Tablier();
        $this->assertFalse($tab->verifieTablierValideDepart());
    }

    /**
     * @dataProvider manquePionTablierProvider
     * @dataProvider pionMauvaisCoteProvider
     * @dataProvider tropSoldatProvider
     * @dataProvider soldatMilieuPlateauProvider
     * @param Tablier $tab
     */
    public function testInvalideTablier(Tablier $tab)
    {
        $this->assertFalse($tab->verifieTablierValideDepart());
    }


    public function manquePionTablierProvider(){
        $tabJ1=$this->creeTablierValide();
        new CasesVide($tabJ1,0,0);
        $tabJ2=$this->creeTablierValide();
        new CasesVide($tabJ2,9,9);
        return [
            [$tabJ1],
            [$tabJ2]
        ];
    }

    public function pionMauvaisCoteProvider()
    {
        $tabJ1=$this->creeTablierValide();
        new Soldats($tabJ1,0,0,-1);
        $tabJ2=$this->creeTablierValide();
        new Soldats($tabJ2,0,9,1);
        return [
            [$tabJ1],
            [$tabJ2]
        ];
    }

    public function tropSoldatProvider()
    {
        $tabJ1=$this->creeTablierValide();
        new Soldats($tabJ1,0,3,1);
        $tabJ2=$this->creeTablierValide();
        new Soldats($tabJ2,0,7,-1);
        return [
            [$tabJ1],
            [$tabJ2]
        ];
    }

    public function soldatMilieuPlateauProvider()
    {
        $tabJ1=$this->creeTablierValide();
        new Soldats($tabJ1,0,4);
        $tabJ2=$this->creeTablierValide();
        new Soldats($tabJ2,3,5);
        return [
            [$tabJ1],
            [$tabJ2]
        ];
    }
}

