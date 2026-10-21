<?php

namespace App\Form;

use App\Entity\Assunto;
use App\Entity\Autor;
use App\Entity\Livro;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SortDirection;

class LivroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo', TextType::class, [
                'label' => 'Título',
                'attr' => ['maxlength' => 40],
            ])
            ->add('editora', TextType::class, [
                'label' => 'Editora',
                'attr' => ['maxlength' => 40],
            ])
            ->add('edicao', IntegerType::class, [
                'label' => 'Edição',
                'attr' => ['min' => 1],
            ])
            ->add('anoPublicacao', TextType::class, [
                'label' => 'Ano de publicação',
                'attr' => [
                    'maxlength' => 4,
                    'inputmode' => 'numeric',
                    'pattern' => '\d{4}',
                ],
            ])
            ->add('valor', NumberType::class, [
                'label' => 'Valor (R$)',
                'input' => 'string',
                'scale' => 2,
                'html5' => false,
                'attr' => ['min' => '0.01'],
            ])
            ->add('autores', EntityType::class, [
                'class' => Autor::class,
                'choice_label' => 'nome',
                'label' => 'Autores',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'query_builder' => static fn (EntityRepository $repository) =>
                    $repository->createQueryBuilder('autor')->orderBy('autor.nome', SortDirection::Ascending),
            ])
            ->add('assuntos', EntityType::class, [
                'class' => Assunto::class,
                'choice_label' => 'descricao',
                'label' => 'Assuntos',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'query_builder' => static fn (EntityRepository $repository) =>
                    $repository->createQueryBuilder('assunto')->orderBy('assunto.descricao', SortDirection::Ascending),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livro::class,
        ]);
    }
}
