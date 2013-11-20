<?php
/*
 * This file is part of the Plv package.
 *
 * (c) Isam Nagoya <nagodon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plv;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

/**
 * Plv phar compiler.
 *
 * @author Isam Nagoya <nagodon@gmail.com>
 */
class Compiler
{
	public function compile($pharFile = 'plv.phar')
	{
		if (file_exists($pharFile)) {
			unlink($pharFile);
		}

		$phar = new \Phar($pharFile, 0, $pharFile);
		$phar->setSignatureAlgorithm(\Phar::SHA1);

		$phar->startBuffering();

		$finder = new Finder();
		$finder->files()
			->ignoreVCS(true)
			->name('*.php')
			->notName('Compiler.php')
			->exclude('Tests')
			->in(__DIR__.'/')
			->in(__DIR__.'/../../vendor/composer/')
			->in(__DIR__.'/../../vendor/symfony/')
			->in(__DIR__.'/../../vendor/fabpot/goutte/')
			->in(__DIR__.'/../../vendor/guzzle/guzzle/src/')
		;

		foreach ($finder as $file) {
			$this->addFile($phar, $file);
		}

		$this->addAutoloader($phar);
		$this->addPlvBin($phar);
		$phar->setStub($this->getStub());
		$phar->stopBuffering();

		unset($phar);
	}

	private function addFile($phar, $file, $strip = true)
	{
		$path = str_replace(dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR, '', $file->getRealPath());

		$content = file_get_contents($file);
		if ($strip) {
			$content = $this->stripWhitespace($content);
		}

		$phar->addFromString($path, $content);
	}

	private function addAutoloader($phar)
	{
		$this->addFile($phar, new \SplFileInfo(__DIR__.'/../../vendor/autoload.php'));
	}

	private function addPlvBin($phar)
	{
		$content = file_get_contents(__DIR__.'/../../bin/plv');
		$content = preg_replace('{^#!/usr/bin/env php\s*}', '', $content);
		$phar->addFromString('bin/plv', $content);
	}

	private function stripWhitespace($source)
	{
		if (!function_exists('token_get_all')) {
			return $source;
		}

		$output = '';
		foreach (token_get_all($source) as $token) {
			if (is_string($token)) {
				$output .= $token;
			} elseif (in_array($token[0], array(T_COMMENT, T_DOC_COMMENT))) {
				$output .= str_repeat("\n", substr_count($token[1], "\n"));
			} elseif (T_WHITESPACE === $token[0]) {
				$whitespace = preg_replace('{[ \t]+}', ' ', $token[1]);
				$whitespace = preg_replace('{(?:\r\n|\r|\n)}', "\n", $whitespace);
				$whitespace = preg_replace('{\n +}', "\n", $whitespace);
				$output .= $whitespace;
			} else {
				$output .= $token[1];
			}
		}

		return $output;
	}

	private function getStub()
	{
		return <<<'EOF'
#!/usr/bin/env php
<?php

Phar::mapPhar('plv.phar');
require 'phar://plv.phar/bin/plv';

__HALT_COMPILER();

EOF;
	}
}

