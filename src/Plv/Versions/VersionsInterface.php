<?php

/*
 * This file is part of the Plv package.
 *
 * (c) Isam Nagoya <nagodon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plv\Versions;

/**
 * VersionsInterface is the interface implemented by all versions classes.
 *
 * @author Isam Nagoya <nagodon@gmail.com>
 */
interface VersionsInterface
{
	public function getName();
	public function getUrl();
	public function getFilterValue();
	public function getCallback();
	public function getInstalledVersion();
}
