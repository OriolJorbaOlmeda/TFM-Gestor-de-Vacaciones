<?php

namespace App\Modules\Calendar\Infrastucture\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class SearchByDepartmentType extends AbstractType
{
    public function __construct(private TranslatorInterface $translator) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('department', ChoiceType::class, [
                'choices' => $options['data'],
                'label' => $this->translator->trans('calendarDashboard.department')
            ]);

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $usersName = [];
                $formOptions = [
                    'choices' => $usersName,
                    'attr' => [
                        'class' => 'form-control select2',
                        'disabled' => 'disabled'
                    ],
                    'label' => $this->translator->trans('calendarDashboard.user')
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
