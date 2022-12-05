<?php

namespace App\Command;

use App\Service\CartManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:cart-expired-command',
    description: 'Delete carts from one week.',
)]
class CartExpiredCommand extends Command
{
    private CartManager $cartManager;

    public function __construct(CartManager $cartManager)
    {
        // best practices recommend to call the parent constructor first and
        // then set your own properties. That wouldn't work in this case
        // because configure() needs the properties set in this constructor
        $this->cartManager = $cartManager;

        parent::__construct(null);
    }

    protected function configure(): void
    {
        $this
        // If you don't like using the $defaultDescription static property,
        // you can also define the short description using this method:
        // ->setDescription('...')

        // the command help shown when running the command with the "--help" option
        ->setHelp('This command checks if the carts creation date are expired from one week..');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Checking Carts',
            '...',
            '....',
            '.....',
        ]);

        $carts = $this->cartManager->deleteExpiredCarts();

        $io = new SymfonyStyle($input, $output);
        
        $io->success('The expired carts have been deleted.');

        return Command::SUCCESS;
    }
}
