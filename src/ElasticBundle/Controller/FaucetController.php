<?php

namespace ElasticBundle\Controller;
use ElasticBundle\Service\Elastic\ElasticManager;
use ElasticBundle\Service\FaucetManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FaucetController
 * @package ElasticBundle\Controller
 */
class FaucetController extends AbstractBaseController
{
    public function indexAction(Request $request)
    {
        $error = null;
        $success = null;
        $faucetManager = $this->get('elastic.manager.faucet');
        $elasticManager = $this->get('elastic.manager.elastic');

        if($request->isMethod('post')) {

            $address = $request->get('address');
            $valid = true;

            if ($address === false || $address === null) {

                throw $this->createNotFoundException();

            }

            if(!$elasticManager->getElasticValidator()->validateAddress($address)) {

                $error = 'Wrong XEL address. Check your address and try again.';
                $valid = false;

            } else {

                if($faucetManager->isIpAddressPaid($request->getClientIp())) {

                    $error = 'You already submitted your request. Please check your address.';
                    $valid = false;

                }

                if($faucetManager->isTorIpAddress($request->getClientIp())) {

                    $error = 'We don\'t support TOR network.';
                    $valid = false;

                }

                if($faucetManager->isXELAddressPaid($address)) {

                    $error = 'You already submitted your request. Please check your address.';
                    $valid = false;

                }

            }

            if($valid) {

                $elasticManager->sendMoney($address, FaucetManager::AMOUNT_TO_PAY);

                $faucetManager->setIpAddressAsPaid($request->getClientIp());
                $faucetManager->setXELAddressAsPaid($address);

                $success = 'Your payment on it\'s way! Check blochchain explorer or your client to make sure you received it. Happy XEL!';

            }


        }

        $paidXELAddresses = $faucetManager->getPaidXELAddresses();


        return $this->render('ElasticBundle:Faucet:index.html.twig',[
            'paidAddresses' => $paidXELAddresses,
            'error' => $error,
            'success' => $success,

        ]);
    }
}
