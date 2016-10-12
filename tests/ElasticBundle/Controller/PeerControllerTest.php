<?php

namespace ElasticBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class PeerControllerTest
 * @package ElasticBundle\Tests\Controller
 */
class PeerControllerTest extends WebTestCase
{
    public function testIndex()
    {

        $client = static::createClient();

        $crawler = $client->request('GET', '/faucet/');

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Submit your XEL address")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("List of payout")')->count());

    }

}
