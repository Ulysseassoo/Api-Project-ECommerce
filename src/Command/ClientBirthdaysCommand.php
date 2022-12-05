<?php

namespace App\Command;

use App\Service\ClientManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:clients-birthday-command',
    description: 'Send email to clients if it is their birthday.',
)]
class ClientBirthdaysCommand extends Command
{
    private ClientManager $clientManager;

    public function __construct(ClientManager $clientManager)
    {
        // best practices recommend to call the parent constructor first and
        // then set your own properties. That wouldn't work in this case
        // because configure() needs the properties set in this constructor
        $this->clientManager = $clientManager;

        parent::__construct(null);
    }

    protected function configure(): void
    {
        $this
        // If you don't like using the $defaultDescription static property,
        // you can also define the short description using this method:
        // ->setDescription('...')

        // the command help shown when running the command with the "--help" option
        ->setHelp('This command sends email to clients to wish them an happy birthday');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Sending emails',
            '...',
            '....',
            '.....',
        ]);

        $clients = $this->clientManager->wishBirthday();

        $output->writeln(sprintf('Happy Birthday emails have been send to %s clients', $clients));
        
        $io = new SymfonyStyle($input, $output);
        
        $io->success('The emails have been sent');

        return Command::SUCCESS;
    }
}
