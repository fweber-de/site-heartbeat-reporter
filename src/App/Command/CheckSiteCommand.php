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
final class CheckSiteCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:check:site')
            ->setDescription('Checks site availability')
            ->addArgument('site_key', InputArgument::REQUIRED, 'key of monitored site')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $site = $this->get('app.updater')->getSite($input->getArgument('site_key'));

        if (!$site) {
            throw new \Exception('site not found');
        }

        $lastContact = $this->get('app.updater')->getLastContactOfSite($site);
        $lastNofified = $this->get('app.updater')->getLastNotificationDate($site);
        $diff = (new \DateTime())->getTimestamp() - $lastContact->getTimestamp();
        $lastType = $this->get('app.updater')->getLastNotificationType($site);

        $io->writeln($lastType);

        if ($diff < $site->getDiff()) {
            if ($lastType != 'online') {
                $this->get('app.notifier')->siteOnline($site);
            }

            $io->success(sprintf('site %s online', $site->getTitle()));
        } else {
            if ($lastType != 'offline') {
                $this->get('app.notifier')->siteOffline($site, $diff);
            }

            $io->error(sprintf('site %s offline > %s seconds', $site->getTitle(), $site->getDiff()));
        }
    }
}
