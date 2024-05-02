<?php

namespace App\Command;

use App\Services\ChangementEtat;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateSortieStateCommand extends Command
{
    protected static $defaultName = 'app:update-sortie-state';
    private $changementEtat;

    public function __construct(ChangementEtat $changementEtat)
    {
        parent::__construct();
        $this->changementEtat = $changementEtat;
    }

    protected function configure()
    {
        $this
            ->setDescription('Update sortie state based on date')
            ->setHelp('This command updates the state of sorties based on their dates.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Appel de la méthode de service pour mettre à jour les états des sorties
        $this->changementEtat->modifierEtat();

        $output->writeln('Sortie states updated successfully.');

        return Command::SUCCESS;
    }
}