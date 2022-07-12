<?php

namespace App\Form;

use App\Entity\Festive;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FestiveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Descripción día festivo'],
                'required' => false
            ])
            ->add('date', DateType::class, [
                'label' => false,
                'attr' => ['class' => 'form-control'],
                'widget' => 'single_text',
                'required' => false
            ])


            ->add('addFestive', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary','name'=>'addFestive', 'id'=> 'btn-añadir',],
                'label' => 'Añadir'
            ])

            //->add('calendar')
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Festive::class,
        ]);
    }
}
