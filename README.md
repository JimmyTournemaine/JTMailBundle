#Installation

JTMailBundle allow you to send mail easily. You can use [our pre-mailer to build your message style](https://github.com/JimmyTournemaine/PreMailer).

##Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

	$ composer require jimmytournemaine/jt-mail-bundle "master"

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

##Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:


	<?php
	// app/AppKernel.php
	
	// ...
	class AppKernel extends Kernel
	{
	    public function registerBundles()
	    {
	        $bundles = array(
	            // ...
	            new JT\MailBundle\JTMailBundle(),
	        );
	
	        // ...
	    }
	
	    // ...
	}
	```

##Step 3: Use the mailer

###Get the mailer

Just get 'jt_mail.mailer' from the container.

From a service :

	// use JT\MailBundle\Mailer\JTMailerInterface
	private $mailer;
	public function __construct(JTMailerInterface $mailer)
	{
		$this->mailer = $mailer
	}

	# src/AcmeBundle/Resources/config/services.yml
	acme_mailer:
	    class: AcmeBundle/EventListener/YourService
	    arguments: ["@jt_mail.mailer"]

From your controller :

	$this->container->get('jt_mail.mailer');

###Prepare your message, then send it !

There is two ways to prepare your message. Set templates and the service will render it.
Set directly parts of your message.
Header and Footer are used only if you send a HTML message.

	$mailer
		->setHeaderTemplate($template, array $parameters = array())
		->setHtmlBodyTemplate($template, array $parameters = array())
		->setTextBodyTemplate($template, array $parameters = array())
		->setFooterTemplate($template, array $parameters = array())
		->setHeader($content)
		->setHtmlBody($content)
		->setTextBody($content)
		->setFooter($content)
		->attach($filename)
		->setAttachments(array $filenames, $reset = true)
		->sendMessage($subject, $from, $to)
	;

[**See more details from the interface**](https://github.com/JimmyTournemaine/JTMailBundle/blob/master/Mailer/JTMailerInterface.php).

The mailer can send :
- [header] html [footer] [text] [attachments]
- Text only
