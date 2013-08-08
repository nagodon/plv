<?php

namespace Plv\Versions;

use Plv\Versions\PhpVersions;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class PhpVersionsTest extends \PHPUnit_Framework_TestCase
{
	private $html;
	private $url = 'http://php.net/';

	protected function setup()
	{
		$client = new Client();
		$client->request('GET', $this->url);
		$this->html = $client->getResponse()->getContent();
	}

	public function testGetName()
	{
		$pv = new PhpVersions();
		$this->assertSame('PHP', $pv->getName());
	}

	public function testGetUrl()
	{
		$pv = new PhpVersions();
		$this->assertSame($this->url, $pv->getUrl());
	}

	public function testGetFilterValue()
	{
		$crawler = new Crawler();
		$pv = new PhpVersions();

		$crawler->addHtmlContent($this->html);
		$filters = $pv->getFilterValue();

		$items = array();
		foreach ($filters as $filter) {
			$items = array_merge($items, $crawler->filter($filter)->each(function (Crawler $crawler, $i) {
				return $crawler->text();
			}));
		}

		$this->assertSame(array('span.release'), $pv->getFilterValue());
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
