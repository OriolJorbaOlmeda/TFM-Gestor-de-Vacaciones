<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class SelectUserType extends AbstractType
{


    public function __construct(private UserRepository $userRepository, private TranslatorInterface $translator) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('department', ChoiceType::class, [
                'choices' => $options['data'],
                'label'=> $this->translator->trans('user.department'),
                'placeholder'=> $this->translator->trans('action.select')
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => $this->translator->trans('action.search')
            ]);

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            listener: function (FormEvent $event) {
                $usersName = [];
                $users = $this->userRepository->findAll();
                 foreach ($users as $user) {
                     if($user->getRoles()[0]!='ROLE_ADMIN') {
                         $usersName[$user->getName()] = $user->getId();
                     }
                 }
                $form = $event->getForm();

                $formOptions = [
                    'choices' => $usersName,
                    'attr' => [
                        'class' => 'form-control select2',
                    ],
                    'placeholder'=> $this->translator->trans('action.select'),
                    'label' => $this->translator->trans('modifyUser.user')

                ];


                $form->add('user', ChoiceType::class, $formOptions);


            }
        );


    }


}
