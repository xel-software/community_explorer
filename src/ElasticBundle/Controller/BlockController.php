<?php

namespace ElasticBundle\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BlockController
 * @package ElasticBundle\Controller
 */
class BlockController extends AbstractBaseController
{
    public function showAction(Request $request, $block)
    {

        $block = (int) $block;

        $elasticManager = $this->get('elastic.manager.elastic');
        $blockInfo = $elasticManager->getBlockByHeight($block, true);

        if(!$blockInfo) {

            throw $this->createNotFoundException();

        }

        return $this->render('ElasticBundle:Block:index.html.twig',[
            'blockInfo' => $blockInfo,
        ]);
    }
}
