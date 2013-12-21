<?php

namespace Plv\Versions;

use Plv\Versions\PerlVersions;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class PerlVersionsTest extends \PHPUnit_Framework_TestCase
{
	protected static $html;

	public static function setupBeforeClass()
	{
		static::$html = file_get_contents(__DIR__.'/../../Fixtures/perl.html');
	}

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
		$crawler = new Crawler();
		$pv = new PerlVersions();

		$crawler->addHtmlContent(static::$html);
		$filters = $pv->getFilterValue();

		$items = array();
		foreach ($filters as $filter) {
			$items = array_merge($items, $crawler->filter($filter)->each(function (Crawler $crawler, $i) {
				return $crawler->text();
			}));
		}

		$this->assertSame(array('div#short_lists > div.quick_links > div.list > p > a'), $pv->getFilterValue());
		$this->assertGreaterThan(1, count($items));
		return $items;
	}

	/**
	 * @depends	testGetFilterValue
	 */
	public function testGetCallback($items)
	{
		$pv = new PerlVersions();
		$callback = $pv->getCallback();
		$version_str = $callback($items);

		$this->assertTrue(is_callable($callback));
		$this->assertEquals(1, count($version_str));
		$this->assertRegExp('/^[0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}$/', $version_str[0]);
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
