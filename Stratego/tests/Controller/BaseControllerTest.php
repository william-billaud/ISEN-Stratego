<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 05/05/18
 * Time: 15:50
 */

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseControllerTest extends WebTestCase
{
    public function testAfficheTablier(){
        $client=static::createClient();
        $client->request('GET','/tab');
        $this->assertEquals(200,$client->getResponse()->getStatusCode());
    }
    public function testIndex(){
        $client=static::createClient();
        $client->request('GET','/index');
        $this->assertEquals(200,$client->getResponse()->getStatusCode());
    }
}