<?php
namespace JT\MailBundle\Command;

use Symfony\Bundle\SwiftmailerBundle\Command\SendEmailCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Use The Premailer to edit messages before sending them.
 *
 * @author Jimmy Tournemaine <jimmy.tournemaine@yahoo.fr>
 */
class SendMailCommand extends SendEmailCommand
{

    protected function configure()
    {
        parent::configure();
        $this->setName("jt_mail:spool:send");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getOption('mailer');
        if ($name) {
            $this->processMailer($name, $input, $output);
        } else {
            $mailers = array_keys($this->getContainer()->getParameter('swiftmailer.mailers'));
            foreach ($mailers as $name) {
                $this->processMailer($name, $input, $output);
            }
        }
    }

    private function processMailer($name, $input, $output)
    {
        if (!$this->getContainer()->has(sprintf('swiftmailer.mailer.%s', $name))) {
            throw new \InvalidArgumentException(sprintf('The mailer "%s" does not exist.', $name));
        }

        $output->write(sprintf('<info>[%s]</info> Processing <info>%s</info> mailer... ', date('Y-m-d H:i:s'), $name));
        if ($this->getContainer()->getParameter(sprintf('swiftmailer.mailer.%s.spool.enabled', $name))) {
            $mailer = $this->getContainer()->get(sprintf('swiftmailer.mailer.%s', $name));
            $transport = $mailer->getTransport();
            if ($transport instanceof \Swift_Transport_SpoolTransport) {
                $spool = $transport->getSpool();
                if ($spool instanceof \Swift_ConfigurableSpool) {
                    $spool->setMessageLimit($input->getOption('message-limit'));
                    $spool->setTimeLimit($input->getOption('time-limit'));
                }
                if ($spool instanceof \Swift_FileSpool) {
                    if (null !== $input->getOption('recover-timeout')) {
                        $spool->recover($input->getOption('recover-timeout'));
                    } else {
                        $spool->recover();
                    }
                }
                $sent = $spool->flushQueue($this->getContainer()->get(sprintf('swiftmailer.mailer.%s.transport.real', $name)));

                $output->writeln(sprintf('<comment>%d</comment> emails sent', $sent));
            }
        } else {
            $output->writeln('No email to send as the spool is disabled.');
        }
    }
}