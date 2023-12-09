<?php

namespace App\Form;

use App\Entity\Macro;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MacroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nome', TextType::class, [
                'label' => 'Nome', 
                'label_attr' => ['class' => 'form-label'],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Inserire la macro categoria'
                ]
            ])
            ->add('Invio', CheckboxType::class, [
                'label' => 'Invio al commercialista', 
                'label_attr' => ['class' => 'form-check-label'],
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Macro::class,
        ]);
    }
}
