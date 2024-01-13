<?php

namespace App\Form;

use App\Entity\Eseguito;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScadenzaEseguitoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Attivita', TextType::class, [
            'label' => 'Attività', 
            'label_attr' => ['class' => 'form-label'],
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Inserire l\'attività'
            ]
        ])
        ->add('DataScadenza', DateType::class, [
            'widget' => 'single_text',
            'label' => 'Scadenza', 
            'required' => true,
            'attr' => [
                'class' => 'form-control',
            ]
        ]) 
        ->add('DataEseguito', DateType::class, [
            'widget' => 'single_text',
            'label' => 'Fatto il', 
            'required' => true,
            'attr' => [
                'class' => 'form-control',
            ]
        ])
        ->add('Differisci', ChoiceType::class, [
            'label' => 'Differisci la scadenza originale', 
            'required' => true,
            'choices'  => [
                'Settimana' => 'Settimana',
                'Mese' => 'Mese',
                'Semestre' => 'Semestre',
                'Anno' => 'Anno',
                'Biennio' => 'Biennio',
                'Decennio' => 'Decennio',
                ],
                "mapped" => false,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Eseguito::class,
        ]);
    }
}
