<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Etat;
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

class ModifierSortieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Nom de la sortie :',
                'attr' => ['placeholder' => 'modifiez le titre ']
                ,'required'=> false
            ])

            ->add('date', DateTimeType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'label' => 'Modifiez la date et l\'heure :',
//                'input' => 'datetime_immutable',
                'required'=> false
            ])

            ->add('nombreDePlaces', TextType::class, [
                'label' => 'Nombre de places :'
                ,'required'=> false
            ])

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
//                'input' => 'datetime_immutable',
                'required'=> false
            ])

            ->add('description', TextareaType::class,
                ['label' => 'Description et infos : ',
                    'attr' => ['placeholder' => 'Modifiez la description de votre évènement !']
                    ,'required'=> false
                ])

            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom',
                'label' => 'Campus :',
            ])

            ->add('etat', EntityType::class, [
                'class' => Etat::class,
                'choice_label' => 'libelle',
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

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}
