<?php

namespace Plv\Versions;

use Plv\Versions\VersionsInterface;

class PhpVersions implements VersionsInterface
{
	private $url = 'http://php.net/';

	public function getName()
	{
		return 'PHP';
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function getFilterValue()
	{
		return array('a.download-link');
	}

	public function getCallback()
	{
		return function ($items) {
			$filtered_replace_items = array();
			foreach ($items as $item) {
				if (preg_match('/^([0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}(RC[0-9]{1,})?)/', $item, $m)) {
					$filtered_replace_items[] = $m[1];
				}
			}

			return $filtered_replace_items;
		};
	}

	public function getInstalledVersion()
	{
		return phpversion();
	}
}
