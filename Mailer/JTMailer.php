<?php
namespace JT\MailBundle\Mailer;

use JT\MailBundle\Mailer\JTMailerInterface;
use JT\MailBundle\Exception\NoMessageToSendException;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use JT\MailBundle\Event\MailEvent;
use JT\MailBundle\JTMailEvents;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File;


/**
 * Mailer to easily send HTML and Plain text mail
 * @author Jimmy Tournemaine <jimmy.tournemaine@yaho.Fr>
 */
class JTMailer implements JTMailerInterface
{
	/**
	 * @var The templating service
	 */
	private $templating;

	/**
	 * @var The event dispatcher
	 */
	private $dispatcher;

	/**
	 * @var The Swift Mailer
	 */
	private $mailer;

	/**
	 * @var The header of the HTML Message
	 */
	private $header;

	/**
	 * @var string The HTML content
	 */
	private $htmlBody;

	/**
	 * @var string The plain text content
	 */
	private $textBody;

	/**
	 * @var string The footer of the HTML Message
	 */
	private $footer;

	/**
	 * @var array An array of filenames to attach to the mail
	 */
	private $attachments;

	public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, EventDispatcherInterface $dispatcher)
	{
	    $this->mailer = $mailer;
		$this->templating = $templating;
		$this->dispatcher = $dispatcher;
		$this->attachments = array();
	}

	public function sendMessage($subject, $from, $to)
	{
		if($this->htmlBody === null && $this->textBody === null) {
			throw new NoMessageToSendException();
		}

		$message = \Swift_Message::newInstance()
			->setSubject($subject)
			->setFrom($from)
			->setTo($to)
		;

		if($this->htmlBody === null) {
			$message->setBody($this->textBody, 'text/plain');
		} else {
			$message->setBody($this->header . $this->htmlBody . $this->footer, 'text/html');
			if($this->textBody !== null) {
				$message->addPart($this->textBody, 'text/plain');
			}
		}

		foreach($this->attachments as $attachment){
			$message->attach(\Swift_Attachment::fromPath($attachment));
		}

		$event = new MailEvent($message);
		$this->dispatcher->dispatch(JTMailEvents::MAIL_PRE_SENDING, $event);
		$message = $event->getMessage();

		return $this->mailer->send($message);
	}

	public function setHeaderTemplate($template, array $parameters = array())
	{
		$this->header = $this->templating->render($template, $parameters);
		return $this;
	}

	public function setHtmlBodyTemplate($template, array $parameters = array())
	{
		$this->htmlBody = $this->templating->render($template, $parameters);
		return $this;
	}

	public function setTextBodyTemplate($template, array $parameters = array())
	{
		$this->textBody = $this->templating->render($template, $parameters);
		return $this;
	}

	public function setFooterTemplate($template, array $parameters = array())
	{
		$this->footer = $this->templating->render($template, $parameters);
		return $this;
	}

	public function setHeader($content)
	{
		$this->header = $content;
		return $this;
	}

	public function setHtmlBody($content)
	{
		$this->htmlBody = $content;
		return $this;
	}

	public function setTextBody($content)
	{
		$this->textBody = $content;
		return $this;
	}

	public function setFooter($content)
	{
		$this->footer = $content;
		return $this;
	}

	public function attach($file)
	{
	    $filename = ($file instanceof File) ? $file->getFilename() : $file;
	    if(!file_exists($file)){
	        throw new FileNotFoundException("Unable to find $file.");
	    }

		$this->attachments[] = $filename;
		return $this;
	}

	public function setAttachments(array $filenames, $reset = true)
	{
		if($reset === true){
			$this->attachments = $filenames;
		} else {
			$this->attachments = array_merge($this->attachments, $filenames);
		}
		return $this;
	}
}
