<?php

namespace ElasticBundle\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ContactController
 * @package ElasticBundle\Controller
 */
class ContactController extends ExtendController
{
    public function showAction(Request $request)
    {

        return $this->render('ElasticBundle:Contact:index.html.twig',[
            // ...
        ]);
    }
}
