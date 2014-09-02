<?php
/*
 * This file is part of the Plv package.
 *
 * (c) Isam Nagoya <nagodon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plv;

use \ArrayIterator;
use Goutte\Client;
use Guzzle\Http\Client as GuzzleClient;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Finder\Finder;

/**
 * Plv.
 *
 * @author Isam Nagoya <nagodon@gmail.com>
 */
class Plv
{
    private $versions;

    public function __construct(ArrayIterator $versions)
    {
        $this->versions = $versions;
    }

    public function execute()
    {
        $output = new ConsoleOutput();
        $client = new Client();
        $client->setClient($this->createGuzzleClient());

        foreach ($this->versions as $versions) {
            $output->writeln(sprintf("<info>Check the version of %s</info>", $versions->getName()));

            $filters = $versions->getFilterValue();
            $crawler = $client->request('GET', $versions->getUrl());
            if ('gzip' == $client->getResponse()->getHeader('Content-Encoding')) {
                $content = gzdecode($client->getResponse()->getContent());
                $crawler->addHtmlContent($content);
            }

            $items = array();
            foreach ($filters as $filter) {
                $current_items = $crawler->filter($filter)->each(function (Crawler $crawler, $i) {
                    return $crawler->text();
                });

                $items = array_merge($items, $current_items);
            }

            $callback = $versions->getCallback();
            if (is_callable($callback)) {
                $items = $callback($items);
            }

            usort($items, function ($left, $right) {
                return version_compare($right, $left);
            });

            $installed_version = $versions->getInstalledVersion();
            if (is_null($installed_version)) {
                $installed_version = '<fg=red;>not installed</fg=red>';
            } else {
                $items = array_map(function ($item) use ($installed_version) {
                    if (version_compare($item, $installed_version, '>')) {
                        $item = '<fg=red;>New</fg=red>' . $item;
                    }

                    return $item;
                }, $items);
            }

            $output->writeln(sprintf('current version : <comment>%s</comment>', implode(' ', $items)));
            $output->writeln(sprintf('installed version : <comment>%s</comment>', $installed_version));
            $output->writeln('');
        }
    }

    private function createGuzzleClient()
    {
        return new GuzzleClient('', array(
            GuzzleClient::SSL_CERT_AUTHORITY => 'system',
            GuzzleClient::DISABLE_REDIRECTS => true,
            GuzzleClient::CURL_OPTIONS => array(
                CURLOPT_NOPROGRESS => false,
                CURLOPT_PROGRESSFUNCTION => function ($resource, $download_size, $downloaded, $upload_size, $uploaded) {
                    if (0 < $download_size) {
                        printf("dowloading... %d%%\r", 100 * ($downloaded / $download_size));
                        flush();
                    }
                }
            )
        ));
    }

    public static function findByLanguageFile()
    {
        $finder = new Finder();

        return $finder
            ->files()
            ->name('*Versions.php')
            ->in(ROOT_PATH . 'src/Plv/Versions')
            ->sortByName();
    }

    public static function toLanguageName(\SplFileInfo $file)
    {
        return strtolower(substr(basename($file), 0, -12));
    }
}
