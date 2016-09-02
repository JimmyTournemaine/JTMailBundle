<?php
namespace JT\MailBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class MailEvent extends Event
{
	protected $message;

	public function __construct(\Swift_Message $message)
	{
		$this->message = $message;
	}

	public function getMessage()
	{
		return $this->message;
	}

	public function setMessage(\Swift_Message $message)
	{
		$this->message = $message;
	}
}