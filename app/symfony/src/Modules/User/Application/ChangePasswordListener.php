<?php

namespace App\Modules\User\Application;

use App\Modules\User\Domain\ChangePasswordEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class ChangePasswordListener
{
    private  MailerInterface $mailer;

    public function __construct(MailerInterface $mailer){
        $this->mailer = $mailer;
    }

    public function __invoke(ChangePasswordEvent $event)
    {
        $email = (new Email())
            ->from('vacationGestor@gmail.com')
            ->to($event->getEmail())
            ->subject('New password changed')
            ->text('Your new password has been changed to: ' . $event->getPassword());

        $this->mailer->send($email);
    }
}