<?php

namespace Plv\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class LanguageCommand extends Command
{
	protected function configure()
	{
		$this
			->setName('language')
			->setDescription('Display language list')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$finder  = new Finder();
		$iterator = $finder->files()->name('*Versions.php')->in(ROOT_PATH . 'src/Plv/Versions');
		foreach ($iterator as $file) {
			$output->writeln(sprintf('<info>%s</info>', strtolower(substr(basename($file->getRealpath()), 0, -12))));
		}
	}
}
