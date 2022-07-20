<?php

namespace App\Form;

use App\Entity\Petition;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RequestVacationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('initial_date', DateType::class, [
                'label' => 'Fecha inicio periodo vacaciones',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                //'invalid_message' => 'You entered an invalid value, it should include %num% letters',
            ])
            ->add('final_date', DateType::class, [
                'label' => 'Fecha final periodo vacaciones',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                //'invalid_message' => 'You entered an invalid value, it should include %num% letters',
            ])
            ->add('duration', TextType::class, [
                'label' => 'Duración',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('reason', TextareaType::class, [
                'label' => 'Motivo',
                'attr' => ['class' => 'form-control', 'placeholder' => "Enter..."],
                'required' => false,
                //rows = 2
            ])
            ->add('save', SubmitType::class, [
            'attr' => ['class' => 'btn btn-primary'],
            'label' => 'Solicitar'
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Petition::class,
        ]);
    }
}
