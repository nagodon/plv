<?php

namespace Plv;

use Plv\Plv;
use Plv\Collection\VersionsCollection;

class PlvTest extends \PHPUnit_Framework_TestCase
{
	protected static $fixturePath;

	public static function setupBeforeClass()
	{
		self::$fixturePath = realpath(__DIR__ . '/Fixtures/');
		require_once self::$fixturePath . '/PhpVersions.php';
		require_once self::$fixturePath . '/PerlVersions.php';
		require_once self::$fixturePath . '/PythonVersions.php';
		require_once self::$fixturePath . '/RubyVersions.php';
	}

	public function testConstructor()
	{
		$plv = new Plv(new VersionsCollection());
		$ref = new \ReflectionProperty($plv, 'versions');

		$ref->setAccessible(true);
		$this->assertInstanceOf('Plv\Collection\VersionsCollection', $ref->getValue($plv));
	}
}
