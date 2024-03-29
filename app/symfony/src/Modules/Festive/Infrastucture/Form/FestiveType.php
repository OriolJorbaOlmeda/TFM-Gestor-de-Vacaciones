<?php

namespace App\Modules\Festive\Infrastucture\Form;

use App\Entity\Festive;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class FestiveType extends AbstractType
{
    public function __construct(private TranslatorInterface $translator) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => $this->translator->trans('createFestive.name')],
                'required' => true
            ])
            ->add('date', DateType::class, [
                'label' => false,
                'attr' => ['class' => 'form-control'],
                'widget' => 'single_text',
                'required' => true
            ])
            ->add('addFestive', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => $this->translator->trans('createFestive.addFestive')
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Festive::class,
        ]);
    }
}
