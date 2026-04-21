<?php

namespace App\Form;

use App\Entity\Progetto;
use App\Entity\ProgettoTipologia;
use App\Repository\ProgettoTipologiaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgettoType extends AbstractType
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
                    'placeholder' => 'Inserire il nome del progetto'
                ]
            ])
            ->add('fkProgettoTipologia', EntityType::class, [
                'class' => ProgettoTipologia::class,
                'expanded' => false,
                'multiple' => false,
                'label' => 'Tipologia progetto',
                'choice_label' => 'Descrizione',
                'query_builder' => function (ProgettoTipologiaRepository $er) {
                    return $er->createQueryBuilder('pt')
                        ->orderBy('pt.Descrizione', 'ASC');
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
            'data_class' => Progetto::class,
        ]);
    }
}
