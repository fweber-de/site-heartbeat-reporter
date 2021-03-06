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
    private $diff;

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

    public function getDiff()
    {
        return $this->diff;
    }

    public function setDiff($diff)
    {
        $this->diff = $diff;

        return $this;
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}
