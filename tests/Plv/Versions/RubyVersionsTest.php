<?php

namespace Plv\Versions;

use Plv\Versions\RubyVersions;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class RubyVersionsTest extends \PHPUnit_Framework_TestCase
{
	protected static $html;
	protected static $url = 'http://www.ruby-lang.org/ja/downloads/';

	public static function setupBeforeClass()
	{
		$client = new Client();
		$client->request('GET', static::$url);
		static::$html = $client->getResponse()->getContent();
	}

	public function testGetName()
	{
		$rv = new RubyVersions();
		$this->assertSame('Ruby', $rv->getName());
	}

	public function testGetUrl()
	{
		$rv = new RubyVersions();
		$this->assertSame(static::$url, $rv->getUrl());
	}

	public function testGetFilterValue()
	{
		$crawler = new Crawler();
		$rv = new RubyVersions();

		$crawler->addHtmlContent(static::$html);
		$filters = $rv->getFilterValue();

		$items = array();
		foreach ($filters as $filter) {
			$items = array_merge($items, $crawler->filter($filter)->each(function (Crawler $crawler, $i) {
				return $crawler->text();
			}));
		}

		$this->assertSame(array('div#content > ul > li'), $rv->getFilterValue());
		$this->assertGreaterThanOrEqual(2, count($items));
		return $items;
	}

	/**
	 * @depends	testGetFilterValue
	 */
	public function testGetCallback($items)
	{
		$rv = new RubyVersions();
		$callback = $rv->getCallback();
		$version_str = $callback($items);

		$this->assertTrue(is_callable($callback));
		$this->assertGreaterThanOrEqual(2, count($version_str));
		foreach ($version_str as $str) {
			$this->assertRegExp('/^[0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}/', $str);
		}
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
