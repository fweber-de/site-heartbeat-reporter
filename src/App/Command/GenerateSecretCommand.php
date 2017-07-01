<?php

namespace App\Command;

use App\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
final class GenerateSecretCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:generate:secret')
            ->setDescription('Generarets a secure string')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $secret = $this->get('app.secure_string')->generate();

        $io->writeln($secret);
    }
}
