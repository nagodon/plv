<?php

namespace Plv\Versions;

use Plv\Versions\VersionsInterface;

class PerlVersions implements VersionsInterface
{
	private $url = 'http://perl.org/';

	public function getName()
	{
		return 'Perl';
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function getFilterValue()
	{
		return 'div#short_lists > div.quick_links > div.list > p > a';
	}

	public function getCallback()
	{
		return function ($items) {
			return array(preg_replace('/^([0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}).*$/', '$1', $items[0]));
		};
	}

	public function getInstalledVersion()
	{
		$version = null;

		exec('/usr/bin/env perl --version 2>&1', $output);
		foreach ($output as $line) {
			if (preg_match('/^.*([0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}).*$/', $line, $m)) {
				$version = $m[1];
				break;
			}
		}

		return $version;
	}
}
