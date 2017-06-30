<?php

namespace App;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
final class Site
{
    private $appKey;
    private $secret;
    private $title;

    public function getAppKey()
    {
        return $this->appKey;
    }

    public function setAppKey($key)
    {
        $this->appKey = $key;

        return $this;
    }

    public function getSecret()
    {
        return $this->secret;
    }

    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }
}
