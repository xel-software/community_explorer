<?php

namespace ElasticBundle\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AboutController
 * @package ElasticBundle\Controller
 */
class AboutController extends AbstractBaseController
{
    public function showAction(Request $request)
    {

        $elasticManager = $this->get('elastic.manager.elastic');
        $blockchainStatus = $elasticManager->getBlockchainStatus();
        $state = $elasticManager->getState(true);
        $requestCount = $this->em->getRepository('ElasticBundle:Request')->findLast(1);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://bittrex.com/api/v1.1/public/getticker?market=btc-xel');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        $data = json_decode($response);
        $btc_price = number_format($data->result->Last, 8, '.', '');

        return $this->render('ElasticBundle:About:index.html.twig',[
            'blockchainStatus' => $blockchainStatus,
            'state' => $state,
            'btc_price' => $btc_price,
            'requestCount' => $requestCount
        ]);
    }
}
