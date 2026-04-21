<?php

namespace App\Form;

use App\Entity\Progetto;
use App\Entity\ProgettoAzione;
use App\Repository\ProgettoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgettoAzioneType extends AbstractType
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
                    'placeholder' => 'Inserire il nome dell\'azione progetto'
                ]
            ])
            ->add('fkProgetto', EntityType::class, [
                'class' => Progetto::class,
                'expanded' => false,
                'multiple' => false,
                'label' => 'Progetto',
                'choice_label' => function ($f) {
                    return $f->getFkProgettoTipologia()->getDescrizione() . ' | ' . $f->getDescrizione();
                },
                'query_builder' => function (ProgettoRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->leftJoin('p.fkProgettoTipologia', 'pt')
                        ->orderBy('pt.Descrizione', 'ASC')
                        ->addOrderBy('p.Descrizione', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('Inizio', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'label' => 'Inizio',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('Fine', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'label' => 'Fine',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProgettoAzione::class,
        ]);
    }
}
