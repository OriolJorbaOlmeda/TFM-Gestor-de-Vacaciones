<?php

namespace App\Modules\Company\Application;

use App\Entity\Company;
use App\Modules\Company\Infrastucture\CompanyRepository;

class GetCompanyById
{

    public function __construct(
        private CompanyRepository $companyRepository
    ){}

    public function getCompanyById(string $companyId): Company
    {
        return $this->companyRepository->findOneBy(['id' => $companyId]);

    }
}