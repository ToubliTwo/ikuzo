<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\NouvelleVilleFormType;
use App\Form\VilleFormType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class VillesController extends AbstractController
{
    private $villeRepository;

    public function __construct(VilleRepository $villeRepository)
    {
        $this->villeRepository = $villeRepository;
    }


    #[Route('/gerer-villes', name: 'gerer_villes')]

    public function gererVilles(Request $request, EntityManagerInterface $entityManager): Response
    {
        $villes = $this->villeRepository->findAll();
        $ville = new Ville();


        $nouvelleVilleForm = $this->createForm(NouvelleVilleFormType::class);

        $form = $this->createForm(VilleFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $request->request->has('clickSurRechercher')) {
            $this->addFlash('success', "");
            $nom = $form->get('nom')->getData();

            $villes = $this->villeRepository->findByCriteriaWithVille($nom);

        }
        $ajouterForm = $this->createForm(NouvelleVilleFormType::class, $ville);
        $ajouterForm->handleRequest($request);

        if ($ajouterForm->isSubmitted() && $ajouterForm->isValid()
            && $request->request->has('clickSurAjouter')) {
            $entityManager->persist($ville);
            $entityManager->flush();
            $this->addFlash('success', "Ville ajouté avec succés");

            return $this->redirectToRoute('gerer_villes');
        }

        return $this->render('villes/gerer_villes.html.twig', [
            'rechercherForm' => $form,
            'villes' => $villes,
            'form' => $nouvelleVilleForm,

        ]);
    }

}