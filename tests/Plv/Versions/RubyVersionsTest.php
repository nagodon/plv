<?php

namespace Plv\Versions;

use Plv\Versions\RubyVersions;

class RubyVersionsTest extends \PHPUnit_Framework_TestCase
{
	public function testGetName()
	{
		$rv = new RubyVersions();
		$this->assertSame('Ruby', $rv->getName());
	}

	public function testGetUrl()
	{
		$rv = new RubyVersions();
		$this->assertSame('http://www.ruby-lang.org/ja/downloads/', $rv->getUrl());
	}

	public function testGetFilterValue()
	{
		$rv = new RubyVersions();
		$this->assertSame('div#content > ul > li', $rv->getFilterValue());
	}

	public function testGetCallback()
	{
		$rv = new RubyVersions();
		$callback = $rv->getCallback();
		$version_str = array(
			'最新の安定版であるruby 2.0.0-p247[]',
			'前世代の安定版であるruby 1.9.3-p448[]',
			'前々世代の安定版であるruby 1.8.7-p374[]'
		);

		$this->assertTrue(is_callable($callback));
		$this->assertSame(array('2.0.0p247', '1.9.3p448', '1.8.7p374'), $callback($version_str));
	}

	public function testGetInstalledVersion()
	{
		$rv = new RubyVersions();
		$version = null;

		$version_str = exec('/usr/bin/env ruby --version 2>&1');
		if (preg_match('/^ruby ([0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}(p[0-9]{1,})?).*$/', $version_str, $m)) {
			$version = $m[1];
		}

		$this->assertSame($version, $rv->getInstalledVersion());
	}
}
