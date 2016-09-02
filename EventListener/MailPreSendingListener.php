<?php
namespace JT\MailBundle\EventListener;

use JT\MailBundle\Event\MailEvent;
use JT\MailBundle\PreMailer\PreMailer;

class MailPreSendingListener
{

    private $preMailer;

    public function __construct(PreMailer $preMailer)
    {
        $this->preMailer = $preMailer;
    }

    /**
     * Extract CSS and move it inline
     *
     * JTMailer always put HTML as body.
     */
    public function onJtmailPresending(MailEvent $event)
    {
        $message = $event->getMessage();

        /* Get HTML and Text messages */
        switch ($message->getContentType()) {
            /* Text only */
            case 'text/plain':
                return;

            /* HTML only */
            case 'text/html':
                $html = $message->getBody();
                $text = null;
                break;

            /* HTML + Text */
            case 'multipart/alternative':
                $html = $message->getBody();
                $text = $message->getChildren()[0]->getBody();
                break;

            /* HTML or/and Text + File(s) */
            case 'multipart/mixed':
                $html = $message->getBody();
                if ($html == strip_tags($this->isHtml)) { // If text => no premailing
                    return;
                }
                foreach ($message->getChildren() as $child) { // Get alternative text
                    if ($child->getContentType() == 'text/plain') {
                        $text = $child->getBody();
                    }
                }
                break;

            default:
                throw new \InvalidArgumentException('"' . $message->getContentType() . '" is not a valid Content-Type.');
        }

        /* Edit HTML and eventually generate a text version */
        $this->preMailer->process($html);

        /* SET HTML and Text messages */
        $message->setBody($this->preMailer->getHtml());
        if(null !== $generatedText = $this->preMailer->getText())
        {
            switch ($message->getContentType())
            {
                case 'text/html':
                    $message->addPart($generatedText);
                    break;

                case 'multipart/alternative':
                    $message->getChildren()[0]->setBody($generatedText);
                    break;

                case 'multipart/mixed':
                    foreach ($message->getChildren() as $child) { // Get alternative text
                        if ($child->getContentType() == 'text/plain') {
                            $child->setBody($generatedText);
                        }
                    }
            }
        }

        // Save the edited message
        $event->setMessage($message);
    }
}
