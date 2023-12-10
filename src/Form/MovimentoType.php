<?php

namespace App\Form;

use App\Entity\Micro;
use App\Entity\Movimento;
use App\Entity\TipoPagamento;
use App\Repository\MicroRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

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
            ->add('Allegati', FileType::class, [
                'label' => 'Allegati (pdf, png, jpg, doc, docx, xls, xlsx, odt, ods, zip)',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'constraints' => [
                    new All([
                        'constraints' => [
                            new File([
                                'maxSize' => '10M',
                                'mimeTypes' => [
                                    'application/pdf',
                                    'application/x-pdf',
                                    'image/jpeg',
                                    'image/png',
                                    'application/vnd.oasis.opendocument.text',
                                    'application/vnd.oasis.opendocument.spreadsheet',
                                    'application/vnd.ms-excel',
                                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                    'application/zip',
                                    'application/msword',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                ],
                                'mimeTypesMessage' => 'Caricare un file valido (solo pdf, png, jpg, doc, docx, xls, xlsx, odt, ods, zip)',
                            ]),
                        ],
                    ]),
                ],
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
