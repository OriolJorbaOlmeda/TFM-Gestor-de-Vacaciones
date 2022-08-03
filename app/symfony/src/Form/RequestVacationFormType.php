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
use Symfony\Contracts\Translation\TranslatorInterface;


class RequestVacationFormType extends AbstractType
{
    public function __construct(private TranslatorInterface $translator) {

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('initial_date', DateType::class, [
                'label' => $this->translator->trans('requestVacation.initialDate'),
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'required' => true,
            ])
            ->add('final_date', DateType::class, [
                'label' => $this->translator->trans('requestVacation.finalDate'),
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
            ->add('reason', TextareaType::class, [
                'label' => $this->translator->trans('petition.reason'),
                'attr' => ['class' => 'form-control', 'placeholder' => "Enter..."],
                'row_attr' => ['class' => 'form-group'],
                'required' => false,
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
