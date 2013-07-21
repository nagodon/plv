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
		$items = $crawler->filter($pv->getFilterValue());

		$this->assertSame('span.release', $pv->getFilterValue());
		$this->assertGreaterThanOrEqual(3, count($items));
	}

	public function testGetCallback()
	{
		$crawler = new Crawler();
		$pv = new PhpVersions();

		$callback = $pv->getCallback();
		$crawler->addHtmlContent($this->html);

		$items = $crawler->filter($pv->getFilterValue())->each(function ($crawler, $i) {
			return $crawler->text();
		});

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
