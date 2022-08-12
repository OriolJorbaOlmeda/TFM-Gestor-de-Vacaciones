<?php

namespace App\Modules\User\Domain;

use Symfony\Contracts\EventDispatcher\Event;

class UserRegistrationEvent extends Event
{
    private string $admin;
    private string $email;
    private string $name;
    private string $password;

    public function __construct(string $admin, string $email, string $name, string $password)
    {
        $this->admin = $admin;
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
    }

    public function getAdmin(): string
    {
        return $this->admin;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }



}