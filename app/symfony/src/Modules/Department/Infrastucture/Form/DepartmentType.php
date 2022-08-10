<?php

namespace App\Modules\Department\Infrastucture\Form;

use App\Entity\Department;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class DepartmentType extends AbstractType
{

    public function __construct(private TranslatorInterface $translator){}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => $this->translator->trans('companyRegistration.departments.name')],
                'required' => true
            ])
            ->add('code', TextType::class, [
                'label' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => $this->translator->trans('companyRegistration.departments.code')],
                'required' => true
            ])
            ->add('addDepartment', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => $this->translator->trans('action.add')
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
