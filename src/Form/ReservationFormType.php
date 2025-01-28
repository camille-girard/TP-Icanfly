<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Mission;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('seatCount', IntegerType::class, [
                'label' => 'Nombre de places',
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]',
                    'oninput' => 'updateTotalPrice()',
                ],
            ])
            ->add('totalPrice', IntegerType::class, [
                'label' => 'Prix total (€)',
                'disabled' => true,
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ])
            ->add('mission', EntityType::class, [
                'class' => Mission::class,
                'choice_label' => 'destination',
                'label' => 'Mission',
                'placeholder' => 'Choisissez une mission',
                'data' => $options['data']->getMission() ?? null,
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]',
                    'onchange' => 'updateTotalPrice()',
                ],
            ]);

        if ($options['is_admin']) {
            $builder->add('customer', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'label' => 'Client',
                'placeholder' => 'Choisissez un client',
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ]);
        } else {
            $builder->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'mapped' => false,
                'data' => $options['email'],
                'disabled' => true,
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ]);
        }

        if ($options['is_new'] && !$options['is_admin']) {
            $builder->add('save_and_pay', SubmitType::class, [
                'label' => 'Enregistrer et Payer',
                'attr' => [
                    'class' => 'mt-6 px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors',
                ],
            ]);
        }

        $builder->add('save', SubmitType::class, [
            'label' => 'Enregistrer',
            'attr' => [
                'class' => 'mt-6 px-4 py-2 bg-[#0022FF] text-white rounded-md hover:bg-blue-600 transition-colors',
            ],
        ]);

        if ($options['created_at']) {
            $builder->add('createdAt', TextType::class, [
                'label' => 'Date de création',
                'mapped' => false,
                'disabled' => true,
                'data' => $options['created_at'],
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'created_at' => null,
            'email' => null,
            'is_admin' => false,
            'is_new' => false, // Par défaut, ce n'est pas une nouvelle réservation
        ]);
    }
}
