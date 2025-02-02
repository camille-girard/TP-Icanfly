<?php

namespace App\Form;

use App\Entity\Mission;
use App\Entity\Spaceship;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'Type de Mission',
                'choices' => [
                    'Scientifique' => 'scientific',
                    'Touristique' => 'travel',
                ],
                'mapped' => false,
                'data' => $options['current_type'] ?? null,
                'attr' => [
                    'class' => 'type-selector mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ])
            ->add('destination', null, [
                'label' => 'Destination',
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ])
            ->add('description', null, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ])
            ->add('date', null, [
                'label' => 'Date',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ])
            ->add('seatPrice', null, [
                'label' => 'Prix par place (€)',
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ])
            ->add('operator', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'required' => true,
            ])
            ->add('image', null, [
                'label' => 'Image',
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ])
            ->add('duration', null, [
                'label' => 'Durée',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ])
            ->add('spaceships', EntityType::class, [
                'class' => Spaceship::class,
                'choice_label' => 'name',
                'multiple' => true,
                'label' => 'Vaisseaux',
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ])
            ->add('specialEquipement', TextType::class, [
                'label' => 'Équipement Spécial',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'scientific-fields mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ])
            ->add('objective', TextType::class, [
                'label' => 'Objectif',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'scientific-fields mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ])
            ->add('hasGuide', CheckboxType::class, [
                'label' => 'Guide inclus ?',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'travel-fields mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ])
            ->add('activities', TextType::class, [
                'label' => 'Activités',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'travel-fields mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
            'current_type' => null, // Used to determine the mission type for existing entities
        ]);
    }
}
