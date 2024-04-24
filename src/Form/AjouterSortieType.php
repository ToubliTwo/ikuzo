<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sorties;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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

            ->add('dateLimiteInscription', DateTimeType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'label' => 'Date limite d\'inscription :',
                'input' => 'datetime_immutable',
                'required'=> false
            ])

            ->add('description', TextareaType::class,
                ['label' => 'Description et infos : ',
                    'attr' => ['placeholder' => 'Ajoutez une description à votre évènement !']
                    ,'required'=> false
                ])


            ->add('ville', EntityType::class, [
                'choice_label' => 'nom',
                'class' => Ville::class,
                'mapped' => false, //pour indiquer que l'attribut n'existe pas dans l'entité Sortie
            ])

            ->add('lieu',EntityType::class, [
                'choice_label' => 'nom',
                'required'=> false,
                'class' => Lieu::class,
            ])

            ->add('latitude',EntityType::class, [
            'choice_label' => 'latitude',
            'required'=> false,
            'class' => Lieu::class,
                'mapped' => false,
        ])

            ->add('longitude',EntityType::class, [
            'choice_label' => 'longitude',
            'required'=> false,
            'class' => Lieu::class,
                'mapped' => false,
        ]);
        }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}

