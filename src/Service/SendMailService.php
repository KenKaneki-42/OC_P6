<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class SendMailService
{
  private $mailer;

  public function __construct(MailerInterface $mailer)
  {
    $this->mailer = $mailer;
  }

  public function send(
    string $from,
    string $to,
    string $subject,
    string $htmlTemplate,
    array $context
  ): void
  {
    $email = (new TemplatedEmail())
      ->from($from)
      ->to($to)
      ->subject($subject)
      ->htmlTemplate("emails/$htmlTemplate.html.twig")
      ->context($context);

    $this->mailer->send($email);
  }

}
