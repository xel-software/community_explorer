<?php

namespace ElasticBundle\Twig\Extension;

use ElasticBundle\Service\ForumManager;


/**
 * Class ForumExtension
 * @package   PeerboardBundle\Twig\Extension
 */
class ForumExtension extends \Twig_Extension
{

    /**
     * @var \ElasticBundle\Service\ForumManager
     */
    private $forumManager;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getLatestForumPost', [$this, 'getLatestForumPost'])
        ];
    }

    public function setForumManager(ForumManager $forumManager)
    {

        $this->forumManager = $forumManager;

    }

    public function getLatestForumPost()
    {

        $latestForumPost = $this->forumManager->getLatestForumPost();

        if(!$latestForumPost) {

            return 'N/A';

        }

        return $latestForumPost;

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ForumExtension';
    }
}
