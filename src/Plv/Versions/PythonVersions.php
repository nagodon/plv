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
 * PythonVersions represents an python version.
 *
 * @author Isam Nagoya <nagodon@gmail.com>
 */
class PythonVersions implements VersionsInterface
{
    private $url = 'http://www.python.org/downloads/';

    public function getName()
    {
        return 'Python';
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getFilterValue()
    {
        return array('ol.list-row-container > li > span.release-number > a');
    }

    public function getCallback()
    {
        return function ($items) {
            $filtered_replace_items = array();
            foreach ($items as $item) {
                if (preg_match('/^Python ([0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}(-?[a-zA-Z0-9]+)?)$/', $item, $m)) {
                    $filtered_replace_items[] = $m[1];
                }
            }

            return $filtered_replace_items;
        };
    }

    public function getInstalledVersion()
    {
        $version = null;

        $version_str = exec('/usr/bin/env python -V 2>&1');
        if (preg_match('/^.*([0-9]{1,}\.[0-9]{1,}\.[0-9]{1,}).*$/', $version_str, $m)) {
            $version = $m[1];
        }

        return $version;
    }
}
