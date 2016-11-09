<?php

namespace EventBundle\Factory;

use Psr\Log\LoggerInterface,
    Psr\Log\LoggerAwareInterface,
    Psr\Log\NullLogger,
    Psr\Log\LoggerAwareTrait;

/**
 * Fabryka mailingu.
 *
 * @author Andrzej Wojdas <praca.aw@gmail.com>
 */
class Mailer implements LoggerAwareInterface {

    use LoggerAwareTrait;

    protected $mailer;

    public function __construct(\Swift_Mailer $mailer, LoggerInterface $logger = null) {
        $this->mailer = $mailer;
        $this->logger = $logger ? new NullLogger() : $logger;
    }

    public function send($from, $to, $subject, $bodyHtml) {
        $message = $this->mailer->createMessage();

        $message->setSubject($subject)
                ->setBody($bodyHtml, 'text/html')
                ->setTo($to)
                ->setFrom($from);

        return $message;
    }

}
