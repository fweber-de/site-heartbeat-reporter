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
        $site = $this->getSite($key);
        $this->setDataOnSite($site, 'last_contact', (new \DateTime())->getTimestamp());

        return $this;
    }

    public function setLastNotificationDate(Site $site, \DateTime $date = null)
    {
        $date = $date ?? new \DateTime('01.01.1970');
        $this->setDataOnSite($site, 'last_notified', $date->getTimestamp());
    }

    public function getLastNotificationDate(Site $site) : \DateTime
    {
        $c = new \DateTime();
        $c->setTimestamp($this->getDataFromSite($site, 'last_notified'));

        return $c;
    }

    public function setLastNotificationType(Site $site, $type)
    {
        $this->setDataOnSite($site, 'last_notification_type', $type);
    }

    public function getLastNotificationType(Site $site)
    {
        return $this->getDataFromSite($site, 'last_notification_type') ?? null;
    }

    public function getLastContactOfSite(Site $site) : \DateTime
    {
        $c = new \DateTime();
        $c->setTimestamp($this->getDataFromSite($site, 'last_contact'));

        return $c;
    }

    private function setDataOnSite(Site $site, $key, $value)
    {
        $store = @file_get_contents($this->dataFilePath);

        if($store == '') {
            $store = '[]';
        }

        $store = json_decode($store, true);

        $store['site_'.$site->getAppKey()][$key] = $value;

        file_put_contents($this->dataFilePath, json_encode($store));
    }

    private function getDataFromSite(Site $site, $key)
    {
        $store = @file_get_contents($this->dataFilePath);

        if($store == '') {
            throw new \Exception('site not seen');
        }

        $store = json_decode($store, true);

        return $store['site_'.$site->getAppKey()][$key] ?? null;
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
                    ->setDiff($site['diff'])
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
