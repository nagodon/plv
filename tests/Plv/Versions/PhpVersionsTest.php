<?php

namespace Plv\Versions;

use Plv\Versions\PhpVersions;

class PhpVersionsTest extends \PHPUnit_Framework_TestCase
{
	public function testGetName()
	{
		$pv = new PhpVersions();
		$this->assertSame('PHP', $pv->getName());
	}

	public function testGetUrl()
	{
		$pv = new PhpVersions();
		$this->assertSame('http://php.net/', $pv->getUrl());
	}

	public function testGetFilterValue()
	{
		$pv = new PhpVersions();
		$this->assertSame('span.release', $pv->getFilterValue());
	}

	public function testGetCallback()
	{
		$pv = new PhpVersions();
		$this->assertSame(null, $pv->getCallback());
	}

	public function testGetInstalledVersion()
	{
		$pv = new PhpVersions();
		$this->assertSame(phpversion(), $pv->getInstalledVersion());
	}
}
