<?php

namespace ElasticBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class GeoIpRepository
 * @package ElasticBundle\Entity
 */
class GeoIpRepository extends EntityRepository
{

    public function findCountryNameByIpAddress($ip)
    {

        $q = $this->createQueryBuilder('g')
            ->select('g.countryName')
            ->where('INET_ATON(:ip) BETWEEN g.ipStartNum AND g.ipEndNum')
            ->setParameter('ip', $ip)
            ->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true, 86400)
            ->setMaxResults(1)
            ->getOneOrNullResult();

        return $q;

    }

    public function findCountryCodeByIpAddress($ip)
    {

        $q = $this->createQueryBuilder('g')
            ->select('g.countryCode')
            ->where('INET_ATON(:ip) BETWEEN g.ipStartNum AND g.ipEndNum')
            ->setParameter('ip', $ip)
            ->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true, 86400)
            ->setMaxResults(1)
            ->getOneOrNullResult();

        return $q;

    }

}
