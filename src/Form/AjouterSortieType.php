<?php

namespace App\Form;

use App\Entity\Sorties;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AjouterSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Nom de la sortie :',
                'attr' => ['placeholder' => 'Donnez un titire à votre évènement !']
                ,'required'=> false
            ])

            ->add('date', DateTimeType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'label' => 'Date et heure de la sortie :',
                'input' => 'datetime_immutable',
                'required'=> false
            ])

            ->add('nombreDePlaces', TextType::class, ['label' => 'Nombre de places :' ,'required'=> false])

            ->add('duree', TimeType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'label' => 'Durée de l\'évènement : ',
                'attr' => [
                    'placeholder' => 'hh:mm'
                ]

            ])

            ->add('description', TextareaType::class,
                ['label' => 'Description et infos : ',
                    'attr' => ['placeholder' => 'Ajoutez une description à votre évènement !']
                    ,'required'=> false
                ])

            ->add('ville', TextType::class, ['label' => 'Ville : ','required'=> false])

            ->add('lieu',TextType::class, ['label' => 'Lieu : ','required'=> false])

            ->add('rue', TextType::class, ['label' => 'Rue : ','required'=> false])

            ->add('codePostal', TextType::class, ['label' => 'Code Postal : ','required'=> false])

            ->add('latitude', TextType::class, ['label' => 'Latitude', 'required' => false])

            ->add('longitude', TextType::class, ['label' => 'Longitude', 'required' => false]);

    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}

