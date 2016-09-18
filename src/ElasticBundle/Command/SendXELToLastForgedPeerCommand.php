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
        $blocks             = $elasticManager->getBlocks(0,0);

        $output->writeln('Trying to send...');

        if(!$blocks) {

            $output->writeln("Can't find last block, exiting.");
            exit;

        }

        if(isset($blocks['blocks']) && is_array($blocks['blocks']) && count($blocks['blocks']) && isset($blocks['blocks'][0]['generatorRS'])) {

            $result = $elasticManager->sendMoney($blocks['blocks'][0]['generatorRS'], 1);

            if(!$result) {

                $output->writeln("Could not sent transaction to this account, exiting.");

            }

        } else {

            $output->writeln("Can't validate last block forged, exiting.");
            exit;

        }

        if(isset($blocks['blocks']) && is_array($blocks['blocks']) && count($blocks['blocks']) && isset($blocks['blocks'][1]['generatorRS'])) {

            if($blocks['blocks'][1]['height'] % 2 === 0) {

                $result = $elasticManager->sendMoney($blocks['blocks'][1]['generatorRS'], 1);

                if(!$result) {

                    $output->writeln("Could not sent transaction to this account, exiting.");

                }

            }

        } else {

            $output->writeln("Can't validate last block forged, exiting.");
            exit;

        }


        $output->writeln('DONE');

    }
}