<?php

namespace App\Modules\Calendar\Infrastucture\Form;

use App\Entity\Calendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use DateTime;
use Symfony\Contracts\Translation\TranslatorInterface;

class CalendarType extends AbstractType
{

    public function __construct(private Security $security, private TranslatorInterface $translator){}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('initial_date', DateType::class, [
                'label' => $this->translator->trans('createCalendar.initialDate'),
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'widget' => 'single_text',
                'required' => true,
                'data' => $this->getInitialDate()
            ])
            ->add('final_date', DateType::class, [
                'label' => $this->translator->trans('createCalendar.finalDate'),
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'widget' => 'single_text',
                'required' => true
            ])
            ->add('year', ChoiceType::class, [
                'label' => $this->translator->trans('createCalendar.year'),
                'attr' => ['class' => 'form-control select2'],
                'row_attr' => ['class' => 'form-group'],
                'choices' => $this->getNextCalendarYear(),
                'required' => true
            ])
            ->add('createCalendar', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => $this->translator->trans('createCalendar.createCalendar'),
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Calendar::class,
        ]);
    }

    private function getNextCalendarYear(): array {
        $calendars = $this->security->getUser()->getDepartment()->getCompany()->getCalendars();
        if (count($calendars) == 0) {
            $year = date("Y");
            return [$year => $year];
        }
        $cal = $calendars[count($calendars) - 1];
        $year = intval($cal->getYear()) + 1;
        return [$year => $year];

    }

    private function getInitialDate(): ?DateTime {
        $calendars = $this->security->getUser()->getDepartment()->getCompany()->getCalendars();
        if (count($calendars) == 0) {
            $year = date("Y");
            $date = strval($year) . '-01-01';
            return new DateTime($date);
        }
        $cal = $calendars[count($calendars) - 1];
        $ini = $cal->getFinalDate();
        $ini->modify('+1 day');
        return $ini;
    }
}
