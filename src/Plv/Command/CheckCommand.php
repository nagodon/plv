<?php

namespace Plv\Command;

use Plv\Plv;
use Plv\Collection\VersionsCollection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class CheckCommand extends Command
{
	protected function configure()
	{
		$this
			->setName('check')
			->setDescription('Check programming language version')
			->setDefinition(array(
				new InputArgument('value', InputArgument::OPTIONAL),
			))
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$args = $input->getArguments();
		if ('' != $args['value']) {
			$language_name = $args['value'];
			$class_name = sprintf(VERSIONS_CLASS_NAME_FORMAT, ucfirst($language_name));

			if (class_exists($class_name)) {
				$classes[] = $class_name;
			} else {
				$output->writeln(sprintf("<error>Not found class %s</error>", $class_name));
				exit;
			}
		} else {
			$finder  = new Finder();
			$iterator = $finder->files()->name('*Versions.php')->in(ROOT_PATH . 'src/Plv/Versions');
			foreach ($iterator as $file) {
				$classes[] = sprintf(VERSIONS_CLASS_NAME_FORMAT, substr(basename($file->getRealpath()), 0, -12));
			}
		}

		$versions_collection = new VersionsCollection();
		foreach ($classes as $class) {
			$versions_collection->append(new $class);
		}

		$plv = new Plv($versions_collection);
		try {
			$plv->execute();
		} catch (Guzzle\Http\Exception\CurlException $ce) {
			$output->writeln("<error>Network error</error>");
		} catch (Exception $e) {
			$output->writeln("<error>Unknow error</error>");
		}
	}
}
