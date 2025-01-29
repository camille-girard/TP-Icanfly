<?php

namespace App\Form;

use App\Entity\VideoStreaming;
use App\Entity\Mission;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoStreamingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', TextType::class, [
                'label' => 'URL du Live',
                'attr' => ['class' => 'form-control']
            ])
            ->add('startDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'attr' => ['class' => 'form-control']
            ])
            ->add('endDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
                'attr' => ['class' => 'form-control']
            ])
            ->add('mission', EntityType::class, [
                'class' => Mission::class,
                'choice_label' => 'destination',
                'label' => 'Mission associée',
                'attr' => ['class' => 'form-control']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'mt-6 px-4 py-2 bg-[#0022FF] text-white rounded-md hover:bg-blue-600 transition-colors',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VideoStreaming::class,
        ]);
    }
}
