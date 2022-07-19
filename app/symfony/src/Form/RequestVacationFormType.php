<?php

namespace App\Form;

use App\Entity\Petition;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RequestVacationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('date', DateType::class, [
                'label' => false,
                'format' => 'dd/MM/yyyy',
                'widget'=> 'choice',
                'months' => range(4, 12),
                'attr' => [
                    'class' => 'form-control',
                ],])


            ->add('content', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],])
        ;



    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Petition::class,
        ]);
    }
}
