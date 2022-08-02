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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserRegistrationType extends AbstractType
{


    public function __construct(
        private DepartmentRepository $departmentRepository,
        private Security $security,
        private UserRepository $userRepository){
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label'=> 'Nombre',
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label'=> 'Apellidos',
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => "mail@hotmail.com", 'class' => 'form-control'],
                'label'=> 'Email',
                'required' => true
            ])
            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => 'Password',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ min }} characters',
                        'max' => 4096,
                    ]),
                ],
                'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control'],
                'required' => true
            ])
            ->add('direction',TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label'=> 'Dirección',
                'required' => true
            ])
            ->add('city',TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label'=> 'Ciudad',
                'required' => true
            ])
            ->add('province',TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label'=> 'Provincia',
                'required' => true
            ])
            ->add('postalcode', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label'=> 'Código postal',
                'required' => true
             ])
            ->add('department', ChoiceType::class, [
                'label' => 'Department',
                'choices' => $this->departments(),
                'choice_value' => 'id',
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control select2'],
                'required' => true,
                'placeholder' => '-- Selecciona --'
            ])
            ->add('supervisor', ChoiceType::class, [
                'label' => 'Supervisor',
                'choices' => $this->supervisors(),
                'choice_value' => 'id',
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control select2'],
                'required' => false,
                'placeholder' => '-- Selecciona --'
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Supervisor' => 'ROLE_SUPERVISOR',
                    'Empleado' => 'ROLE_EMPLEADO',
                ],
                'attr' => ['class' => 'form-control select2'],
                'label'=> 'Rol',
                'mapped' => false,
                'required' => true,
                'empty_data' => null,
                'placeholder' => '-- Selecciona --',
            ])
            ->add('total_vacation_days', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label'=> 'Vacation days',
                'required' => false,
                'invalid_message' => 'Este valor debe ser numérico'
            ])
            //->add('pending_vacation_days')
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label'=> 'Dar de Alta'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    public function departments(): array{
        $user = $this->security->getUser();
        $department = $user->getDepartment();
        $company = $department->getCompany();

        return $this->departmentRepository->findBy(['company' => $company]);
    }

    public function supervisors(): array{

        $users = $this->userRepository->findBy(['department' => '2']);

        return $users;

    }
}
