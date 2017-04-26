<?php

namespace ElasticBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class RequestRepository
 * @package ElasticBundle\Entity
 */
class RequestRepository extends EntityRepository
{

    public function findLast(int $count = 7)
    {

        return $this->createQueryBuilder('r')
            ->orderBy('r.reqDate', 'desc')
            ->setMaxResults($count)
            ->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true, 60)
            ->getResult();

    }

    public function getMaxFromLast( int $count = 7)
    {

        return $this->createQueryBuilder('r')
            ->select('MAX(r.reqCount)')
            ->orderBy('r.reqDate', 'desc')
            ->setMaxResults($count)
            ->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true, 60)
            ->getSingleScalarResult();

    }

}