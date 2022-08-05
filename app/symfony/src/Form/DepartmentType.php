<?php

namespace App\Form;

use App\Entity\Department;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Nombre del departamento'],
                'required' => true
            ])
            ->add('code', TextType::class, [
                'label' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Código del departamento'],
                'required' => true
            ])
            ->add('addDepartment', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => 'Añadir'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Department::class,
        ]);
    }
}
