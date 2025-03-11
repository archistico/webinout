<?php

namespace App\Command;

use App\Service\MovimentoRicorrenteService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:processa-ricorrenti',
    description: 'Processa i movimenti ricorrenti e aggiungi il pagamento',
    hidden: false,
    aliases: ['app:processa'],
)]
class ProcessaRicorrentiCommand extends Command
{
    public function __construct(private MovimentoRicorrenteService $movimentoRicorrenteService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->movimentoRicorrenteService->processaMovimentoRicorrente();
        $io->success('I movimenti ricorrenti sono stati processati.');

        return Command::SUCCESS;
    }
}
