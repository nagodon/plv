<?php

namespace Plv\Versions;

use Plv\Versions\VersionsInterface;

class RubyVersions implements VersionsInterface
{
	private $url = 'http://www.ruby-lang.org/ja/downloads/';

	public function getName()
	{
		return 'Ruby';
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function getFilterValue()
	{
		return 'div#content > ul > li';
	}

	public function getCallback()
	{
		return function ($items) {
			$filtered_replace_items = array();
			foreach ($items as $item) {
				if (preg_match('/.*([0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}\-p[0-9]{1,}).*$/', $item, $m)) {
					$filtered_replace_items[] = $m[1];
				}
			}

			return $filtered_replace_items;
		};
	}

	public function getInstalledVersion()
	{
		$version = null;

		$version_str = exec('/usr/bin/env ruby --version 2>&1');
		if (preg_match('/^.*([0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}(p[0-9]{1,})?).*$/', $version_str, $m)) {
			$version = $m[1];
		}

		return $version;
	}
}
