<?php

namespace ElasticBundle\Event\Listener;

use ElasticBundle\Entity\Request;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class RequestListener
{

    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {

            //$cookies = $event->getRequest()->cookies;
            return;

        }

        $route = $event->getRequest()->get('_route');

        if(strpos($route, '_wdt') === 0) {

            return;

        }

        $requestRepository = $this->em->getRepository('ElasticBundle:Request');

        $requestEntry = $requestRepository->findOneBy(['reqDate' => new \DateTime()]);

        if($requestEntry) {

            $requestEntry->setReqCount($requestEntry->getReqCount() + 1);
            $this->em->persist($requestEntry);

        } else {

            $requestEntry = new Request();
            $requestEntry->setReqCount(1);
            $requestEntry->setReqDate(new \DateTime());
            $this->em->persist($requestEntry);

        }

        $this->em->flush();

    }
}