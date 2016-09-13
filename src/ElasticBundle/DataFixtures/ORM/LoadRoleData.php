<?php
namespace ElasticBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ElasticBundle\Entity\GeoIp;

/**
 * Class LoadRoleData
 * @package SystemBundle\DataFixtures\ORM
 */
class LoadRoleData implements FixtureInterface, OrderedFixtureInterface
{

    const GEO_IP_SOURCE_CSV_PATH = 'src/ElasticBundle/Resources/geoip/GeoIPCountryWhois.csv';

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {

        if ($path = realpath(self::GEO_IP_SOURCE_CSV_PATH)) {

            $count = 0;

            $handle = fopen($path, 'r');

            /*
             * [0] - start address ip
             * [1] - end address ip
             * [2] - start numeric address ip
             * [3] - end numeric address ip
             * [4] - country code
             * [5] - country name
             */

            while (($row = fgetcsv($handle, 10000, ',')) !== FALSE)
            {

                echo($row[0] . PHP_EOL);

                $geoIp = new GeoIp();
                $geoIp->setCountryCode($row[4]);
                $geoIp->setCountryName($row[5]);
                $geoIp->setIpStart($row[0]);
                $geoIp->setIpEnd($row[1]);
                $geoIp->setIpStartNum($row[2]);
                $geoIp->setIpEndNum($row[3]);

                $manager->persist($geoIp);

                ++$count;

                if($count % 100 == 0) {

                    $manager->flush();
                    $manager->clear();

                }
            }

        }

        $manager->flush();

    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 0;
    }
}