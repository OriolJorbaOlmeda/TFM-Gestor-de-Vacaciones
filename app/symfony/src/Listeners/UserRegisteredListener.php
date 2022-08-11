<?php

namespace App\Listeners;

use App\Modules\User\Domain\UserRegistrationEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class UserRegisteredListener
{
    private  MailerInterface $mailer;

    public function __construct(MailerInterface $mailer){
        $this->mailer = $mailer;
    }

    public function __invoke(UserRegistrationEvent $event)
    {
        $email = (new Email())
            ->from(new Address($event->getAdmin()))
            ->to($event->getEmail())
            ->subject('Vacation App credentials for '. $event->getName())
            ->text('Hello ' . $event->getName() . '. Your account has been created. Your username is: ' . $event->getEmail() . ' , and your password: ' . $event->getPassword());

        $this->mailer->send($email);
    }
}