<?php

namespace Amethyst\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class TestPhpunitCommand extends Command
{
    use Concerns\StartProcess;

    protected function configure()
    {
        $this
            ->setName('test:phpunit')
            ->setDescription('Test phpunit')
            ->addOption('dir', null, InputOption::VALUE_REQUIRED, 'Target directory', getcwd())
        ;
    }

    /**
     * Execute the command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = './vendor/bin/phpunit --coverage-html=./build/reports/phpunit --coverage-clover=build/logs/clover.xml --verbose --debug';
        $command = sprintf($command);

        return $this->startProcess($input, $output, Process::fromShellCommandline($command, $input->getOption('dir')));
    }
}
