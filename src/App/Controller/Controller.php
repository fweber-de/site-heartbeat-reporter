<?php

namespace App\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Seld\JsonLint\JsonParser;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
abstract class Controller implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Returns true if the service id is defined.
     *
     * @param string $id The service id
     *
     * @return bool true if the service id is defined, false otherwise
     */
    protected function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * Gets a container service by its id.
     *
     * @param string $id The service id
     *
     * @return object The service
     */
    protected function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * Gets a container configuration parameter by its name.
     *
     * @param string $name The parameter name
     *
     * @return mixed
     */
    protected function getParameter($name)
    {
        return $this->container->getParameter($name);
    }

    protected function parseJson($json)
    {
        $parser = new JsonParser();

        return $parser->parse($json);
    }

    protected function json($data, $status = 200, $contentType = 'application/json')
    {
        return new Response(json_encode($data), $status, [
            'Content-Type' => $contentType
        ]);
    }
}
