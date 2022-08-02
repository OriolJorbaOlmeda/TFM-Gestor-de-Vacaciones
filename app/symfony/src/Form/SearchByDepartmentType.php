<?php

namespace App\Form;

use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchByDepartmentType extends AbstractType
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
                ChoiceType::class, ['choices' => $options['data']]
            );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $usersName = [];
                $formOptions = [
                    'choices' => $usersName,
                    'attr' => [
                        'class' => 'form-control select2',
                        'disabled' => 'disabled',
                    ]
                ];
                $form->add('user', ChoiceType::class, $formOptions);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
