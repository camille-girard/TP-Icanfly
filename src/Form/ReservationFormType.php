<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\User;
use App\Entity\Mission;
use App\Enum\BookingStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\CallbackTransformer;

class ReservationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('destination', TextType::class, [
                'label' => 'Destination',
                'attr' => ['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]']
            ])
            ->add('seatCount', IntegerType::class, [
                'label' => 'Nombre de places',
                'attr' => ['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]']
            ])
            ->add('totalPrice', IntegerType::class, [
                'label' => 'Prix total (€)',
                'attr' => ['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]']
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Confirmé' => BookingStatus::CONFIRMED->value,
                    'En attente' => BookingStatus::PENDING->value,
                    'Annulé' => BookingStatus::CANCELLED->value,
                ],
                'label' => 'Statut',
                'attr' => ['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]']
            ])
            ->add('customer', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'label' => 'Client',
                'attr' => ['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]']
            ])
            ->add('mission', EntityType::class, [
                'class' => Mission::class,
                'choice_label' => function (Mission $mission) {
                    return $mission->getDestination();
                },
                'label' => 'Mission',
                'attr' => ['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'bg-[#002EFF] text-white px-4 py-2 rounded hover:bg-[#001FCC] mt-6'
                ]
            ]);

        $builder->get('status')->addModelTransformer(new CallbackTransformer(
            function ($status) {
                return $status instanceof BookingStatus ? $status->value : null;
            },
            function ($status) {
                return BookingStatus::from($status);
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
