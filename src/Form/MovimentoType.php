<?php

namespace App\Form;

use App\Entity\Micro;
use App\Entity\Meso;
use App\Entity\Movimento;
use App\Entity\TipoPagamento;
use App\Repository\MesoRepository;
use App\Repository\MicroRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\Query\Expr\Join;


class MovimentoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Data', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Data', 
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
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
            ->add('Importo', NumberType::class, [
                'label' => 'Importo', 
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '0,00 €'
                ]
            ])
            ->add('Tipo', EntityType::class, [
                'class' => TipoPagamento::class,
                'choice_label' => 'Descrizione',
            ])
            ->add('Note')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movimento::class,
        ]);
    }
}
