<?php

namespace App\Modules\User\Infrastucture\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Contracts\Translation\TranslatorInterface;

class CreateAdminType extends AbstractType
{

    public function __construct(private TranslatorInterface $translator){}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => "mail@hotmail.com", 'class' => 'form-control'],
                'label'=> $this->translator->trans('companyRegistration.admin.email'),
                'required' => true
            ])
            ->add('password', PasswordType::class, [
                'attr' => ['class' => 'form-control'],
                'label'=> $this->translator->trans('companyRegistration.admin.password'),
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/',
                        'message' => $this->translator->trans('companyRegistration.admin.passwordIncorrect')
                    ])
                ],
                'help' => $this->translator->trans('changePass.passHelp'),
                'required' => true
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label'=> $this->translator->trans('action.finish')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
