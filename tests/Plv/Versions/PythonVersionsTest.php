<?php

namespace Plv\Versions;

use Plv\Versions\PythonVersions;

class PythonVersionsTest extends \PHPUnit_Framework_TestCase
{
	public function testGetName()
	{
		$pv = new PythonVersions();
		$this->assertSame('Python', $pv->getName());
	}

	public function testGetUrl()
	{
		$pv = new PythonVersions();
		$this->assertSame('http://www.python.org/download/', $pv->getUrl());
	}

	public function testGetFilterValue()
	{
		$pv = new PythonVersions();
		$this->assertSame('div#download-python > p > a', $pv->getFilterValue());
	}

	public function testGetCallback()
	{
		$pv = new PythonVersions();
		$callback = $pv->getCallback();
		$version_str = array('Python 2.7.4', 'Python 3.3.1');

		$this->assertTrue(is_callable($callback));
		$this->assertSame(array('2.7.4', '3.3.1'), $callback($version_str));
	}

	public function testGetInstalledVersion()
	{
		$pv = new PythonVersions();
		$version = null;

		$version_str = exec('/usr/bin/env python -V 2>&1');
		if (preg_match('/^.*([0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}).*$/', $version_str, $m)) {
			$version = $m[1];
		}

		$this->assertSame($version, $pv->getInstalledVersion());
	}
}
