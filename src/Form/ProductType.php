<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [

            ])
            ->add('description', TextareaType::class, [

            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix'
            ])
            ->add('stock', IntegerType::class)
            ->add('isActif', ChoiceType::class, [
                'label' => 'Actif',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ]
            ])
//            ->add('commands')
            ->add('brand', EntityType::class, [
                'label' => 'Marque',
                'class' => Brand::class,
                'choice_label' => 'label',
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (CategoryRepository $category) {
                return $category->createQueryBuilder('c')
                    ->where('c.id > 2');
                },
                'choice_label' => 'label',
                'expanded' => true,
                'multiple' => true,
            ])

            ->add('image', FileType::class, [
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
