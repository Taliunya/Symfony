<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Shop;
use App\Entity\ShopProduct;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ShopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'empty_data' => '',
                'constraints' => [
                    new NotBlank([
                        'message' => 'This field is required'
                    ]),
                ]
            ])
            ->add('count', IntegerType::class, [
                'label' => 'Count',
                'attr' => ['class' => 'form-control'],
                'empty_data' => '',
                'required' => true,
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Price for 1 kg',
                'help' => '* Price for 1 kg',
                'attr' => ['class' => 'form-control'],
                'html5' => false,
            ])
            ->add('city', EntityType::class, [
                'placeholder' => 'Choose origin',
                'class' => City::class,
                'attr' => ['class' => 'form-control'],
                'choice_value' => 'id',
            ])
            ->add('shop_products', EntityType::class, [
                'class' => ShopProduct::class,
                'choice_value' => 'product.product_number',
                'choice_label' => 'product.product_number',
                'multiple' => true
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-primary mb-2']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Shop::class,
        ]);
    }
}
