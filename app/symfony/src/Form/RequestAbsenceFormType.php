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
use Symfony\Contracts\Translation\TranslatorInterface;

class RequestAbsenceFormType extends AbstractType
{
    public function __construct(private TranslatorInterface $translator){}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('initial_date', DateType::class, [
                'label' => $this->translator->trans('requestAbsence.initialDate'),
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'required' => true,
            ])
            ->add('final_date', DateType::class, [
                'label' => $this->translator->trans('requestAbsence.finalDate'),
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'required' => true,
            ])
            ->add('duration', TextType::class, [
                'label' => $this->translator->trans('petition.duration'),
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'required' => true,
            ])
            ->add('reason', ChoiceType::class, [
                'label' => $this->translator->trans('petition.reason'),
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'placeholder' => $this->translator->trans('action.select'),
                'choices' => [
                    $this->translator->trans('requestAbsence.reason1') => $this->translator->trans('requestAbsence.reason1'),
                    $this->translator->trans('requestAbsence.reason2') => $this->translator->trans('requestAbsence.reason2'),
                    $this->translator->trans('requestAbsence.reason3') => $this->translator->trans('requestAbsence.reason3'),
                    $this->translator->trans('requestAbsence.reason4') => $this->translator->trans('requestAbsence.reason4')
                ],
                'required' => true,
            ])
            ->add('justify_content', FileType::class, [
                'label' => $this->translator->trans('petition.addJustify'),
                'attr' => ['class' => 'form-control', 'accept' => 'image/*, application/pdf'],
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
                'label' => $this->translator->trans('petition.request'),
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
