<?php
namespace JT\MailBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class SendMailCommand extends Command
{
	protected function configure()
	{
		$this
			->setName('jt:mail:send')
			->setDescription('Send a mail')
			->setHelp("This command allows you to test a mail sending.")
			->addArgument('to', InputArgument::REQUIRED, 'To who we have to send the message.')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$sender = $this->getContainer()->get('jt_mail.test.sending');
		$to = $intput->getArgument('to');

		$sender->send();
		$output->writeln("Message sent to $to.");
	}
}

