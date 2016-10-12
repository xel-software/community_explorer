<?php

namespace ElasticBundle\Tests\Entity;

/**
 * Class GeoIpTest
 * @package ElasticBundle\Tests\Entity
 */
class GeoIpTest extends \PHPUnit_Framework_TestCase
{
    public function testIpStart()
    {

        $geoIp = $this->getGeoIp();

        $this->assertNull($geoIp->getIpStart());

        $geoIp->setIpStart('127.0.0.1');
        $this->assertEquals('127.0.0.1', $geoIp->getIpStart());

    }

    public function testIpEnd()
    {

        $geoIp = $this->getGeoIp();

        $this->assertNull($geoIp->getIpEnd());

        $geoIp->setIpEnd('127.0.0.1');
        $this->assertEquals('127.0.0.1', $geoIp->getIpEnd());

    }

    public function testCountryCode()
    {

        $geoIp = $this->getGeoIp();

        $this->assertNull($geoIp->getCountryCode());

        $geoIp->setCountryCode('PL');
        $this->assertEquals('PL', $geoIp->getCountryCode());

    }

    public function testCountryName()
    {

        $geoIp = $this->getGeoIp();

        $this->assertNull($geoIp->getCountryName());

        $geoIp->setCountryName('Poland');
        $this->assertEquals('Poland', $geoIp->getCountryName());

    }

    public function testIpStartNum()
    {

        $geoIp = $this->getGeoIp();

        $this->assertNull($geoIp->getIpStartNum());

        $geoIp->setIpStartNum(3758096384);
        $this->assertEquals(3758096384, $geoIp->getIpStartNum());

    }

    public function testIpEndNum()
    {

        $geoIp = $this->getGeoIp();

        $this->assertNull($geoIp->getIpEndNum());

        $geoIp->setIpEndNum(4294967295);
        $this->assertEquals(4294967295, $geoIp->getIpEndNum());

    }

    /**
     * @return \ElasticBundle\Entity\GeoIp
     */
    protected function getGeoIp()
    {
        return $this->getMockForAbstractClass('ElasticBundle\Entity\GeoIp');
    }

}