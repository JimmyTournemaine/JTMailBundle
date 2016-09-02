# How to use it ?

## Get the JTMailer service

### From any of yours services

```php
<?php
namespace AppBundle\Service;
use JT\MailBundle\Mailer\JTMailerInterface;

class YourService
{
	private $mailer;

	public function __construct(JTMailerInterface $mailer)
	{
		$this->mailer = $mailer
	}
}
```

```yaml
acme_mailer:
    class: AcmeBundle/EventListener/YourService
    arguments: ["@jt_mail.mailer"]
```

### From your controller

```php
$this->container->get('jt_mail.mailer');
```

##Prepare your message, then send it !

There is two ways to prepare your message. Set templates and the service will render it.
Set directly some parts of your message.
Header and Footer are used only if you send a HTML message.

```php
$mailer
	->setHeaderTemplate($template, array $parameters = array())
	->setHtmlBodyTemplate($template, array $parameters = array())
	->setTextBodyTemplate($template, array $parameters = array())
	->setFooterTemplate($template, array $parameters = array())
	->setHeader($content)
	->setHtmlBody($content)
	->setTextBody($content)
	->setFooter($content)
	->attach($file)
	->setAttachments(array $filenames, $reset = true)
	->sendMessage($subject, $from, $to)
;
```

##Next steps

- [Configure the bundle for auto adding header and footer](header_footer_configuration_2.md)
- [Use PreMailer](premailer_3.md)
