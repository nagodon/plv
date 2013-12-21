<?php

namespace Plv\Versions;

use Plv\Versions\PythonVersions;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class PythonVersionsTest extends \PHPUnit_Framework_TestCase
{
	protected static $html;

	public static function setupBeforeClass()
	{
		static::$html = file_get_contents(__DIR__.'/../../Fixtures/python.html');
	}

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
		$crawler = new Crawler();
		$pv = new PythonVersions();

		$crawler->addHtmlContent(static::$html);
		$filters = $pv->getFilterValue();

		$items = array();
		foreach ($filters as $filter) {
			$items = array_merge($items, $crawler->filter($filter)->each(function (Crawler $crawler, $i) {
				return $crawler->text();
			}));
		}

		$this->assertSame(array('div#download-python > p > a'), $pv->getFilterValue());
		$this->assertGreaterThanOrEqual(2, count($items));
		return $items;
	}

	/**
	 * @depends	testGetFilterValue
	 */
	public function testGetCallback($items)
	{
		$pv = new PythonVersions();
		$callback = $pv->getCallback();
		$version_str = $callback($items);

		$this->assertTrue(is_callable($callback));
		$this->assertGreaterThanOrEqual(2, count($version_str));
		foreach ($version_str as $str) {
			$this->assertRegExp('/^[0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}/', $str);
		}
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
