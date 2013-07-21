<?php

namespace Plv\Command;

use Plv\Plv;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
		$iterator = Plv::findByLanguageFile();
		foreach ($iterator as $file) {
			$output->writeln(sprintf('<info>%s</info>', Plv::toLanguageName($file)));
		}
	}
}
