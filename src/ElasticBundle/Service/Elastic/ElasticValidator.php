<?php

namespace ElasticBundle\Service\Elastic;

/**
 * Class ElasticValidator
 * @package ElasticBundle\Service\Elastic
 */
class ElasticValidator
{

    public function __construct()
    {

    }


    public function validateAddress($address)
    {

        $address = strtoupper($address);

        if(!preg_match("/^(XEL)-([A-Z0-9]{4})-([A-Z0-9]{4})-([A-Z0-9]{4})-([A-Z0-9]{5})$/", $address)) {

            return false;

        }

        return true;

    }

    public function validateBlockHeight($height)
    {

        if(!is_numeric($height)) {

            return false;

        }

        $height = (int) $height;

        if(!preg_match("/^[0-9]{1,12}$/", $height)) {

            return false;

        }

        return true;

    }

    public function validateTransactionId($transactionId)
    {

        if(!is_numeric($transactionId)) {

            return false;

        }

        $transactionId = (int) $transactionId;

        if(!preg_match("/^[0-9]{13,23}$/", $transactionId)) {

            return false;

        }

        return true;

    }

    public function validateTransactionFullHash($transactionFullHash)
    {

        $transactionFullHash = trim($transactionFullHash);

        if(strlen($transactionFullHash) != 64) {

            return false;

        }

        if(!ctype_xdigit($transactionFullHash)) {

            return false;

        }

        return true;

    }

    public function validateIpAddress($ip)
    {

        return filter_var($ip, FILTER_VALIDATE_IP);

    }

}