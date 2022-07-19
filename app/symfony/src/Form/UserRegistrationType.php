<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\DepartmentRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserRegistrationType extends AbstractType
{

    private DepartmentRepository $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository){
        $this->departmentRepository = $departmentRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control', 'id' => 'name'],
                'label'=> 'Nombre',
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'attr' => ['class' => 'form-control', 'id' => 'name'],
                'label'=> 'Apellidos',
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => "mail@hotmail.com", 'class' => 'form-control', 'id' => 'email'],
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
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
                'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control', 'id' => 'password'],
                'required' => true
            ])
            ->add('direction',TextType::class, [
                'attr' => ['class' => 'form-control', 'id' => 'direccion'],
                'label'=> 'Dirección',
                'required' => true
            ])
            ->add('city',TextType::class, [
                'attr' => ['class' => 'form-control', 'id' => 'ciudad'],
                'label'=> 'Ciudad',
                'required' => true
            ])
            ->add('province',TextType::class, [
                'attr' => ['class' => 'form-control', 'id' => 'provincia'],
                'label'=> 'Provincia',
                'required' => true
            ])
            ->add('postalcode', TextType::class, [
                'attr' => ['class' => 'form-control', 'id' => 'codigoPostal'],
                'label'=> 'Código postal',
                'required' => true
             ])
            ->add('department', ChoiceType::class, [
                'label' => 'Department',
                'choices' => $this->departments(),
                'choice_value' => 'id',
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control select2'],
                'required' => true
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => array(
                    'Admin' => 'ROLE_ADMIN',
                    'Supervisor' => 'ROLE_SUPERVISOR',
                    'Empleado' => 'ROLE_EMPLEADO',
                ),
                'attr' => ['class' => 'form-control select2'],
                'label'=> 'Rol',
                'mapped' => false,
                'required' => true,
                'empty_data' => null

            ])
            ->add('total_vacation_days', TextType::class, [
                'attr' => ['class' => 'form-control', 'id' => 'diasVacaciones'],
                'label'=> 'Vacation days',
                'required' => true
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
        return $this->departmentRepository->findAll();
    }
}