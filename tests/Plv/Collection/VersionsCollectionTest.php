<?php

namespace Plv\Collection;

use Plv\Collection\VersionsCollection;

class VersionsCollectionTest extends \PHPUnit_Framework_TestCase
{
	public function testConstructor()
	{
		$vc = new VersionsCollection();
		$this->assertInstanceOf('Plv\Collection\VersionsCollection', $vc);
	}
}

