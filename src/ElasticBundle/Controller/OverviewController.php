<?php

namespace ElasticBundle\Controller;
use ElasticBundle\Service\Elastic\ElasticManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class OverviewController
 * @package ElasticBundle\Controller
 */
class OverviewController extends ExtendController
{
    public function indexAction(Request $request)
    {

        $elasticManager = $this->get('elastic.manager.elastic');
        $blocks = $elasticManager->getBlocks(0, null, true);

        $nextBlockGenerators = $elasticManager->getNextBlockGenerators();
        $peer = $elasticManager->getPeer('54.159.100.251');
//        $time = $elasticManager->getTime();
//        $diff = (new \DateTime())->getTimestamp() - $time['time'];

        return $this->render('ElasticBundle:Overview:index.html.twig',[
            'blocks' => $blocks,
            'nextBlockGenerators' => $nextBlockGenerators
        ]);
    }

    public function searchAction(Request $request)
    {

        $elasticManager = $this->get('elastic.manager.elastic');

        if($request->isMethod('post')) {

            $input = $request->get('input');

            if($input === false || $input === null) {

                throw $this->createNotFoundException();

            }

            if(!preg_match('/^([a-zA-Z0-9-]{1,64})$/', $input)) {

                throw $this->createNotFoundException();

            }

            $inputType = $elasticManager->determineDataType($input);

            switch($inputType) {

                case ElasticManager::INPUT_DATA_TYPE_ADDRESS_RS: {

                    return $this->redirectToRoute('elastic_address',['address' => $input]);

                } break;
                case ElasticManager::INPUT_DATA_TYPE_BLOCK_HEIGHT: {

                    return $this->redirectToRoute('elastic_block',['block' => $input]);

                } break;
                case ElasticManager::INPUT_DATA_TYPE_TRANSACTION_FULL_HASH:
                case ElasticManager::INPUT_DATA_TYPE_TRANSACTION_ID: {

                    return $this->redirectToRoute('elastic_transaction',['transaction' => $input]);

                } break;
            }

        }


        throw $this->createNotFoundException();

    }
}
