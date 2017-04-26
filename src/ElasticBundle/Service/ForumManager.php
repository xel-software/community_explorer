<?php

namespace ElasticBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ForumManager
 * @package ElasticBundle\Service
 */
class ForumManager
{


    const FORUM_URL = 'https://talk.elasticexplorer.org';

    private $curlManager;

    public function __construct(CURLManager $curlManager)
    {

        $this->curlManager = $curlManager;

    }

    public function getLatestForumPost()
    {

        $latestPosts = $this->curlManager->getURL(self::FORUM_URL . '/latest.json', 60);

        if(!$latestPosts) {

            return false;

        }

        $readyTopic = [];

        $latestPosts = json_decode($latestPosts);

        $readyTopic['title'] = $latestPosts->topic_list->topics[0]->title;
        $readyTopic['url'] = self::FORUM_URL . '/t/' . $latestPosts->topic_list->topics[0]->slug . '/' . $latestPosts->topic_list->topics[0]->id . '/' . $latestPosts->topic_list->topics[0]->highest_post_number;

        return $readyTopic;

    }

}