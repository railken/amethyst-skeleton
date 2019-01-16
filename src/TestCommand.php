<?php

namespace Railken\Amethyst\Cli;

use GuzzleHttp\Client;
use Laravel\Installer\Console\NewCommand as Command;
use Railken\Dotenv\Dotenv;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Process\Process;
use ZipArchive;

class TestCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('test')
            ->setDescription('Test')
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

    	$targets = ['app', 'tests', 'src'];

    	foreach ($targets as $target) {

	    	if (file_exists($input->getOption('dir')."/".$target)) {
	    		$command = sprintf(
	    			'%s/vendor/bin/phpstan analyze %s --level=max -c %s', 
	    			__DIR__."/..", 
	    			$target,
	    			 __DIR__."/../resources/phpstan.neon"
	    		);

	    		$output->writeln(sprintf("<info>phpstan fix %s</info>", $target));
	    		$process = Process::fromShellCommandline($command, $input->getOption('dir'));
		        $process->run(null);
	    	}
    	}

    	$targets = ['app', 'tests', 'src', 'database', 'config'];

    	foreach ($targets as $target) {

	    	if (file_exists($input->getOption('dir')."/".$target)) {
	    		$command = sprintf(
	    			'%s/vendor/php-cs-fixer fix %s --allow-risky="yes" --config=%s', 
	    			__DIR__."/..", 
	    			$target, 
	    			__DIR__."/../resources/.php_cs.dist"
	    		);
	    		$output->writeln(sprintf("<info>php-cs-fixer fix %s</info>", $target));
	    		$process = Process::fromShellCommandline($command, $input->getOption('dir'));
		        $process->run(null);
	    	}
    	}

    	$command = './vendor/bin/phpunit --coverage-html=./build/reports/phpunit --coverage-clover=build/logs/clover.xml --verbose --debug';
	    $output->writeln("<info>phpunit</info>");
		$command = sprintf($command);
		$process = Process::fromShellCommandline($command, $input->getOption('dir'));
        $process->run(null);

    }

   
}
