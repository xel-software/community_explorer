<?php

namespace ElasticBundle\Twig\Extension;
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
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('translateTransactionNumericType', [$this, 'translateTransactionNumericType']),
            new \Twig_SimpleFunction('translateTimestampToHumanReadable', [$this, 'translateTimestampToHumanReadable']),
        ];
    }

    public function setElasticManager(ElasticManager $elasticManager) {

        $this->elasticManager = $elasticManager;

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
        $ago = \DateTime::createFromFormat('U',$timestamp);

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

    /**
     * @return string
     */
    public function getName()
    {
        return 'ElasticExtension';
    }
}
