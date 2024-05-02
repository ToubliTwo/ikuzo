<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class TestMailController extends AbstractController
{
    public function sendTestMail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('eni-projets@hotmail.com')
            ->to('dev.mj@hotmail.com')
            ->subject('Test d\'envoi d\'email depuis Symfony')
            ->text('Ceci est un email de test envoyé depuis Symfony.');

        $mailer->send($email);

        return new Response('Email de test envoyé !');
    }
}
