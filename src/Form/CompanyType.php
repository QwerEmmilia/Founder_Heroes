<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    "required" => true
                ])

            ->add(
                'area',
                ChoiceType::class,
                [
                    "required" => true,
                    'choices'  => [
                        'Landwirtschaft' => 'Landwirtschaft',
                        'Handel' => 'Handel',
                        'Bauindustrie' => 'Bauindustrie',
                        'Biotech' => 'Biotech',
                        'Dienstleistung' => 'Dienstleistung',
                        'Energie' => 'Energie',
                        'Finanzmärkte' => 'Finanzmärkte',
                        'Forschung' => 'Forschung',
                        'Fotografie' => 'Fotografie',
                        'Gesundheit' => 'Gesundheit',
                        'Nuklear' => 'Nuklear',
                        'Recycling' => 'Recycling',
                        'Kunst' => 'Kunst',
                        'Rohstoffe' => 'Rohstoffe',
                        'Luftfahrt' => 'Luftfahrt',
                        'Nachrichten' => 'Nachrichten',
                        'Kino' => 'Kino',
                        'Nanotech' => 'Nanotech',
                        'IT' => 'IT',
                        'Sportarten' => 'Sportarten',
                        'Raum' => 'Raum',
                        'Mode' => 'Mode',
                        'Marketing' => 'Marketing',
                        'Automobile' => 'Automobile',
                        ]
                ])

            ->add(
                'city',
                TextType::class,
                [
                    "required" => true
                ])

            ->add(
                'region',
                TextType::class,
                [
                    "required" => true
                ])

            ->add(
                'country',
                TextType::class,
                [
                    "required" => true
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
