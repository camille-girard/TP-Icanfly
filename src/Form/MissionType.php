<?php

namespace App\Form;

use App\Entity\Mission;
use App\Entity\SpaceShip;
use App\Entity\User;
use App\Entity\VideoStreaming;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'attr' => [
                    'class' => 'form-input block w-full rounded-lg border-2 border-gray-400 bg-gray-50 px-4 py-3 focus:ring-4 focus:ring-[#002EFF] focus:border-[#002EFF]',
                    'placeholder' => 'Nom de la mission',
                ],
            ])
            ->add('description', null, [
                'attr' => [
                    'class' => 'form-input block w-full rounded-lg border-2 border-gray-400 bg-gray-50 px-4 py-3 focus:ring-4 focus:ring-[#002EFF] focus:border-[#002EFF]',
                    'placeholder' => 'Description détaillée',
                ],
            ])
            ->add('destination', null, [
                'attr' => [
                    'class' => 'form-input block w-full rounded-lg border-2 border-gray-400 bg-gray-50 px-4 py-3 focus:ring-4 focus:ring-[#002EFF] focus:border-[#002EFF]',
                    'placeholder' => 'Destination',
                ],
            ])
            ->add('launchDate', null, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-input block w-full rounded-lg border-2 border-gray-400 bg-gray-50 px-4 py-3 focus:ring-4 focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ])
            ->add('seatPrice', null, [
                'attr' => [
                    'class' => 'form-input block w-full rounded-lg border-2 border-gray-400 bg-gray-50 px-4 py-3 focus:ring-4 focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ])
            ->add('duration', null, [
                'attr' => [
                    'class' => 'form-input block w-full rounded-lg border-2 border-gray-400 bg-gray-50 px-4 py-3 focus:ring-4 focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ])
            ->add('createdAt', null, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-input block w-full rounded-lg border-2 border-gray-400 bg-gray-50 px-4 py-3 focus:ring-4 focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ])
            ->add('status', null, [
                'attr' => [
                    'class' => 'form-input block w-full rounded-lg border-2 border-gray-400 bg-gray-50 px-4 py-3 focus:ring-4 focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ])
            ->add('participants', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
                'multiple' => true,
                'attr' => [
                    'class' => 'form-multiselect block w-full rounded-lg border-2 border-gray-400 bg-gray-50 px-4 py-3 focus:ring-4 focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ])
            ->add('videoStreaming', EntityType::class, [
                'class' => VideoStreaming::class,
                'choice_label' => 'id',
                'attr' => [
                    'class' => 'form-select block w-full rounded-lg border-2 border-gray-400 bg-gray-50 px-4 py-3 focus:ring-4 focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ])
            ->add('spaceship', EntityType::class, [
                'class' => SpaceShip::class,
                'choice_label' => 'id',
                'attr' => [
                    'class' => 'form-select block w-full rounded-lg border-2 border-gray-400 bg-gray-50 px-4 py-3 focus:ring-4 focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
