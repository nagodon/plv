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
 * PhpVersions represents an php Version.
 *
 * @author Isam Nagoya <nagodon@gmail.com>
 */
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
