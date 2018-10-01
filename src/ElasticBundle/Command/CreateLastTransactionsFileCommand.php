<?php
namespace ElasticBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ElasticBundle\Service\Elastic\ElasticManager;

/**
 * Class CreateTopBalanceAccountsFileCommand
 * @package ElasticBundle\Command
 */
class CreateLastTransactionsFileCommand extends ContainerAwareCommand
{

    const MAX_ACCOUNTS = 10000;

    protected function configure()
    {
        $this
            ->setName('elastic:createlasttransactionsfile')
            ->setDescription('Creating TOP balance accounts, puts in file.');;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $elasticManager = $container->get('elastic.manager.elastic');
        $filePath = $this->getContainer()->get('kernel')->getRootDir() . '/../web/share/lastTransactions.ser';

		$output->writeln('get blocks...');
		$blocksAfter = $elasticManager->getBlocks(0, 50000, true);
        $allTx = array();
        foreach ($blocksAfter['blocks'] as $block){
  		   	if($block['transactions'])
  		   	{
	        	$allTx = array_merge($allTx, $block['transactions']);
        	}
    	}

        if(file_exists($filePath)) {

            unlink($filePath);

        }

        file_put_contents($filePath, serialize($allTx));

        return true;

    }

}
