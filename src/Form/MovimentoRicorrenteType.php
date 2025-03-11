<?php

namespace App\Form;

use App\Entity\Micro;
use App\Entity\MovimentoRicorrente;
use App\Entity\TipoPagamento;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\Repository\MicroRepository;

class MovimentoRicorrenteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Descrizione')
            ->add('Importo', NumberType::class, [
                'label' => 'Importo', 
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '0,00 €'
                ]
            ])
            ->add('Inizio', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Inizio', 
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('Fine', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Fine', 
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('Frequenza', ChoiceType::class, [
                'choices' => [
                    'Mensile' => 'Mensile',
                    'Settimanale' => 'Settimanale',
                    'Annuale' => 'Annuale',
                ],
            ])
            ->add('GiornoPagamento', NumberType::class, [
                'label' => 'Giorno del pagamento (1-31)', 
                'attr' => [
                    'min' => 1,
                    'max' => 31,
                ],
            ])
            ->add('Attivo', CheckboxType::class, [
                'label' => 'Attivo', 
                'label_attr' => ['class' => 'form-check-label mt-5'],
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ]
            ])
            ->add('Categoria', EntityType::class, [
                'class' => Micro::class,
                'expanded' => false,
                'multiple' => false,
                'label' => 'Categoria',                
                'choice_label' => function ($f) {
                    return $f->getPadre()->getPadre()->getNome() . " | " . $f->getPadre()->getNome() . " | " . $f->getNome() ;
                },
                'query_builder' => function (MicroRepository $er) {
                    return $er->createQueryBuilder('mi')
                        ->leftJoin('mi.Padre', 'me')
                        ->leftJoin('me.Padre', 'ma')
                        ->OrderBy('ma.Nome', 'ASC')
                        ->addOrderBy('me.Nome', 'ASC')
                        ->addOrderBy('mi.Nome', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('Tipo', EntityType::class, [
                'class' => TipoPagamento::class,
                'choice_label' => 'Descrizione',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MovimentoRicorrente::class,
        ]);
    }
}
