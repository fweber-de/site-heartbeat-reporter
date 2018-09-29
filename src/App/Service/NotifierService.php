<?php

namespace App\Service;

use App\Site;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
final class NotifierService
{
    /**
     * @var HeartbeatUpdater
     */
    private $updater;

    /**
     * @var NotificationServiceInterface
     */
    private $slack;

    /**
     * @var bool
     */
    private $notifySlack;

    /**
     * @var NotificationServiceInterface
     */
    private $iftttWebhook;

    /**
     * @var bool
     */
    private $notifyIftttWebhook;

    public function __construct(
        HeartbeatUpdater $updater,
        NotificationServiceInterface $slack,
        $notifySlack,
        NotificationServiceInterface $iftttWebhook,
        $notifyIftttWebhook
    )
    {
        $this->updater = $updater;
        $this->slack = $slack;
        $this->notifySlack = $notifySlack;
        $this->iftttWebhook = $iftttWebhook;
        $this->notifyIftttWebhook = $notifyIftttWebhook;
    }

    /**
     * @param Site $site
     * @param $timeDiffInSeconds
     */
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

        if($this->notifyIftttWebhook) {
            $this->iftttWebhook->send([
                'value1' => $site->getTitle(),
                'value2' => 'offline',
                'value3' => $timeDiffInSeconds,
            ]);
        }

        $this->updater->setLastNotificationDate($site, new \DateTime());
        $this->updater->setLastNotificationType($site, 'offline');
    }

    /**
     * @param Site $site
     */
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

        if($this->notifyIftttWebhook) {
            $this->iftttWebhook->send([
                'value1' => $site->getTitle(),
                'value2' => 'online',
                'value3' => null,
            ]);
        }

        $this->updater->setLastNotificationDate($site, new \DateTime());
        $this->updater->setLastNotificationType($site, 'online');
    }
}
