Site Heartbeat Reporter
=======================

Do you want to know if your companys site is still online?
Most of the time monitoring solutions are running behind the firewall but alerting is handled by systems beyond your control,
like Slack, PagerDuty, etc...
If the internet connection of the site is not redundant and alerting fails, you wont get notified.

**Site Heartbeat Reporter to the rescue!**

Set up a script on one of your running machines in the office which sends periodic messages to the Heartbeat Endpoint,
if the Endpoint doesn't hear from your site in a customizable timeframe you get alerted.

## Supported Channels

* **Slack** A 'Site Offline' message gets sent to a defined Slack channel, if the site is online again, a 'Site Online' message is sent

## Installation

* clone the repo
* run composer install
* configure
* set the webroot to the public directory

## Settings

Configure your endpoint with the .yml files in `/config`:

`app.yml`
```yml
config:
    slack_url: null
    notify.slack: false
```

`slack_url` is the public url of your channel
`notify.slack` toggles if anything is sent to this channel at all

`sites.yml`
```yml
sites:
    - key1:
        title: site1
        secret: null
        diff: null
    - key2:
        title: site2
        secret: null
        diff: null
```

`key` is the unique identifier of the site
`title` is the display name
`secret` is the secure string whis has to be sent in every update request
`diff` is the time difference in seconds which is allowed to pass between two update cycles

## Update a site

Send a POST request to the endpoint `/index.php?p=update`
with this json payload:

```json
{
	"app_key": "<key>",
	"secret": "<secret>"
}
```

## Check site status cli script

Configure a cron job with the following script to check the time diff and send messages

`php console.php app:check:site <key>`
