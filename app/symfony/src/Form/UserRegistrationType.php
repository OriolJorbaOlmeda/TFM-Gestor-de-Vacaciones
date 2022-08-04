<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\DepartmentRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserRegistrationType extends AbstractType
{


    public function __construct(
        private DepartmentRepository $departmentRepository,
        private Security $security,
        private UserRepository $userRepository,
        private TranslatorInterface $translator){
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label'=>  $this->translator->trans('user.name'),
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label'=> $this->translator->trans('user.lastname'),
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => "mail@hotmail.com", 'class' => 'form-control'],
                'label'=> $this->translator->trans('user.email'),
                'required' => true
            ])
            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => $this->translator->trans('user.password'),
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/',
                        'message' => $this->translator->trans('user.passwordError')
                    ])
                ],
                'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control'],
                'required' => true
            ])
            ->add('direction',TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label'=> $this->translator->trans('user.direction'),
                'required' => true
            ])
            ->add('city',TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label'=> $this->translator->trans('user.city'),
                'required' => true
            ])
            ->add('province',TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label'=> $this->translator->trans('user.province'),
                'required' => true
            ])
            ->add('postalcode', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label'=> $this->translator->trans('user.postalCode'),
                'required' => true,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(?:0[1-9]|[1-4]\d|5[0-2])\d{3}$/',
                        'message' => $this->translator->trans('user.postalCodeError')
                    ])
                ],
             ])
            ->add('department', ChoiceType::class, [
                'label' => $this->translator->trans('user.department'),
                'choices' => $this->departments(),
                'choice_value' => 'id',
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control select2'],
                'required' => true,
                'placeholder' => $this->translator->trans('action.select')
            ])
            ->add('supervisor', ChoiceType::class, [
                'label' => $this->translator->trans('user.supervisor'),
                'choices' => $this->supervisors(),
                'choice_value' => 'id',
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control select2'],
                'required' => true,
                'placeholder' => $this->translator->trans('action.select')
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    $this->translator->trans('roles.admin') => 'ROLE_ADMIN',
                    $this->translator->trans('roles.supervisor') => 'ROLE_SUPERVISOR',
                    $this->translator->trans('roles.employee') => 'ROLE_EMPLEADO',
                ],
                'attr' => ['class' => 'form-control select2'],
                'label'=> $this->translator->trans('user.role'),
                'mapped' => false,
                'required' => true,
                'empty_data' => null,
                'placeholder' => $this->translator->trans('action.select')
            ])
            ->add('total_vacation_days', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label'=> $this->translator->trans('user.totalVacationDays'),
                'required' => true,
                'empty_data' => '0',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9]{1,2}$/',
                        'message' => $this->translator->trans('user.totalVacationDaysError')
                    ])
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label'=> $this->translator->trans('user.createUser')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    public function departments(): array
    {
        $company = $this->security->getUser()->getDepartment()->getCompany();
        return $this->departmentRepository->findBy(['company' => $company]);
    }

    public function supervisors(): array
    {
        return $this->userRepository->findAll();
    }
}
