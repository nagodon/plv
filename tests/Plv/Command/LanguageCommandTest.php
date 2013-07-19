<?php

namespace Plv\Command;

use Plv\Command\LanguageCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Application;

class LanguageCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecuteForCommand()
    {
        $command = new LanguageCommand();
        $commandTester = new CommandTester($command);
        $commandTester->execute(array());
        $this->assertRegExp("/perl\nphp\npython\nruby/", $commandTester->getDisplay());
    }

    public function testExecuteForApplicationCommand()
    {
        $application = new Application();
		$application->add(new LanguageCommand());
        $commandTester = new CommandTester($command = $application->get('language'));
        $commandTester->execute(array('command' => $command->getName()));
        $this->assertRegExp("/perl\nphp\npython\nruby/", $commandTester->getDisplay());
    }
}
