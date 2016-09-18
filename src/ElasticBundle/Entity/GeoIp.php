<?php

namespace ElasticBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Role
 *
 * @ORM\Table(name="GeoIp", indexes={
 *     @ORM\Index(name="ip_start_end_num", columns={"ip_start_num", "ip_end_num"}),
 *     @ORM\Index(name="ip_start_num", columns={"ip_start_num"}),
 *     @ORM\Index(name="ip_end_num", columns={"ip_end_num"}),
 * })
 * @ORM\Entity(repositoryClass="ElasticBundle\Entity\GeoIpRepository")
 */
class GeoIp
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
     * @var string
     *
     * @ORM\Column(name="country_code", type="string", length=2)
     */
    private $countryCode;

    /**
     * @var string
     *
     * @ORM\Column(name="country_name", type="string", length=50)
     */
    private $countryName;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_start", type="string", length=15)
     */
    private $ipStart;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_end", type="string", length=15)
     */
    private $ipEnd;

    /**
     * @var int
     * @ORM\Column(name="ip_start_num", type="bigint", options={"unsigned"=true})
     */
    private $ipStartNum;

    /**
     * @var int
     * @ORM\Column(name="ip_end_num", type="bigint", options={"unsigned"=true})
     */
    private $ipEndNum;



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
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return GeoIp
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set countryName
     *
     * @param string $countryName
     *
     * @return GeoIp
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;

        return $this;
    }

    /**
     * Get countryName
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * Set ipStart
     *
     * @param string $ipStart
     *
     * @return GeoIp
     */
    public function setIpStart($ipStart)
    {
        $this->ipStart = $ipStart;

        return $this;
    }

    /**
     * Get ipStart
     *
     * @return string
     */
    public function getIpStart()
    {
        return $this->ipStart;
    }

    /**
     * Set ipEnd
     *
     * @param string $ipEnd
     *
     * @return GeoIp
     */
    public function setIpEnd($ipEnd)
    {
        $this->ipEnd = $ipEnd;

        return $this;
    }

    /**
     * Get ipEnd
     *
     * @return string
     */
    public function getIpEnd()
    {
        return $this->ipEnd;
    }

    /**
     * Set ipStartNum
     *
     * @param integer $ipStartNum
     *
     * @return GeoIp
     */
    public function setIpStartNum($ipStartNum)
    {
        $this->ipStartNum = $ipStartNum;

        return $this;
    }

    /**
     * Get ipStartNum
     *
     * @return integer
     */
    public function getIpStartNum()
    {
        return $this->ipStartNum;
    }

    /**
     * Set ipEndNum
     *
     * @param integer $ipEndNum
     *
     * @return GeoIp
     */
    public function setIpEndNum($ipEndNum)
    {
        $this->ipEndNum = $ipEndNum;

        return $this;
    }

    /**
     * Get ipEndNum
     *
     * @return integer
     */
    public function getIpEndNum()
    {
        return $this->ipEndNum;
    }
}
