<?php

namespace App\Service;

use Symfony\Component\Yaml\Yaml;
use App\Site;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
final class HeartbeatUpdater
{
    private $siteConfigPath;
    private $dataFilePath;

    public function __construct($siteConfigPath, $dataFilePath)
    {
        $this->siteConfigPath = $siteConfigPath;
        $this->dataFilePath = $dataFilePath;
    }

    public function verify($key, $secret)
    {
        $site = $this->getSite($key);

        if(!$site) {
            throw new \Exception('site unknown');
        }

        if($secret === $site->getSecret()) {
            return true;
        }

        return false;
    }

    public function update($key)
    {
        $store = @file_get_contents($this->dataFilePath);

        if($store == '') {
            $store = '[]';
        }

        $store = json_decode($store, true);

        $store['site_'.$key]['last_contact'] = new \DateTime();

        file_put_contents($this->dataFilePath, json_encode($store));

        return $this;
    }

    public function getSites()
    {
        try {
            $_sites = Yaml::parse(file_get_contents($this->siteConfigPath))['sites'];
        } catch (\Exception $exc) {
            throw new \Exception(sprintf('Something is wrong with the file %s! Maybe the file does not exist?', $this->siteConfigPath));
        }

        $sites = [];

        foreach ($_sites as $s) {
            foreach ($s as $key => $site) {
                $sites[$key] = (new Site())
                    ->setAppKey($key)
                    ->setSecret($site['secret'])
                    ->setTitle($site['title'])
                ;
            }
        }

        return $sites;
    }

    public function getSite($key)
    {
        return $this->getSites()[$key] ?? null;
    }
}
