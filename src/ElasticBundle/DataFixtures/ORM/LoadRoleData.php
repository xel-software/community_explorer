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

    const GEO_IP_SOURCE_CSV_PATH = 'src/ElasticBundle/Resources/geoip/dbip-country.csv';
    const GEO_IP_COUNTRY_DESC_CSV_PATH = 'src/ElasticBundle/Resources/geoip/GeoLite2-Country-Locations-en.csv';
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {

        // load country desc
        /*
         * geoname_id,	locale_code,	continent_code,	continent_name,	country_iso_code,	country_name
         */

        $countries = [];

        if ($path = realpath(self::GEO_IP_COUNTRY_DESC_CSV_PATH)) {

            $handle = fopen($path, 'r');

            $first = true;
            while (($row = fgetcsv($handle, 10000, ',')) !== FALSE)
            {

                if($first) {

                    $first = false;
                    continue;

                }

                echo($row[5] . PHP_EOL);

                $countries[$row[4]] = $row[5];
            }

        }

        // load ips
        /*
         * network,	geoname_id,	registered_country_geoname_id,	represented_country_geoname_id,	is_anonymous_proxy,	is_satellite_provider
         */

        if ($path = realpath(self::GEO_IP_SOURCE_CSV_PATH)) {

            $count = 0;

            $handle = fopen($path, 'r');

            while (($row = fgetcsv($handle, 10000, ',')) !== FALSE)
            {

                echo($row[0] . PHP_EOL);

                $geoIp = new GeoIp();
                $geoIp->setCountryCode($row[2]);
                $geoIp->setCountryName(isset($countries[$row[2]]) ? $countries[$row[2]] : 'N/A');
                $geoIp->setIpStart($row[0]);
                $geoIp->setIpEnd($row[1]);
                $geoIp->setIpStartNum(sprintf("%u", ip2long($row[0])));
                $geoIp->setIpEndNum(sprintf("%u", ip2long($row[1])));

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


    private function cidrToRange($cidr) {

        $range = array();
        $cidr = explode('/', $cidr);
        $range[0] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
        $range[1] = long2ip((ip2long($cidr[0])) + pow(2, (32 - (int)$cidr[1])) - 1);
        return $range;

    }

    private function cidrToIntRange($cidr) {

        $range = array();
        $cidr = explode('/', $cidr);
        $range[0] = sprintf("%u",(ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
        $range[1] = sprintf("%u",(ip2long($cidr[0])) + pow(2, (32 - (int)$cidr[1])) - 1);
        return $range;

    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 0;
    }
}