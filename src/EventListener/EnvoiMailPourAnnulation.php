<?php

namespace App\EventListener;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EnvoiMailPourAnnulation
{
    protected MailerInterface $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendNewUserNotificationToAdmin(string $motifAnnulation) : void
    {
        //file_put_contents('debug.txt', $user->getEmail() . ' has been registered');
        $message = new Email();
        $message->from('acounts@sorties.com') // l'adresse mail de l'expéditeur
            ->to('admin@sorties.com') // l'adresse mail du destinataire
            ->subject('Annulation d\'une sortie !')
            ->html('<h1>Annulation d\'une sortie :</h1><br>'
         .'<p>'.$motifAnnulation.'<p>');

        $this->mailer->send($message);
    }

}