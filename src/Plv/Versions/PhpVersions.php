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
		return 'span.release';
	}

	public function getCallback()
	{
		return null;
	}

	public function getInstalledVersion()
	{
		return phpversion();
	}
}
