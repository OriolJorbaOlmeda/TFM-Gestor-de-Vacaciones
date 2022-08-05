<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangePasswordType extends AbstractType
{
    public function __construct(private TranslatorInterface $translator) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                //'mapped' => false,
                'label' => $this->translator->trans('changePass.pass'),
                'constraints' => [
                    new UserPassword([
                        'message' => $this->translator->trans('changePass.passIncorrect')
                    ])
                ],
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'required' => true,
                //'data' => $actualPass
            ])
            ->add('new_password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                //'mapped' => false,
                'label' => $this->translator->trans('changePass.newPass'),
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/',
                        'message' => $this->translator->trans('changePass.newPassIncorrect')
                    ])
                ],
                'help' => $this->translator->trans('changePass.passHelp'),
                'attr' => [ 'class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'], 
                'required' => true
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary btn-block'],
                'label'=> $this->translator->trans('changePass.button')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
