<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 28/05/18
 * Time: 11:52
 */

namespace App\Tests\Entity;


use App\Entity\Partie;
use App\Tests\Game\TablierTest;
use PHPUnit\Framework\TestCase;

class PartieTest extends TestCase
{


    public function testSerialization()
    {
        $tab=TablierTest::creeTablierValide();
        $base =new Partie();
        $base->setTablier($tab);
        $base->serializeTab();
        $copie=new Partie();
        $copie->setJsonTab($base->getJsonTab());
        $copie->unserializeTab();
        $copie->serializeTab();
        $this->assertEquals($base->getJsonTab(),$copie->getJsonTab());
    }
}