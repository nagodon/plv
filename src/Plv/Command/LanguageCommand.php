<?php

/*
 * This file is part of the Plv package.
 *
 * (c) Isam Nagoya <nagodon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plv\Command;

use Plv\Plv;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * LanguageCommand displays the list of supported programing language.
 *
 * @author Isam Nagoya <nagodon@gmail.com>
 */
class LanguageCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('language')
            ->setDescription('Display language list')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $iterator = Plv::findByLanguageFile();
        foreach ($iterator as $file) {
            $output->writeln(sprintf('<info>%s</info>', Plv::toLanguageName($file)));
        }
    }
}
