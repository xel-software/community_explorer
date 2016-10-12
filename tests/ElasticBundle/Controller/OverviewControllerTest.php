<?php

namespace ElasticBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class OverviewControllerTest
 * @package ElasticBundle\Tests\Controller
 */
class OverviewControllerTest extends WebTestCase
{
    public function testIndex()
    {

        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Latest blocks")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Latest transactions")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Next block generators")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Top XEL accounts")')->count());

        $crawler = $client->click($crawler->selectLink('About')->link());
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Available Supply")')->count());
        $this->assertEquals('/about/', $client->getRequest()->getRequestUri());

    }

    // can't test search action because without Elastic XEL service search will never return any results, tests will always fail
}
