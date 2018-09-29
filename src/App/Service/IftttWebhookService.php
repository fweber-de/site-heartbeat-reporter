<?php

namespace App\Service;

use GuzzleHttp\Client as Guzzle;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
final class IftttWebhookService implements NotificationServiceInterface
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

        $data = [];

        if(!is_null(@$params['value1'])) {
            $data['value1'] = $params['value1'];
        }

        if(!is_null(@$params['value2'])) {
            $data['value2'] = $params['value2'];
        }

        if(!is_null(@$params['value3'])) {
            $data['value3'] = $params['value3'];
        }

        if(count($data) > 0) {
            $res = $guzzle->request('POST', $this->url, [
                'body' => json_encode($data),
            ]);
        } else {
            $res = $guzzle->request('POST', $this->url);
        }
    }
}
