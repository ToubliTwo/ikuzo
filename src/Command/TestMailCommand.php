<?php
// src/Command/TestMailCommand.php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class TestMailCommand extends Command
{
    // Le nom de votre commande (app:test-mail)
    protected static $defaultName = 'app:test-mail';

    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Envoie un e-mail de test depuis Symfony.')
            ->setHelp('Cette commande envoie un e-mail de test depuis Symfony.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Envoi d\'un e-mail de test...');

        $email = (new Email())
            ->from('eni-projets@hotmail.com')
            ->to('dev.mj@hotmail.com')
            ->subject('Test d\'envoi d\'e-mail depuis Symfony')
            ->text('Ceci est un e-mail de test envoyé depuis Symfony.');

        $this->mailer->send($email);

        $output->writeln('E-mail de test envoyé !');

        return Command::SUCCESS;
    }
}