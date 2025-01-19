<?php

namespace App\Form;

use App\Entity\Mission;
use App\Entity\Spaceship;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
