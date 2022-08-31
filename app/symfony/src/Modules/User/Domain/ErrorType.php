<?php

namespace App\Modules\User\Domain;
use PHPUnit\Framework\Exception;

class ErrorType extends Exception
{
    public function __construct()
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'invalid_name';
    }

    protected function errorMessage(): string
    {
        return sprintf('The name provided is empty');
    }
}