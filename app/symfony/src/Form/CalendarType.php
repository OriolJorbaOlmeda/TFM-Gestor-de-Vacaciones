<?php

namespace App\Form;

use App\Entity\Calendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
                'widget' => 'single_text',
                'required' => true

                // <span class="error invalid-feedback"> La fecha inicio tiene que ser mayor o igual a la fecha actual y menor a la fecha fin </span>

            ])
            ->add('final_date', DateType::class, [
                'label' => 'Fecha fin',
                'attr' => ['class' => 'form-control'],
                'widget' => 'single_text',
                'invalid_message' => 'You entered an invalid value, it should include %num% letters',
                'required' => true
            ])
            ->add('year', ChoiceType::class, [
                'label' => 'Año laboral',
                'attr' => ['class' => 'form-control select2'],
                'choices'  => [
                    '2022' => '2022',
                    '2023' => '2023',
                    '2024' => '2024',
                    '2025' => '2025',
                    '2026' => '2026',
                    '2027' => '2027',
                ],
                'required' => true
            ] )
            ->add('createCalendar', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => 'Crear calendario'
            ])
            /*->add('festives', CollectionType::class, [
                'entry_type' => FestiveType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true
            ])*/

            //->add('company')
            //->add('festives')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Calendar::class,
        ]);
    }
}
