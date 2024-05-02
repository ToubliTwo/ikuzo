<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ProfilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'nom',)
            ->add(child: 'prenom')
            ->add(child: 'telephone')
            ->add(child: 'email')
            //ne pas afficher le mot de passe
            ->add(child: 'password', type: null, options: [
                'label' => 'Mot de passe',
                'required' => false,
                'mapped' => false,
            ])
            ->add(child: 'pseudo')
            ->add(child: 'campus', type: EntityType::class, options: [
                'class' => Campus::class,
                'choice_label' => 'nom',
            ])
            //ajouter un champ pour uploader une photo
            ->add(child: 'imageFile', type: VichFileType::class, options: [
                'label' => 'Photo de profil',
                'required' => false,
            ])
            ->add(child: 'imageName', type: null, options: [
                'label' => 'Photo de profil',
                'required' => false,
            ])
            ->add(child: 'imageSize', type: null, options: [
                'label' => 'Photo de profil',
                'required' => false,
            ])

//        //ajouter un bouton de validation
//            ->add(child: 'submit', type: SubmitType::class, options: [
//                'label' => 'Enregistrer',
//            ])
//            //ajouter un bouton d'annulation qui redirige vers la page d'accueil
//            ->add(child: 'cancel', type: SubmitType::class, options: [
//                'label' => 'Annuler',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
