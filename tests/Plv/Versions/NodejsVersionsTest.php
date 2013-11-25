<?php

namespace Plv\Versions;

use Plv\Versions\NodejsVersions;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class NodejsVersionsTest extends \PHPUnit_Framework_TestCase
{
	protected static $html;
	protected static $url = 'http://nodejs.org/';

	public static function setupBeforeClass()
	{
		$client = new Client();
		$client->request('GET', static::$url);
		static::$html = $client->getResponse()->getContent();
	}

	public function testGetName()
	{
		$pv = new NodejsVersions();
		$this->assertSame('node.js', $pv->getName());
	}

	public function testGetUrl()
	{
		$pv = new NodejsVersions();
		$this->assertSame(static::$url, $pv->getUrl());
	}

	public function testGetFilterValue()
	{
		$crawler = new Crawler();
		$pv = new NodejsVersions();

		$crawler->addHtmlContent(static::$html);
		$filters = $pv->getFilterValue();

		$items = array();
		foreach ($filters as $filter) {
			$items = array_merge($items, $crawler->filter($filter)->each(function (Crawler $crawler, $i) {
				return $crawler->text();
			}));
		}

		$this->assertSame(array('div#intro > p'), $pv->getFilterValue());
		$this->assertGreaterThanOrEqual(2, count($items));
		return $items;
	}

	/**
	 * @depends	testGetFilterValue
	 */
	public function testGetCallback($items)
	{
		$pv = new NodejsVersions();
		$callback = $pv->getCallback();
		$version_str = $callback($items);

		$this->assertTrue(is_callable($callback));
		$this->assertGreaterThanOrEqual(1, count($version_str));
		foreach ($version_str as $str) {
			$this->assertRegExp('/^[0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}/', $str);
		}
	}

	public function testGetInstalledVersion()
	{
		$pv = new NodejsVersions();
		$version = null;

		$version_str = exec('/usr/bin/env node --version 2>&1');
		if (preg_match('/^v([0-9]{1,}\.[0-9]{1,}\.[0-9]{1,})$/', $version_str, $m)) {
			$version = $m[1];
		}

		$this->assertSame($version, $pv->getInstalledVersion());
	}
}
