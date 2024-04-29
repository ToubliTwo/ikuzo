<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',)
            ->add('prenom')
            ->add('telephone')
            ->add('email')
            //ne pas afficher le mot de passe
            ->add('password', null, [
                'label' => 'Mot de passe',
                'required' => false,
                'mapped' => false,
            ])
            ->add('pseudo')
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom',
            ])
            //ajouter un champ pour uploader une photo
            ->add('photo', null, [
                'label' => 'Photo',
                'required' => false,
                'mapped' => false,
            ])

            //ajouter un bouton de validation
            ->add('submit', SubmitType::class,
            [
                'label' => 'Enregistrer',
                'attr' => ['class'=> 'submitValider'],
            ])

            //ajouter un bouton d'annulation qui redirige vers la page d'accueil
            ->add('cancel', SubmitType::class, [
                'label' => 'Annuler',
                'attr' => ['class'=>'submitAnnuler', 'style'=>'color=white;'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
