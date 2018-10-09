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
class CreateTopBalanceAccountsFileCommand extends ContainerAwareCommand
{

    const MAX_ACCOUNTS = 10000;

    protected function configure()
    {
        $this
            ->setName('elastic:createtopbalanceaccountsfile')
            ->setDescription('Creating TOP balance accounts, puts in file.');;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $genesisAccount = ElasticManager::ELASTIC_GENESIS_ACCOUNT_RS;
        $container = $this->getContainer();
        $elasticManager = $container->get('elastic.manager.elastic');
        $genesisAccountInfo = $elasticManager->getAccount($genesisAccount);
        $accounts = [];
        $accountsToCheck = [];
        $accountsToCheck[$genesisAccount] = true;
        $filePath = $this->getContainer()->get('kernel')->getRootDir() . '/../web/share/topXelAccounts.ser';


        if (!$genesisAccountInfo) {

            $output->writeln('Couldn\'t get genesis account info. Exiting.');
            return false;

        }

        $output->writeln('Trying to create list...');

        $i = 0;
        while(count($accountsToCheck)) {

            if(count($accounts) > self::MAX_ACCOUNTS) {
                $output->writeln(self::MAX_ACCOUNTS . ' accounts reached');
                break;

            }

            foreach($accountsToCheck as $account => $accountDummy) {
                $i++;

                if(isset($accounts[$account])) {

                    unset($accountsToCheck[$account]);
                    continue;

                }

                $output->writeln('$i - Checking ' . $account);

                $accountInfo = $elasticManager->getAccount($account);

                if($accountInfo) {

                    $accounts[$account] = $accountInfo['balanceNQT'] / ElasticManager::ELASTIC_NQT_DIVIDER;

                }



                $output->writeln('Trying to get account transactions');

                $accountTransactions = $elasticManager->getBlockchainTransactions($account, 0, 10000000, ElasticManager::TRANSACTION_TYPE_PAYMENT);

                $output->writeln('Got account transactions, processing...');
                //$output->writeln('');

                if($accountTransactions && isset($accountTransactions['transactions']) && is_array($accountTransactions['transactions']) && count($accountTransactions['transactions'])) {

                    foreach($accountTransactions['transactions'] as $key => $transaction) {

                        if(isset($transaction['recipientRS'])) {

                            if(isset($accounts[$transaction['recipientRS']]) || isset($accountsToCheck[$transaction['recipientRS']])) {

                                unset($accountTransactions['transactions'][$key]);
                                continue;

                            } else {

                                $accountsToCheck[$transaction['recipientRS']] = true;

                            }

                            $memoryUsage = round(memory_get_usage(false)/1024/1024,2,PHP_ROUND_HALF_UP);

                            //$output->writeln("<info><comment>{$memoryUsage}</comment>MiB</info>");

                            unset($accountTransactions['transactions'][$key]);

                        }

                    }

                }

                unset($accountsToCheck[$account]);

            }

        }

        unset($accounts[ElasticManager::ELASTIC_GENESIS_ACCOUNT_RS]);

        uasort($accounts, function($a, $b) {
            return $b <=> $a;
        });

        if(file_exists($filePath)) {

            unlink($filePath);

        }

        file_put_contents($filePath, serialize($accounts));

        return true;

    }

}
