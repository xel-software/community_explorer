<?php

namespace ElasticBundle\Service;

use Doctrine\Common\Cache\RedisCache;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CURLManager
 * @package ElasticBundle\Service
 */
class CURLManager
{

    private $cache;

    /**
     * @var int
     */
    private $cacheTTL;

    /**
     * @var string
     */
    private $cacheKeyPrefix = 'curl';

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(RedisCache $cache, $cacheTTL = 30)
    {

        $this->cache = $cache;
        $this->setDefaultCacheTTL($cacheTTL);

    }

    public function setDefaultCacheTTL($ttl)
    {

        if(is_numeric($ttl)) {

            $this->cacheTTL = (int) $ttl;

            return true;

        }

        return false;

    }

    /**
     * @param string $url
     * @param int|null $cacheTTL
     * @return bool|mixed
     * @throws \Exception
     */
    public function getURL($url, $cacheTTL = null)
    {

        if(!is_string($url)) {

            throw new \Exception('Invalid parameter.');

        }

        if(!is_null($cacheTTL) && !is_numeric($cacheTTL)) {

            throw new \Exception('Invalid parameter.');

        }

        $cacheKey = $this->cacheKeyPrefix . sha1($url);

        if($cacheTTL === null) {

            $cacheTTL = $this->cacheTTL;

        } else {

            $cacheTTL = (int) $cacheTTL;

        }

        if($this->cache->contains($cacheKey)) {

            return $this->cache->fetch($cacheKey);

        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.110 Safari/537.36');

        $output = curl_exec($ch);

        curl_close($ch);

        if($output) {

            if($cacheTTL >= 0) {

                $this->cache->save($cacheKey, $output, $cacheTTL);

            }

            return $output;

        }

        return false;

    }

}