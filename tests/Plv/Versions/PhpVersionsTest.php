<?php

namespace Plv\Versions;

use Plv\Versions\PhpVersions;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class PhpVersionsTest extends \PHPUnit_Framework_TestCase
{
	protected static $html;

	public static function setupBeforeClass()
	{
		static::$html = file_get_contents(__DIR__.'/../../Fixtures/php.html');
	}

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
		$crawler = new Crawler();
		$pv = new PhpVersions();

		$crawler->addHtmlContent(static::$html);
		$filters = $pv->getFilterValue();

		$items = array();
		foreach ($filters as $filter) {
			$items = array_merge($items, $crawler->filter($filter)->each(function (Crawler $crawler, $i) {
				return $crawler->text();
			}));
		}

		$this->assertSame(array('a.download-link'), $pv->getFilterValue());
		$this->assertGreaterThanOrEqual(3, count($items));
		return $items;
	}

	/**
	 * @depends	testGetFilterValue
	 */
	public function testGetCallback($items)
	{
		$pv = new PhpVersions();
		$callback = $pv->getCallback();
		$version_str = $callback($items);

		$this->assertTrue(is_callable($callback));
		$this->assertGreaterThanOrEqual(3, count($version_str));
		foreach ($version_str as $str) {
			$this->assertRegExp('/^[0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}/', $str);
		}
	}

	public function testGetInstalledVersion()
	{
		$pv = new PhpVersions();
		$this->assertSame(phpversion(), $pv->getInstalledVersion());
	}
}
