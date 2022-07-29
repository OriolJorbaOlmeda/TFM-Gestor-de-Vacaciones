<?php

namespace App\Form;

use App\Entity\Calendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class CalendarType extends AbstractType
{

    public function __construct(private Security $security){}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('initial_date', DateType::class, [
                'label' => 'Fecha inicio',
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'widget' => 'single_text',
                'required' => true
            ])
            ->add('final_date', DateType::class, [
                'label' => 'Fecha fin',
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'widget' => 'single_text',
                'required' => true
            ])
            ->add('year', ChoiceType::class, [
                'label' => 'Año laboral',
                'attr' => ['class' => 'form-control select2'],
                'row_attr' => ['class' => 'form-group'],
                'choices' => $this->getNextCalendarYear(),
                'required' => true
            ])
            ->add('createCalendar', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => 'Crear calendario'
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
}
