<?php
namespace JT\MailBundle\Exception;

class PreMailingException extends \Exception
{
    public function __construct($status)
    {
        $this->message = "The PreMailer server return a $status error.";
    }
}