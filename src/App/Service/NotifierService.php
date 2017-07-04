<?php

namespace App\Service;

use App\Site;
use App\Service\NotificationServiceInterface;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
final class NotifierService
{
    private $updater;
    private $slack;
    private $notifySlack;

    public function __construct($updater, NotificationServiceInterface $slack, $notifySlack)
    {
        $this->updater = $updater;
        $this->slack = $slack;
        $this->notifySlack = $notifySlack;
    }

    public function siteOffline(Site $site, $timeDiffInSeconds)
    {
        if ($this->notifySlack) {
            $this->slack->send([
                'message' => '',
                'username' => 'Site Monitor',
                'attachments' => [
                    [
                        'title' => sprintf('Site offline'),
                        'text' => sprintf('Site %s offline for %s seconds', $site->getTitle(), $timeDiffInSeconds),
                        'color' => '#EB4D5C',
                    ]
                ],
            ]);
        }

        $this->updater->setLastNotificationDate($site, new \DateTime());
        $this->updater->setLastNotificationType($site, 'offline');
    }

    public function siteOnline(Site $site)
    {
        if ($this->notifySlack) {
            $this->slack->send([
                'message' => '',
                'username' => 'Site Monitor',
                'attachments' => [
                    [
                        'title' => sprintf('Site online'),
                        'text' => sprintf('Site %s', $site->getTitle()),
                        'color' => '#9EE299',
                    ]
                ],
            ]);
        }

        $this->updater->setLastNotificationDate($site, new \DateTime());
        $this->updater->setLastNotificationType($site, 'online');
    }
}
