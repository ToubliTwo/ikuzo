<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Sorties;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheSortieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', null, [
                'label' => 'Le nom de la sortie contient :',
                'required' => false,
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom',
                'placeholder' => 'Sélectionnez un campus',
                'required' => false,
            ])
            ->add('dateRechercheDebut', DateType::class, [
                'label' => 'Entre',
                'widget' => 'single_text',
                'mapped' => false,
                'required' => false,
            ])
            ->add('dateRechercheFin', DateType::class, [
                'label' => ' Et',
                'widget' => 'single_text',
                'mapped' => false,
                'required' => false,

            ])
            // Ajout de la case à cocher pour les sorties dont vous êtes l'organisateur/trice
            ->add('organisateur', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false,
                'mapped' => false,
            ])
            // Ajout de la case à cocher pour les sorties auxquelles vous êtes inscrit/e
            ->add('inscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false,
                'mapped' => false,
            ])
            ->add('pasInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,
                'mapped' => false,
            ])
            ->add('sortiesPassees', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false,
                'mapped' => false,
            ])

            ->add("submit", SubmitType::class, [
                'label' => 'Rechercher'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}
