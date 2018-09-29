<?php

namespace App\Service;

use GuzzleHttp\Client as Guzzle;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
final class WebhookService implements NotificationServiceInterface
{
    /**
     * @var string
     */
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * @param $params
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send($params)
    {
        $guzzle = new Guzzle();

        $res = $guzzle->request('POST', $this->url);
    }
}
