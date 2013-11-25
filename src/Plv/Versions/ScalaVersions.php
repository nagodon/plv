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
 * ScalaVersions represents an scala version.
 *
 * @author Isam Nagoya <nagodon@gmail.com>
 */
class ScalaVersions implements VersionsInterface
{
	private $url = 'http://www.scala-lang.org/downloads';

	public function getName()
	{
		return 'Scala';
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function getFilterValue()
	{
		return array(
			'div.main-page-column > p',
			'div.main-page-column > ul > li'
		);
	}

	public function getCallback()
	{
		return function ($items) {
			$filtered_replace_items = array();
			foreach ($items as $item) {
				if (preg_match('/Scala ([0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}(:?\-[\w]+)?)/', $item, $m)) {
					$filtered_replace_items[] = $m[1];
				}
			}

			return $filtered_replace_items;
		};
	}

	public function getInstalledVersion()
	{
		$version = null;

		$version_str = exec('/usr/bin/env scala -version 2>&1');
		if (preg_match('/^.*([0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}).*$/', $version_str, $m)) {
			$version = $m[1];
		}

		return $version;
	}
}
