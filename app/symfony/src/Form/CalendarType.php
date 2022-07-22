<?php

namespace App\Form;

use App\Entity\Calendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarType extends AbstractType
{
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
                'choices' => [
                    '2022' => '2022',
                    '2023' => '2023',
                    '2024' => '2024',
                    '2025' => '2025',
                    '2026' => '2026',
                    '2027' => '2027',
                ],
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
}
