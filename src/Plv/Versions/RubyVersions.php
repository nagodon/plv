<?php

/*
 * This file is part of the Plv package.
 *
 * (c) Isam Nagoya <nagodon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plv\Versions;

use Plv\Versions\VersionsInterface;

/**
 * RubyVersions represents an ruby version.
 *
 * @author Isam Nagoya <nagodon@gmail.com>
 */
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
		return array('div#content > ul > li > p > a');
	}

	public function getCallback()
	{
		return function ($items) {
			$filtered_replace_items = array();
			foreach ($items as $item) {
				if (preg_match('/Ruby ([0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}(?:\-p[0-9]{1,})?)$/', $item, $m)) {
					$filtered_replace_items[] = strtr($m[1], array('-' => ''));
				}
			}

			return $filtered_replace_items;
		};
	}

	public function getInstalledVersion()
	{
		$version = null;

		$version_str = exec('/usr/bin/env ruby --version 2>&1');
		if (preg_match('/^ruby ([0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}(p[0-9]{1,})?).*$/', $version_str, $m)) {
			$version = $m[1];
		}

		return $version;
	}
}
