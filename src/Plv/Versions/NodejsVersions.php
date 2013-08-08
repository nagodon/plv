<?php

namespace Plv\Versions;

use Plv\Versions\VersionsInterface;

class NodejsVersions implements VersionsInterface
{
	private $url = 'http://nodejs.org/';

	public function getName()
	{
		return 'node.js';
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function getFilterValue()
	{
		return array('div#intro > p');
	}

	public function getCallback()
	{
		return function ($items) {
			$filtered_replace_items = array();
			foreach ($items as $item) {
				if (preg_match('/Current Version: v([0-9]{1,}\.[0-9]{1,}\.[0-9]{1,})$/', $item, $m)) {
					$filtered_replace_items[] = $m[1];
				}
			}

			return $filtered_replace_items;
		};
	}

	public function getInstalledVersion()
	{
		$version = null;

		$version_str = exec('/usr/bin/env node --version 2>&1');
		if (preg_match('/^.*v([0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}).*$/', $version_str, $m)) {
			$version = $m[1];
		}

		return $version;
	}
}
