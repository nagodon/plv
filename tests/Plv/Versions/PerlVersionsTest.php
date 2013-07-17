<?php

namespace Plv\Versions;

use Plv\Versions\PerlVersions;

class PerlVersionsTest extends \PHPUnit_Framework_TestCase
{
	public function testGetName()
	{
		$pv = new PerlVersions();
		$this->assertSame('Perl', $pv->getName());
	}

	public function testGetUrl()
	{
		$pv = new PerlVersions();
		$this->assertSame('http://perl.org/', $pv->getUrl());
	}

	public function testGetFilterValue()
	{
		$pv = new PerlVersions();
		$this->assertSame('div#short_lists > div.quick_links > div.list > p > a', $pv->getFilterValue());
	}

	public function testGetCallback()
	{
		$pv = new PerlVersions();
		$callback = $pv->getCallback();
		$version_str = array('5.18.0 - download now');

		$this->assertTrue(is_callable($callback));
		$this->assertSame(array('5.18.0'), $callback($version_str));
	}

	public function testGetInstalledVersion()
	{
		$pv = new PerlVersions();
		$version = null;

		exec('/usr/bin/env perl --version 2>&1', $output);
		foreach ($output as $line) {
			if (preg_match('/^.*([0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}).*$/', $line, $m)) {
				$version = $m[1];
				break;
			}
		}

		$this->assertSame($version, $pv->getInstalledVersion());
	}
}
