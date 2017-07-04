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
}
