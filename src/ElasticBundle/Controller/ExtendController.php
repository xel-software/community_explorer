<?php

namespace ElasticBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Bridge\Monolog\Logger;
use Doctrine\Common\Cache\FilesystemCache;

/**
 * Class ExtendController
 * @package ElasticBundle\Controller
 */
abstract class ExtendController extends Controller
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var FlashBagInterface
     */
    protected $flash;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var FilesystemCache
     */

    protected $cache;

    /**
     * @{inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container  = $container;
        $this->em         = $container->get('doctrine.orm.default_entity_manager');
        $this->session    = $container->get('session');
        $this->flash      = $container->get('session')->getFlashBag();
        $this->logger     = $container->get('logger');
        $this->cache      = $container->get('cache');
    }
}