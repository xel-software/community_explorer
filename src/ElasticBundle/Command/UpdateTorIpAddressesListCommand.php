<?php
namespace ElasticBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ElasticBundle\Service\Elastic\ElasticManager;

/**
 * Class UpdateTorIpAddressesListCommand
 * @package ElasticBundle\Command
 */
class UpdateTorIpAddressesListCommand extends ContainerAwareCommand
{


    protected function configure()
    {
        $this
            ->setName('elastic:updatetoripaddresseslist')
            ->setDescription('Updating TOR IP addresses list.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $container = $this->getContainer();
        $curlManager = $container->get('elastic.manager.curl');
        $filePath = $this->getContainer()->get('kernel')->getRootDir() . '/../src/ElasticBundle/Resources/faucet/torIpAddresses.ser';
        $torIpAddressesListUrl = 'https://check.torproject.org/exit-addresses';

        $memoryUsage = round(memory_get_usage(false)/1024/1024,2,PHP_ROUND_HALF_UP);

        $output->writeln("<info><comment>{$memoryUsage}</comment>MiB</info>");

        $torIpAddressesList = $curlManager->getURL($torIpAddressesListUrl, -1);

        if (!$torIpAddressesList) {

            $output->writeln('Couldn\'t get TOR IP addresses list. Exiting.');
            return false;

        }

        $output->writeln('List downloaded.');

        $memoryUsage = round(memory_get_usage(false)/1024/1024,2,PHP_ROUND_HALF_UP);

        $output->writeln("<info><comment>{$memoryUsage}</comment>MiB</info>");

        $torIpAddressesList = explode(PHP_EOL, $torIpAddressesList);

        $memoryUsage = round(memory_get_usage(false)/1024/1024,2,PHP_ROUND_HALF_UP);

        $output->writeln("<info><comment>{$memoryUsage}</comment>MiB</info>");

        $output->writeln('Trying to parse list...');

        foreach($torIpAddressesList as $key => &$entry) {

            if(strpos($entry,'ExitAddress') !== false) {

                if (preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $entry, $ip_match)) {
                    $entry = $ip_match[0];
                }

            } else {

                unset($torIpAddressesList[$key]);

            }

            $memoryUsage = round(memory_get_usage(false)/1024/1024,2,PHP_ROUND_HALF_UP);

            $output->writeln("<info><comment>{$memoryUsage}</comment>MiB</info>");

        }

        $output->writeln('List parsed. Saving.');

        if(file_exists($filePath)) {

            unlink($filePath);

        }

        file_put_contents($filePath, serialize($torIpAddressesList));

        return true;

    }

}