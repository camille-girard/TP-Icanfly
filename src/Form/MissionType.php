<?php

namespace App\Form;

use App\Entity\Mission;
use App\Entity\ScientificMission;
use App\Entity\TouristMission;
use App\Entity\Spaceship;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


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
                'mapped' => false, // Non lié à l'entité directement
                'attr' => ['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]']
            ])
            ->add('destination', null, [
                'label' => 'Destination',
                'attr' => ['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]']
            ])
            ->add('description', null, [
                'label' => 'Description',
                'attr' => ['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]']
            ])
            ->add('date', null, [
                'label' => 'Date',
                'widget' => 'single_text',
                'attr' => ['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]']
            ])
            ->add('seatPrice', null, [
                'label' => 'Prix par place (€)',
                'attr' => ['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]']
            ])
            ->add('image', null, [
                'label' => 'Image',
                'attr' => ['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]']
            ])
            ->add('duration', null, [
                'label' => 'Durée',
                'widget' => 'single_text',
                'attr' => ['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]']
            ])
            ->add('spaceships', EntityType::class, [
                'class' => Spaceship::class,
                'choice_label' => 'name',
                'multiple' => true,
                'label' => 'Vaisseaux',
                'attr' => ['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#002EFF] focus:border-[#002EFF]']
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            if (isset($data['type'])) {
                if ($data['type'] === 'scientific') {
                    $form
                        ->add('specialEquipement', TextType::class, [
                            'label' => 'Équipement Spécial',
                        ])
                        ->add('objective', TextType::class, [
                            'label' => 'Objectif',
                        ]);
                } elseif ($data['type'] === 'travel') {
                    $form
                        ->add('hasGuide', CheckboxType::class, [
                            'label' => 'Guide inclus ?',
                            'required' => false,
                        ])
                        ->add('activities', TextType::class, [
                            'label' => 'Activités',
                        ]);
                }
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
