<?php

namespace App\Modules\Company\Infrastucture\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Contracts\Translation\TranslatorInterface;

class CompanyType extends AbstractType
{

    public function __construct(private TranslatorInterface $translator){}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'label'=>  $this->translator->trans('companyRegistration.company.name'),
                'required' => true
            ])
            ->add('direction', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'label'=>  $this->translator->trans('companyRegistration.company.direction'),
                'required' => true
            ])
            ->add('postalCode', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'label'=>  $this->translator->trans('companyRegistration.company.postalCode'),
                'required' => true,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(?:0[1-9]|[1-4]\d|5[0-2])\d{3}$/',
                        'message' => $this->translator->trans('user.postalCodeError')
                    ])
                ],
            ])
            ->add('cif', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'label'=>  $this->translator->trans('companyRegistration.company.cif'),
                'required' => true
            ])
            ->add('telefono', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'label'=>  $this->translator->trans('companyRegistration.company.phone'),
                'required' => true
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label'=> $this->translator->trans('action.next'),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
