<?php

namespace App\Modules\Petition\Application;

use App\Entity\Calendar;
use App\Entity\Petition;
use App\Modules\Petition\Infrastucture\PetitionRepository;
use App\Modules\User\Application\SearchUser;
use App\Modules\User\Application\UpdateVacationDays;
use Psr\Container\ContainerInterface;
use Symfony\Component\Security\Core\Security;

class RequestAbsence
{
    public function __construct(
        private PetitionRepository $petitionRepository,
        private ContainerInterface $container,
        private Security $security,
        private SearchUser $searchUser,
        private UpdateVacationDays $updateVacationDays
    ){}

    public function requestVacation(Petition $petition, Calendar $calendar)
    {
        // Ya están rellenos: initial_date, final_date, duration y reason

        // Rellenar los datos que faltan: state, type, petition_date, employee, calendar, justify y supervisor
        $petition->setType($this->container->getParameter('absence'));

        $user = $this->searchUser->searchUserById($this->security->getUser()->getId());

        // si es supervisor el supervisor será el mismo
        if (in_array($this->container->getParameter('role_supervisor'), $this->security->getUser()->getRoles())) {

            $petition->setSupervisor($user);
            $petition->setState($this->container->getParameter('accepted'));
            $duration = $petition->getDuration();
            $this->updateVacationDays->updateVacationDays($user, $duration);
        } else {
            $petition->setSupervisor($this->security->getUser()->getSupervisor());
            $petition->setState($this->container->getParameter('pending'));
        }

        $petition->setPetitionDate(new \DateTime());
        $petition->setEmployee($user);

        $petition->setCalendar($calendar);
        $this->petitionRepository->add($petition, true);

    }

}