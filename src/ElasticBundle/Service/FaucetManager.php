<?php

namespace ElasticBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use ElasticBundle\Service\Elastic\ElasticValidator;

/**
 * Class FaucetManager
 * @package ElasticBundle\Service
 */
class FaucetManager
{


    const AMOUNT_TO_PAY = 100000;

    /**
     * @var string
     */
    private $filepathTorIpAddresses;

    /**
     * @var string
     */
    private $filepathPaidIpAddresses;

    /**
     * @var string
     */
    private $filepathPaidXELAddresses;

    /**
     * @var ElasticValidator
     */
    private $elasticValidator;

    public function __construct(ContainerInterface $containerInterface)
    {

        $this->filepathPaidIpAddresses = $containerInterface->get('kernel')->getRootDir() . '/../src/ElasticBundle/Resources/faucet/paidIpAddresses.ser';
        $this->filepathTorIpAddresses = $containerInterface->get('kernel')->getRootDir() . '/../src/ElasticBundle/Resources/faucet/torIpAddresses.ser';
        $this->filepathPaidXELAddresses = $containerInterface->get('kernel')->getRootDir() . '/../src/ElasticBundle/Resources/faucet/paidXELAddresses.ser';

        $this->elasticValidator = new ElasticValidator();

    }

    /**
     * @return array
     */
    private function getTorIpAddresses()
    {

        if(!file_exists($this->filepathTorIpAddresses)) {

            $torIpAddresses = [];
            file_put_contents($this->filepathTorIpAddresses, serialize($torIpAddresses));

        }

        return unserialize(file_get_contents($this->filepathTorIpAddresses));

    }

    /**
     * @return array
     */
    private function getPaidIpAddresses()
    {

        if(!file_exists($this->filepathPaidIpAddresses)) {

            $paidIpAddresses = [];
            file_put_contents($this->filepathPaidIpAddresses, serialize($paidIpAddresses));

        }

        return unserialize(file_get_contents($this->filepathPaidIpAddresses));

    }

    /**
     * @return array
     */
    public function getPaidXELAddresses()
    {

        if(!file_exists($this->filepathPaidXELAddresses)) {

            $paidXELAddresses = [];
            file_put_contents($this->filepathPaidXELAddresses, serialize($paidXELAddresses));

        }

        return unserialize(file_get_contents($this->filepathPaidXELAddresses));

    }

    /**
     * @param $ipAddress
     * @return bool
     */
    public function setIpAddressAsPaid($ipAddress)
    {

        if(!$this->elasticValidator->validateIpAddress($ipAddress)) {

            return false;

        }

        $paidIpAddresses = $this->getPaidIpAddresses();
        $paidIpAddresses[] = $ipAddress;

        file_put_contents($this->filepathPaidIpAddresses, serialize($paidIpAddresses));

        return true;

    }

    /**
     * @param $address
     * @return bool
     */
    public function setXELAddressAsPaid($address)
    {

        if(!$this->elasticValidator->validateAddress($address)) {

            return false;

        }

        $paidXELAddresses = $this->getPaidXELAddresses();
        $paidXELAddresses[(new \DateTime)->format('Y-m-d H:i:s') . '|' . md5($address)] = $address;

        file_put_contents($this->filepathPaidXELAddresses, serialize($paidXELAddresses));

        return true;

    }

    /**
     * @param $address
     * @return bool
     */
    public function isXELAddressPaid($address)
    {

        if(!$this->elasticValidator->validateAddress($address)) {

            return true;

        }

        $paidAddresses = $this->getPaidXELAddresses();

        if(in_array($address, $paidAddresses)) {

            return true;

        }

        return false;

    }

    /**
     * @param $address
     * @return bool
     */
    public function isIpAddressPaid($address)
    {

        if(!$this->elasticValidator->validateIpAddress($address)) {

            return true;

        }

        $paidAddresses = $this->getPaidIpAddresses();

        if(in_array($address, $paidAddresses)) {

            return true;

        }

        return false;

    }

    /**
     * @param $address
     * @return bool
     */
    public function isTorIpAddress($address)
    {


        if(!$this->elasticValidator->validateIpAddress($address)) {

            return true;

        }

        $torAddresses = $this->getTorIpAddresses();

        if(in_array($address, $torAddresses)) {

            return true;

        }

        return false;

    }


}