<?php

namespace ElasticBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Request
 *
 * @ORM\Table(name="request", indexes={
 *     @ORM\Index(name="req_date", columns={"req_date"}),
 * })
 * @ORM\Entity(repositoryClass="ElasticBundle\Entity\RequestRepository")
 */
class Request
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(name="req_date", type="date")
     */
    private $reqDate;

    /**
     * @var int
     *
     * @ORM\Column(name="req_count", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $reqCount = 0;
    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set reqDate
     *
     * @param \DateTime $reqDate
     *
     * @return Request
     */
    public function setReqDate($reqDate)
    {
        $this->reqDate = $reqDate;

        return $this;
    }

    /**
     * Get reqDate
     *
     * @return \DateTime
     */
    public function getReqDate()
    {
        return $this->reqDate;
    }

    /**
     * Set reqCount
     *
     * @param integer $reqCount
     *
     * @return Request
     */
    public function setReqCount($reqCount)
    {
        $this->reqCount = $reqCount;

        return $this;
    }

    /**
     * Get reqCount
     *
     * @return integer
     */
    public function getReqCount()
    {
        return $this->reqCount;
    }
}
