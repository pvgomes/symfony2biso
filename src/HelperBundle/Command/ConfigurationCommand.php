<?php

namespace HelperBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConfigurationCommand
 *
 */
class ConfigurationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('symfony2biso:configuration:load')
            ->setDescription('Load ExternalShop configurations to Redis')
            ->addOption(
                'data',
                'd',
                InputArgument::OPTIONAL,
                "Data: dev, test, staging, prod <comment>[default: dummy]</comment>")
            ->setHelp(<<<EOT
The <info>symfony2biso:configuration:load</info> command load ExternalShop configurations to Redis.

<comment>Load Dummy data</comment>:
<info>php app/console symfony2biso:configuration:load --data=dummy</info>

<comment>Load environment specific data</comment>:
<info>php app/console symfony2biso:configuration:load --data=dev</info>

EOT
            );
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $data = $input->getOption('data');

            if (!in_array($data, ['dummy', 'dev', 'test', 'staging', 'prod'])) {
                throw new \InvalidArgumentException('Invalid data environment name: '.$data.' (see help)');
            }

            $dataClassName = 'HelperBundle\Command\\' .ucfirst($data) . 'Data';

            if (!class_exists($dataClassName)) {
                throw new \InvalidArgumentException('Class not found: '.$dataClassName.' (see help)');
            }

            $dataClass = new $dataClassName();
            $redisData = $dataClass->getConfiguration();
            $redisClient = $this->getContainer()->get('redis.client');

            foreach($redisData as $key => $value) {
                if (is_array($value)) {
                    $redisData[$key] = json_encode($redisData[$key]);
                }
            }

            $redisClient->mset($redisData);

            $output->writeln(sprintf('<info>Configuration data load with success!</info>'));

        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>Could not load configuration data to Redis</error>'));
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }
    }
}
