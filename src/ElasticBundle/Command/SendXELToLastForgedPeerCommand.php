<?php
namespace ElasticBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class SendXELToLastForgedPeerCommand
 * @package ElasticBundle\Command
 */
class SendXELToLastForgedPeerCommand extends ContainerAwareCommand
{

    const SEND_NORMAL_TRANSACTIONS = 30;
    const SEND_EXTRA_TRANSACTIONS = 30;
    const SEND_SLEEP_TIME = 0.2;

    protected function configure()
    {
        $this
            ->setName('elastic:sendxeltolastforged')
            ->setDescription('Sends XEL from main account to account that forged last block.');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $container          = $this->getContainer();
        $elasticManager     = $container->get('elastic.manager.elastic');
        $blocks             = $elasticManager->getBlocks(0,1,false,1);

        $output->writeln('Trying to send...');

        if(!$blocks) {

            $output->writeln("Can't find last block, exiting.");
            exit;

        }

        $firstSendRandom = mt_rand(1, self::SEND_NORMAL_TRANSACTIONS);
        $secondSendRandom = mt_rand(1, self::SEND_EXTRA_TRANSACTIONS);

        for($i = 0; $i < $firstSendRandom; $i++) {

            if(isset($blocks['blocks']) && is_array($blocks['blocks']) && count($blocks['blocks']) && isset($blocks['blocks'][0]['generatorRS'])) {

                usleep(self::SEND_SLEEP_TIME * 1000000);

                $result = $elasticManager->sendMoney($blocks['blocks'][0]['generatorRS'], 1);
                $output->writeln("XEL sent.");

                if(!$result) {

                    $output->writeln("Could not send transaction to this account, exiting.");

                }

            } else {

                $output->writeln("Can't validate last block forged, exiting.");
                exit;

            }

        }

        for($i = 0; $i < $secondSendRandom; $i++) {

            if(isset($blocks['blocks']) && is_array($blocks['blocks']) && count($blocks['blocks']) && isset($blocks['blocks'][1]['generatorRS'])) {

                if($blocks['blocks'][1]['height'] % 5 === 0) {

                    usleep(self::SEND_SLEEP_TIME * 1000000);

                    $result = $elasticManager->sendMoney($blocks['blocks'][1]['generatorRS'], 1);
                    $output->writeln("XEL sent.");

                    if(!$result) {

                        $output->writeln("Could not send transaction to this account, exiting.");

                    }

                }

            } else {

                $output->writeln("Can't validate last block forged, exiting.");
                exit;

            }

        }


        $output->writeln('DONE');

    }
}