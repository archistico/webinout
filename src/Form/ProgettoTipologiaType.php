<?php

namespace App\Form;

use App\Entity\ProgettoTipologia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgettoTipologiaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Descrizione', TextType::class, [
                'label' => 'Descrizione',
                'label_attr' => ['class' => 'form-label'],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Inserire la tipologia progetto'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProgettoTipologia::class,
        ]);
    }
}
