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

class SelectUserType extends AbstractType
{


    public function __construct(UserRepository $userRepository,
    ) {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'department',
                ChoiceType::class,
                [
                    'choices' => $options['data']
                ]
            )
           /* ->add('user', ChoiceType::class, [
                'choices' => $usersName,
                'attr' => [
                    'class' => 'form-control select2',
                ]
            ])*/
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => 'Buscar'
            ]);
        /* $formModifier = function (FormInterface $form, $data) {
             $form->add('user', ChoiceType::class, [
                 'choices' => $data,
                 'attr' => [
                     'class' => 'form-control select2',
                     'visibility'=> 'hidden'
                 ]
             ]);
         };

*/
         $builder->addEventListener(
             FormEvents::POST_SET_DATA,
             function (FormEvent $event)  {
                 $department = $event->getForm()->getData();
                 $usersName = [];
                 $users = $this->userRepository->findBy(array('department' => $department));
                 foreach ($users as $user) {
                     $usersName[$user->getName()] = $user->getId();
                 }
                 $form = $event->getForm();

                 $formOptions = [

                         'choices' => $usersName,
                         'attr' => [
                             'class' => 'form-control select2',
                        ]

                 ];


                 $form->add('user', ChoiceType::class, $formOptions);


             }
         );
    }


}
