<?php

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
					$filtered_replace_items[] = strtr($m[1], array('-' => ''));
				}
			}

			return $filtered_replace_items;
		};
	}

	public function getInstalledVersion()
	{
		return '2.0.0p198';
	}
}
