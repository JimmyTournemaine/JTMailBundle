<?php
namespace JT\MailBundle\Tests;

class SendingTest
{
	private $mailer;
	private $receiver;

	public function __construct($mailer)
	{
		$this->mailer = $mailer;
	}

	public function send($to)
	{
		$this->mailer
			->setHeader('<h1>My company</h1><h2>Partner in success</h2>');
			->setHtmlBody('<p>This is a sending test.</p><p>Here is a <strong>strong</strong> word.</p>')
			->setTextBody('This is a sending test. Here is a strong word.')
			->setFooter('<a href="https://github.com/JimmyTournemaine">My social link</a>')
			->sendMessage('Test', 'me@domain.com', $this->receiver)
		;
	}
}
