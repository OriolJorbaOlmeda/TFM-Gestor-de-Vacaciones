<?php

namespace App\Modules\Company\Domain;

use App\Entity\Company;

class CompanyExample
{
    public static function random(): Company
    {
        $company = new Company();
        $company->setName('MPWAR');
        $company->setDirection('Terrassa');
        $company->setPostalCode('08222');
        $company->setCif('2222');
        $company->setTelefono('232123311');

        return $company;
    }
}