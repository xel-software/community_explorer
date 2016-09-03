<?php

namespace ElasticBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ElasticBundle:Default:index.html.twig');
    }
}
