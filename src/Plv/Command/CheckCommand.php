<?php

/*
 * This file is part of the Plv package.
 *
 * (c) Isam Nagoya <nagodon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plv\Command;

use Plv\Plv;
use \ArrayIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CheckCommand displays the each programing language version.
 *
 * @author Isam Nagoya <nagodon@gmail.com>
 */
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
			$iterator = Plv::findByLanguageFile();
			foreach ($iterator as $file) {
				$classes[] = sprintf(VERSIONS_CLASS_NAME_FORMAT, ucfirst(Plv::toLanguageName($file)));
			}
		}

		$versions_collection = new ArrayIterator();
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
