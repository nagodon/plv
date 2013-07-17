<?php

use Plv\Versions\VersionsInterface;

class PythonVersions implements VersionsInterface
{
	private $url = 'http://www.python.org/download/';

	public function getName()
	{
		return 'Python';
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function getFilterValue()
	{
		return 'div#download-python > p > a';
	}

	public function getCallback()
	{
		return function ($items) {
			foreach ($items as $item) {
				if (preg_match('/^Python ([0-9]{1,}\.[0-9]{1,}\.[0-9]{1,})$/', $item, $m)) {
					$filtered_replace_items[] = $m[1];
				}
			}

			return $filtered_replace_items;
		};
	}

	public function getInstalledVersion()
	{
		return '2.7.1';
	}
}
