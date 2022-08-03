<?php

namespace App\Form;

use App\Entity\Petition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RequestAbsenceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('initial_date', DateType::class, [
                'label' => 'Fecha inicio periodo ausencia',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'required' => true,
                //'invalid_message' => 'You entered an invalid value, it should include %num% letters',
            ])
            ->add('final_date', DateType::class, [
                'label' => 'Fecha final periodo ausencia',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'required' => true,
                //'invalid_message' => 'You entered an invalid value, it should include %num% letters',
            ])
            ->add('duration', TextType::class, [
                'label' => 'Duración',
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'required' => true,
            ])
            ->add('reason', ChoiceType::class, [
                'label' => 'Motivo',
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'placeholder' => '-- Selecciona --',
                'choices' => [
                    'Baja por enfermedad' => 'Baja por enfermedad',
                    'Traslado' => 'Traslado',
                    'Fallecimiento de un familiar' => 'Fallecimiento de un familiar',
                    'Otros' => 'Otros'
                ],
                'required' => true,
            ])
            ->add('justify_content', FileType::class, [
                'label' => 'Adjuntar justificante',
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF/JPEG/PNG',
                    ])
                ],
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => 'Solicitar'
            ])
        ;

        /*$builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $product = $event->getData();
            var_dump($product);

        });*/

        $builder->get('initial_date')->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $date = $event->getData();
        });
    }


    public function listener() {

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Petition::class,
        ]);
    }
}
