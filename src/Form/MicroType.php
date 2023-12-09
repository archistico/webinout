<?php

namespace App\Form;

use App\Entity\Macro;
use App\Entity\Meso;
use App\Entity\Micro;
use App\Repository\MacroRepository;
use App\Repository\MesoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MicroType extends AbstractType
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
            ->add('Padre', EntityType::class, [
                'class' => Meso::class,
                'expanded' => false,
                'multiple' => false,
                'label' => 'Macro | Meso',
                'choice_label' => function ($f) {
                    return $f->getPadre()->getNome() . " | " . $f->getNome() ;
                },
                'query_builder' => function (MesoRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.Nome', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Micro::class,
        ]);
    }
}
