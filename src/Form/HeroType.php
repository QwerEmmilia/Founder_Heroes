<?php

namespace App\Form;

use App\Entity\Hero;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HeroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'Name',
                TextType::class,
                [
                    "required" => true
                ])
            ->add('WikiLink',
                TextType::class,
                [
                    "required" => true
                ])
            ->add(
                'age',
                TextType::class,
                [
                    "required" => true
                ])
            ->add('companies', CollectionType::class, [
                    'label' => ' ',
                    'entry_type' => CompanyType::class,
                    'allow_delete' => true,
                    'allow_add' => true,
                    'by_reference' => false,])
            ->add('photo', FileType::class, [
                    'data_class' => null,
                    'label' => 'Hero image',
                    'required' => false
            ])
            ->add("visible", CheckboxType::class, [
                "label" => "make visible",
                "required" => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hero::class,
        ]);
    }
}
