<?php

namespace ElasticBundle\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AddressController
 * @package ElasticBundle\Controller
 */
class AddressController extends ExtendController
{
    public function showAction(Request $request, $address)
    {

        $address = strtoupper($address);

        $elasticManager = $this->get('elastic.manager.elastic');
        $accountInfo = $elasticManager->getAccount($address, true);
        $accountTransactions = $elasticManager->getBlockchainTransactions($address);
        $accountBlocks = $elasticManager->getAccountBlocks($address);

        if(!$accountInfo) {

            throw $this->createNotFoundException();

        }

        return $this->render('ElasticBundle:Account:index.html.twig',[
            'accountInfo' => $accountInfo,
            'accountBlocks' => $accountBlocks,
            'accountTransactions' => $accountTransactions
        ]);
    }
}
