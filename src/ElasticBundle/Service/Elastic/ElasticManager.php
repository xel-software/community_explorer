<?php

namespace ElasticBundle\Service\Elastic;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

use ElasticBundle\Service\CURLManager;
/**
 * Class ElasticManager
 * @package ElasticBundle\Service\Elastic
 */
class ElasticManager
{

    const ELASTIC_NQT_DIVIDER = 100000000;

    const TRANSACTION_TYPE_PAYMENT = 0;
    const TRANSACTION_TYPE_MESSAGING = 1;
    const TRANSACTION_TYPE_ACCOUNT_CONTROL = 2;
    const TRANSACTION_TYPE_WORK_CONTROL = 3;

    const INPUT_DATA_TYPE_BLOCK_HEIGHT = 0;
    const INPUT_DATA_TYPE_TRANSACTION_ID = 1;
    const INPUT_DATA_TYPE_TRANSACTION_FULL_HASH = 2;
    const INPUT_DATA_TYPE_ADDRESS_RS = 3;

    // TODO delete offset when Elastic wallet will be fixed
    const ELASTIC_TIME_OFFSET = 1451610000;

    /**
     * @var string
     */
    private $daemonAddress;

    private $elasticValidator;

    /**
     * @var \ElasticBundle\Service\CURLManager
     */
    private $curlManager;

    /**
     * @param CURLManager $curlManager
     * @param string $daemonAddress
     * @param int $daemonPort
     */
    public function __construct(CURLManager $curlManager, $daemonAddress, $daemonPort)
    {

        $this->setElasticDaemonAddress($daemonAddress, $daemonPort);
        $this->curlManager = $curlManager;
        $this->elasticValidator = new ElasticValidator();

    }

    /**
     * @param string $address
     * @param int $port
     */
    private function setElasticDaemonAddress($address, $port = 7876)
    {

        $this->daemonAddress = 'http://' . $address . ':' . $port . '/nxt?requestType=';

    }

    /**
     * @param null $firstIndex
     * @param null $lastIndex
     * @param bool|false $includeTransactions
     * @return bool|mixed
     * @throws \Exception
     */
    public function getBlocks($firstIndex = null, $lastIndex = null, $includeTransactions = false)
    {

        $query = 'getBlocks';

        if($firstIndex) {
            // TODO
        }

        if($lastIndex) {
            // TODO
        }

        if($includeTransactions) {

            $query .= '&includeTransactions=true';

        }

        $result = $this->curlManager->getURL($this->daemonAddress . $query);

        if(!$result) {

            return false;

        }

        $blocks = json_decode($result, true);


        if(isset($blocks['errorCode'])) {

            return false;

        }

        if(!$blocks) {

            return false;

        }

        return $blocks;

    }

    /**
     * @param $type
     * @return string
     */
    public function translateTransactionNumericType($type)
    {

        if(is_numeric($type)) {

            $type = (int) $type;

        }

        if(!is_int($type)) {

            throw new Exception('Wrong parameter.');

        }

        switch($type) {

            case self::TRANSACTION_TYPE_PAYMENT: return 'Payment'; break;
            case self::TRANSACTION_TYPE_MESSAGING: return 'Message'; break;
            case self::TRANSACTION_TYPE_ACCOUNT_CONTROL: return 'Account'; break;
            case self::TRANSACTION_TYPE_WORK_CONTROL: return 'PoW'; break;
            default: return 'Unknown'; break;

        }

    }

    /**
     * @param $address
     * @return bool|mixed
     * @throws \Exception
     */
    public function getAccount($address)
    {

        if(!$this->elasticValidator->validateAddress($address)) {

            return false;

        }

        $query = 'getAccount&account=' . $address;

        $result = $this->curlManager->getURL($this->daemonAddress . $query);

        if(!$result) {

            return false;

        }

        $accountInfo = json_decode($result, true);

        if(!$accountInfo) {

            return false;

        }

        if(isset($accountInfo['errorCode'])) {

            return false;

        }

        return $accountInfo;

    }

    /**
     * @param $address
     * @param int $firstIndex
     * @param int $lastIndex
     * @return bool|mixed
     * @throws \Exception
     */
    public function getAccountTransactions($address, $firstIndex = 0, $lastIndex = 199)
    {

        if(!$this->elasticValidator->validateAddress($address)) {

            return false;

        }

        $query = 'getAccountTransactions&account=' . $address;

        $firstIndex = (int) $firstIndex;
        $lastIndex = (int) $lastIndex;

        if($firstIndex === 0 || $firstIndex > 0) {

            $query .= '&firstIndex=' . $firstIndex;

        }

        if($lastIndex > 0) {

            $query .= '&lastIndex=' . $lastIndex;

        }

        $result = $this->curlManager->getURL($this->daemonAddress . $query);

        if(!$result) {

            return false;

        }

        $accountTransactions = json_decode($result, true);

        if(!$accountTransactions) {

            return false;

        }

        if(isset($accountTransactions['errorCode'])) {

            return false;

        }

        return $accountTransactions;

    }


    public function getBlockByHeight($blockHeight, $includeTransactions = false)
    {

        if(!$this->elasticValidator->validateBlockHeight($blockHeight)) {

            return false;

        }

        $blockHeight = (int) $blockHeight;

        $query = 'getBlock&height=' . $blockHeight;

        $result = $this->curlManager->getURL($this->daemonAddress . $query);

        if(!$result) {

            return false;

        }

        $blockInfo = json_decode($result, true);

        if(!$blockInfo) {

            return false;

        }

        if(isset($blockInfo['errorCode'])) {

            return false;

        }

        if(isset($blockInfo['transactions']) && is_array($blockInfo['transactions']) && count($blockInfo['transactions'])) {

            foreach($blockInfo['transactions'] as $transactionKey => $transactionId) {

                $transactionInfo = $this->getTransactionById($transactionId);

                if($transactionInfo) {

                    $blockInfo['transactions'][$transactionKey] = $transactionInfo;

                } else {

                    unset($blockInfo['transactions'][$transactionKey]);

                }

            }

        }

        return $blockInfo;

    }

    /**
     * @param int $transactionId
     * @return bool|mixed
     * @throws \Exception
     */
    public function getTransactionById($transactionId)
    {

        if(!$this->elasticValidator->validateTransactionId($transactionId)) {

            return false;

        }

        $query = 'getTransaction&transaction=' . $transactionId;

        $result = $this->curlManager->getURL($this->daemonAddress . $query);

        if(!$result) {

            return false;

        }

        $transactionInfo = json_decode($result, true);

        if(!$transactionInfo) {

            return false;

        }

        if(isset($transactionInfo['errorCode'])) {

            return false;

        }

        return $transactionInfo;

    }

    /**
     * @param string $transactionFullHash
     * @return bool|mixed
     * @throws \Exception
     */
    public function getTransactionByFullHash($transactionFullHash)
    {

        if(!$this->elasticValidator->validateTransactionFullHash($transactionFullHash)) {

            return false;

        }

        $query = 'getTransaction&fullHash=' . $transactionFullHash;

        $result = $this->curlManager->getURL($this->daemonAddress . $query);

        if(!$result) {

            return false;

        }

        $transactionInfo = json_decode($result, true);

        if(!$transactionInfo) {

            return false;

        }

        if(isset($transactionInfo['errorCode'])) {

            return false;

        }

        return $transactionInfo;

    }

    /**
     * @param mixed $input
     * @return int
     */
    public function determineDataType($input)
    {

        if($this->elasticValidator->validateTransactionFullHash($input)) {

            return self::INPUT_DATA_TYPE_TRANSACTION_FULL_HASH;

        }

        if($this->elasticValidator->validateTransactionId($input)) {

            return self::INPUT_DATA_TYPE_TRANSACTION_ID;

        }

        if($this->elasticValidator->validateAddress($input)) {

            return self::INPUT_DATA_TYPE_ADDRESS_RS;

        }

        if($this->elasticValidator->validateBlockHeight($input)) {

            return self::INPUT_DATA_TYPE_BLOCK_HEIGHT;

        }

        return -1;

    }

}