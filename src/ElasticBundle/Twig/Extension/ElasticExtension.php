<?php

namespace ElasticBundle\Twig\Extension;
use Doctrine\ORM\EntityManager;
use ElasticBundle\Service\Elastic\ElasticManager;


/**
 * Class ElasticExtension
 * @package   PeerboardBundle\Twig\Extension
 */
class ElasticExtension extends \Twig_Extension
{

    /**
     * @var \ElasticBundle\Service\Elastic\ElasticManager
     */
    private $elasticManager;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('translateTransactionNumericType', [$this, 'translateTransactionNumericType']),
            new \Twig_SimpleFunction('translateTimestampToHumanReadable', [$this, 'translateTimestampToHumanReadable']),
            new \Twig_SimpleFunction('translateIpAddressToCountryName', [$this, 'translateIpAddressToCountryName']),
            new \Twig_SimpleFunction('translateIpAddressToCountryCode', [$this, 'translateIpAddressToCountryCode']),
            new \Twig_SimpleFunction('getCountryCodeCount', [$this, 'getCountryCodeCount']),


        ];
    }

    public function setElasticManager(ElasticManager $elasticManager)
    {

        $this->elasticManager = $elasticManager;

    }

    public function setEntityManager(EntityManager $entityManager)
    {

        $this->em = $entityManager;

    }

    public function translateTransactionNumericType($type)
    {

        return $this->elasticManager->translateTransactionNumericType($type);

    }

    public function translateTimestampToHumanReadable($timestamp, $full = true)
    {

        // TODO delete offset when Elastic wallet will be fixed

        $timestamp += ElasticManager::ELASTIC_TIME_OFFSET;

        $now = new \DateTime();
        $ago = \DateTime::createFromFormat('U', $timestamp);

        /** @var \DateTime $diff */
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';


    }

    public function translateIpAddressToCountryName($ip)
    {

        if(!$this->elasticManager->getElasticValidator()->validateIpAddress($ip)) {

            return 'N/A';

        }

        $geoIpRepo = $this->em->getRepository('ElasticBundle:GeoIp');

        $countryName = $geoIpRepo->findCountryNameByIpAddress($ip);

        if($countryName) {

            return $countryName;

        }

        return 'N/A';


    }

    public function translateIpAddressToCountryCode($ip)
    {

        if(!$this->elasticManager->getElasticValidator()->validateIpAddress($ip)) {

            return 'N/A';

        }

        $geoIpRepo = $this->em->getRepository('ElasticBundle:GeoIp');

        $countryCode = $geoIpRepo->findCountryCodeByIpAddress($ip);

        if($countryCode) {

            return $countryCode;

        }

        return 'N/A';

    }

    public function getCountryCodeCount($peers)
    {

        if(!$peers) {

            return false;

        }

        $readyArr = [];

        foreach($peers as $peer) {

            $peerCountryCode = $this->translateIpAddressToCountryCode($peer['address']);

            if($peerCountryCode && $peerCountryCode !== 'N/A') {

                if(isset($readyArr[$peerCountryCode['countryCode']])) {

                    ++$readyArr[$peerCountryCode['countryCode']];

                } else {

                    $readyArr[$peerCountryCode['countryCode']] = 1;

                }

            }

        }

        unset($readyArr['N/A']);

        return $readyArr;

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ElasticExtension';
    }
}
