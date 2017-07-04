<?php

namespace App\Service;

use App\Service\NotificationServiceInterface;
use GuzzleHttp\Client as Guzzle;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
final class SlackService implements NotificationServiceInterface
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function send($params)
    {
        $guzzle = new Guzzle();
        $payload = [
            'text' => $params['message'],
            'username' => $params['username'],
            'attachments' => $params['attachments'],
        ];

        $res = $guzzle->request('POST', $this->url, [
            'body' => $this->sanitizeNewline(json_encode($payload)),
        ]);
    }

    private function sanitizeNewline($str)
    {
        return str_replace('\\\\n', '\n', $str);
    }
}
