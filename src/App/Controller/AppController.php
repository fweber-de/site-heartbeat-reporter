<?php

namespace App\Controller;

use App\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Seld\JsonLint\ParsingException;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
final class AppController extends Controller
{
    public function updateHeartbeatAction(Request $request)
    {
        try {
            $source = $this->parseJson($request->getContent());
        } catch (ParsingException $p) {
            return $this->json([
                'status' => 'error',
                'message' => 'json error',
            ], 400);
        }

        if (!$this->get('app.updater')->verify($source->app_key, $source->secret)) {
            return $this->json([
                'status' => 'error',
                'message' => 'verification failed',
            ], 403);
        }

        $this->get('app.updater')->update($source->app_key);

        return $this->json([
            'status' => 'success',
            'message' => 'updated successfully',
        ]);
    }

    public function showHeartbeatAction(Request $request)
    {
        $key = $request->get('key');
        $secret = $request->get('secret');

        if (!$this->get('app.updater')->verify($key, $secret)) {
            return $this->json([
                'status' => 'error',
                'message' => 'verification failed',
            ], 403);
        }

        $site = $this->get('app.updater')->getSite($key);

        return $this->json([
            'site' => $site->toArray(),
            'data' => [
                'last_contact' => $this->get('app.updater')->getLastContactOfSite($site),
                'last_notification_type' => $this->get('app.updater')->getLastNotificationType($site),
                'last_notified' => $this->get('app.updater')->getLastNotificationDate($site),
            ]
        ]);
    }

    public function statusAction(Request $request)
    {
        $key = $request->get('key');
        $secret = $request->get('secret');

        if (!$this->get('app.updater')->verify($key, $secret)) {
            return $this->json([
                'status' => 'error',
                'message' => 'verification failed',
            ], 403);
        }

        $site = $this->get('app.updater')->getSite($key);
        $lastContact = $this->get('app.updater')->getLastContactOfSite($site);
        $diff = (new \DateTime())->getTimestamp() - $lastContact->getTimestamp();

        if ($diff < $site->getDiff()) {
            return $this->json(['status' => 'site online']);
        } else {
            return $this->json(['status' => 'site offline'], 500);
        }
    }
}
