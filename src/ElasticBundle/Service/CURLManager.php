<?php

namespace PeerboardBundle\Service;

class CURLManager {

    public function __construct() {

    }

    /**
     * @param $url
     * @return bool|mixed
     */
    public function getURL($url) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.110 Safari/537.36');

        $output = curl_exec($ch);

        curl_close($ch);

        if($output) {
            return $output;
        }

        return false;

    }

}