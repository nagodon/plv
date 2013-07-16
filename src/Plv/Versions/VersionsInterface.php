<?php

namespace Plv\Versions;

interface VersionsInterface
{
	public function getName();
	public function getUrl();
	public function getFilterValue();
	public function getCallback();
	public function getInstalledVersion();
}
