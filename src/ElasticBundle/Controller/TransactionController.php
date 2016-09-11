<?php

namespace ElasticBundle\Controller;
use ElasticBundle\Service\Elastic\ElasticValidator;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TransactionController
 * @package ElasticBundle\Controller
 */
class TransactionController extends ExtendController
{
    public function showAction(Request $request, $transaction)
    {
        $elasticManager = $this->get('elastic.manager.elastic');
        $elasticValidator = new ElasticValidator();

        $isFullHash = $elasticValidator->validateTransactionFullHash($transaction);
        $isId = $elasticValidator->validateTransactionId($transaction);
        $transactionInfo = null;

        if($isFullHash) {

            $transactionInfo = $elasticManager->getTransactionByFullHash($transaction);

        }

        if($isId) {

            $transactionInfo = $elasticManager->getTransactionById($transaction);

        }


        if(!$transactionInfo) {

            throw $this->createNotFoundException();

        }

        return $this->render('ElasticBundle:Transaction:index.html.twig',[
            'transactionInfo' => $transactionInfo,
        ]);
    }
}
