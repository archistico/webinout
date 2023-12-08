<?php

namespace App\Form;

use App\Entity\TipoPagamento;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TipoPagamentoType extends AbstractType
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
                'placeholder' => 'Inserire il tipo di pagamento (es. Banca, Bancomat, ...)'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TipoPagamento::class,
        ]);
    }
}
