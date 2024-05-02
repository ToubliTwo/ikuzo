<?php

namespace App\EventListener;

use App\Entity\Sorties;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EnvoiMailPourAnnulation
{
    protected MailerInterface $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendNewUserNotificationToAdmin(string $motifAnnulation, Sorties $sortie) : void
    {
        // Récupérer le nom de l'organisateur et la date de la sortie
        $organisateur = $sortie->getOrganisateur(); // Supposons que le nom complet de l'organisateur soit récupéré à partir de la méthode getNomComplet()
        $dateSortie = $sortie->getDate()->format('d/m/Y'); // Supposons que la date de la sortie soit récupérée au format jj/mm/aaaa

        // Créer l'e-mail
        $message = new Email();
        $message->from('eni-projets@hotmail.com') // Adresse e-mail de l'expéditeur
        ->to('symfonyeniprojet@gmail.com') // Adresse e-mail du destinataire
        ->subject('Annulation d\'une sortie !')
            ->html('
                <html>
                    <head>
                        <style>
                            /* Styles CSS */
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f4f4f4;
                                padding: 20px;
                            }
                            .container {
                                max-width: 600px;
                                margin: 0 auto;
                                background-color: #fff;
                                padding: 40px;
                                border-radius: 10px;
                                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                            }
                            h1 {
                                color: #333;
                            }
                            p {
                                color: #666;
                                line-height: 1.6;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <h1>Annulation d\'une sortie :</h1>
                            <p>Bonjour,</p>
                            <p>Nous vous informons que la sortie prévue le '.$dateSortie.' organisée par '.$organisateur.' est annulée pour le motif suivant :</p>
                            <p>'.$motifAnnulation.'</p>
                            <p>Veuillez la supprimer via le panel.</p>
                            <p>Cordialement,</p>
                            <p>Votre équipe de gestion des sorties</p>
                        </div>
                    </body>
                </html>
            ');

        $this->mailer->send($message);
    }
}