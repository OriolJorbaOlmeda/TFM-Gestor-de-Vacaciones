<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\DepartmentRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserModificationType extends AbstractType
{

    public function __construct(
        private DepartmentRepository $departmentRepository,
        private Security $security,
        private UserRepository $userRepository,
        private TranslatorInterface $translator){}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control', 'id' => 'name'],
                'label'=> $this->translator->trans('user.name'),
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'attr' => ['class' => 'form-control', 'id' => 'name'],
                'label'=> $this->translator->trans('user.lastname'),
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => "mail@hotmail.com", 'class' => 'form-control', 'id' => 'email'],
                'label'=> $this->translator->trans('user.email'),
                'required' => true
            ])
            ->add('direction',TextType::class, [
                'attr' => ['class' => 'form-control', 'id' => 'direccion'],
                'label'=> $this->translator->trans('user.direction'),
                'required' => true
            ])
            ->add('city',TextType::class, [
                'attr' => ['class' => 'form-control', 'id' => 'ciudad'],
                'label'=> $this->translator->trans('user.city'),
                'required' => true
            ])
            ->add('province',TextType::class, [
                'attr' => ['class' => 'form-control', 'id' => 'provincia'],
                'label'=> $this->translator->trans('user.province'),
                'required' => true
            ])
            ->add('postalcode', TextType::class, [
                'attr' => ['class' => 'form-control', 'id' => 'codigoPostal'],
                'label'=> $this->translator->trans('user.postalCode'),
                'required' => true
             ])

            ->add('department', ChoiceType::class, [
                'label' => $this->translator->trans('user.department'),
                'choices' => $this->departments(),
                'choice_value' => 'id',
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control select2'],
                'required' => true,
                'placeholder' => $this->translator->trans('action.select'),

            ])
            ->add('supervisor', ChoiceType::class, [
                'label' => $this->translator->trans('user.supervisor'),
                'choices' => $this->supervisor(),
                'choice_value' => 'id',
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control select2'],
                'required' => true,
                'placeholder' => $this->translator->trans('action.select'),
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
                'data'=>$options['data']->getRoles()[0]
            ])
            ->add('total_vacation_days', NumberType::class, [
                'attr' => ['class' => 'form-control', 'id' => 'diasVacaciones'],
                'label'=> $this->translator->trans('user.totalVacationDays'),
                'required' => true,
                'invalid_message' => $this->translator->trans('user.totalVacationDaysError'),
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label'=>  $this->translator->trans('action.save'),
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


    public function supervisor(): array{

       $users=  $this->userRepository->findAll();
       $supervisor=[];
       foreach ($users as $user){
           if($user->getRoles()[0]=="ROLE_SUPERVISOR"){
               $supervisor[]=$user;

           }
       }

        return $supervisor;
    }
}
