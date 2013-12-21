<?php

namespace Plv\Versions;

use Plv\Versions\ScalaVersions;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class ScalaVersionsTest extends \PHPUnit_Framework_TestCase
{
	protected static $html;
	protected static $url = 'http://www.scala-lang.org/downloads';

	public static function setupBeforeClass()
	{
		$client = new Client();
		$client->request('GET', static::$url);
		static::$html = $client->getResponse()->getContent();
	}

	public function testGetName()
	{
		$pv = new ScalaVersions();
		$this->assertSame('Scala', $pv->getName());
	}

	public function testGetUrl()
	{
		$pv = new ScalaVersions();
		$this->assertSame(static::$url, $pv->getUrl());
	}

	public function testGetFilterValue()
	{
		$crawler = new Crawler();
		$pv = new ScalaVersions();

		$crawler->addHtmlContent(static::$html);
		$filters = $pv->getFilterValue();

		$items = array();
		foreach ($filters as $filter) {
			$items = array_merge($items, $crawler->filter($filter)->each(function (Crawler $crawler, $i) {
				return $crawler->text();
			}));
		}

		$this->assertSame(array('div.main-page-column > div.bigcircle-wrapper > div.bigcircle-content > p.center', 'div.main-page-column > ul > li'), $pv->getFilterValue());
		$this->assertGreaterThanOrEqual(3, count($items));
		return $items;
	}

	/**
	 * @depends	testGetFilterValue
	 */
	public function testGetCallback($items)
	{
		$pv = new ScalaVersions();
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
		$pv = new ScalaVersions();
		$version = null;

		$version_str = exec('/usr/bin/env scala -version 2>&1');
		if (preg_match('/^.*([0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}).*$/', $version_str, $m)) {
			$version = $m[1];
		}

		$this->assertSame($version, $pv->getInstalledVersion());
	}
}
