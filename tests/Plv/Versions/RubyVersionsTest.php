<?php

namespace Plv\Versions;

use Symfony\Component\DomCrawler\Crawler;

class RubyVersionsTest extends \PHPUnit_Framework_TestCase
{
    protected static $html;

    public static function setupBeforeClass()
    {
        static::$html = file_get_contents(__DIR__.'/../../Fixtures/ruby.html');
    }

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

        $this->assertSame(array('div#content > ul > li > p > a'), $rv->getFilterValue());
        $this->assertGreaterThanOrEqual(3, count($items));

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
        $this->assertGreaterThanOrEqual(3, count($version_str));
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
