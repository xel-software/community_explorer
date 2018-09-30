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

        $curlManager = $this->get('elastic.manager.curl');
        $response = $curlManager->getURL('https://bittrex.com/api/v1.1/public/getticker?market=btc-xel', -1, true);
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
