<?php

namespace App\Service;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
interface NotificationServiceInterface
{
    public function send($params);
}
