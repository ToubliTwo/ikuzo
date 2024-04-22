<?php

namespace App\Controller;

use App\Form\ProfilFormType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ParticipantController extends AbstractController
{
    #[Route('/participant/modifier/{id}', name: 'participant_modifier')]
    public function modifier(int $id,
                             ParticipantRepository $participantRepository,
                             EntityManagerInterface $entityManager,
                             Request $request
    ): Response
    {
        $participant = $participantRepository->find($id);

        if (!$participant) {
            throw $this->createNotFoundException('Participant non trouvé');
        }

        $participantForm = $this->createForm(ProfilFormType::class, $participant);
        $participantForm->handleRequest($request);

        if ($participantForm->isSubmitted() && $participantForm->isValid()) {

            $participant->setAdministrateur(false);
            $participant->setActif(true);

            $entityManager->persist($participant);
            $entityManager->flush();

            $this->addFlash('success', 'Profil modifié');
            return $this->redirectToRoute('participant_modifier', ['id' => $participant->getId()]);
        }

        return $this->render('participant/modifier.html.twig', [
        'participant' => $participant,
        'participantForm' => $participantForm
        ]);
    }
}
