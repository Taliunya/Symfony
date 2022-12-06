<?php

namespace App\Form;

use App\Entity\Shop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SearchShopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'empty_data' => '',
            ])
            ->add('price', MoneyType::class, options: [
                'label' => 'Price for 1 kg',
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'html5' => false,
            ])
            ->add('order', ChoiceType::class, [
                'label' => 'Min/Max Price',
                'choices' => [
                    'Min Price' => 'min',
                    'Max Price' => 'max'
                ],
                'attr' => ['class' => 'form-control'],
                'mapped' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Search',
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
