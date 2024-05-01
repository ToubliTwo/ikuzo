<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sorties;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifierSortieFormType extends AbstractType
{
    private $lieuRepository;

    public function __construct(LieuRepository $lieuRepository)
    {
        $this->lieuRepository = $lieuRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'titre', type: TextType::class, options: [
                'label' => 'Nom de la sortie :',
                'attr' => ['placeholder' => 'Donnez un titire à votre évènement !']
                ,'required'=> false
            ])

            ->add(child: 'nombreDePlaces', type: TextType::class, options: [
                'label' => 'Nombre de places :'
                ,'required'=> false
            ])

            ->add(child: 'duree', type: TimeType::class, options: [
                'html5' => true,
                'widget' => 'single_text',
                'label' => 'Durée de l\'évènement : ',
                'attr' => [
                    'placeholder' => 'hh:mm'
                ]
            ])

            ->add('date', DateTimeType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'label' => 'Date et heure de la sortie :',
                'input' => 'datetime_immutable',
                'required' => false,
            ])

            ->add('dateLimiteInscription', DateTimeType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'label' => 'Date limite d\'inscription :',
                'input' => 'datetime_immutable',
                'required' => false,
            ])

            ->add(child: 'description', type: TextareaType::class, options: [
                'label' => 'Description et infos : ',
                'attr' => ['placeholder' => 'Ajoutez une description à votre évènement !']
                ,'required'=> false
            ])


            ->add(child: 'campus', type: EntityType::class, options: [
                'class' => Campus::class,
                'choice_label' => 'nom',
                'label' => 'Campus :',
            ])


            ->add(child: 'ville', type: EntityType::class, options: [
                'choice_label' => 'nom',
                'class' => Ville::class,
                'mapped' => false, //pour indiquer que l'attribut n'existe pas dans l'entité Sortie
            ])

            ->add(child: 'lieu',type: EntityType::class, options: [
                'choice_label' => 'nom',
                'required'=> false,
                'class' => Lieu::class,
            ])


            ->add(child: 'latitude',type: TextType::class, options: [
                'required'=> false,
                'mapped' => false,
                'disabled' => true,
            ])

            ->add(child: 'longitude',type: TextType::class, options: [
                'required'=> false,
                'mapped' => false,
                'disabled' => true,
            ])

            ->add(child: 'etat', type: EntityType::class, options: [
                'class' => Etat::class,
                'choice_label' => 'libelle',
            ])->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder) {
                $form = $event->getForm();

                // Ajouter le champ "lieu" dynamiquement
                $form->add('lieu', EntityType::class, [
                    'class' => Lieu::class,
                    'choice_label' => 'nom',
                    'label' => 'Lieu :',
                    'placeholder' => 'Sélectionnez un lieu',
                    'required' => false,
                    'query_builder' => function (LieuRepository $lieuRepository) {
                        return $lieuRepository->findByVilleQueryBuilder('1');
                    },
                ]);

            });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($builder) {
            $form = $event->getForm();
            $data = $event->getData();
            $villeId = $data['ville'] ?? null;

            if ($villeId) {
                $lieux = $this->lieuRepository->findBy(['ville' => $villeId]);

                $form->add('lieu', EntityType::class, [
                    'class' => Lieu::class,
                    'choice_label' => 'nom',
                    'label' => 'Lieu :',
                    'placeholder' => 'Sélectionnez un lieu',
                    'required' => false,
                    'choices' => $lieux,
                ]);
            }
        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}
