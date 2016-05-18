# Behatch notifiers

[![Build Status](https://travis-ci.org/Behatch/notifiers.svg)](https://travis-ci.org/Behatch/notifiers)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Behatch/notifiers/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Behatch/notifiers/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/78cf311b-e4ec-4ea6-bdd2-0c62d3204ed5/mini.png)](https://insight.sensiolabs.com/projects/78cf311b-e4ec-4ea6-bdd2-0c62d3204ed5)

Behatch notifiers provide notifiers for behat.

## Installation

    $ composer require "behatch/notifiers"

For Slack notifier, additionally

    $ composer require "maknz/slack"

## Usage

In ``behat.yml``, enable desired notifier:

``` yaml
default:
    suites:
        default:
            contexts:
                - Behatch\Notifier\Desktop
                - Behatch\Notifier\Campfire
                    url: https://sample.campfirenow.com
                    token: 605b32dd
                    room: 1
                - Behatch\Notifier\Slack
                    url: https://hooks.slack.com/services/some-long/hook-url/goes-here
                    spamTimeout: '10'
                    prefix: 'Message Prefix'
                    settings: {username: BehatBot, channel: '#general'}
```

## Configuration

* ``Desktop`` - notification through libnotify (``notify-send`` command)
    * ``spamTimeout``: default time between two notifications (60 secondes)
    * ``icons``: array of icons (sad, smile and error)
* ``Campfire`` - notification over [campfire](https://campfirenow.com/)
    * ``url``, ``token``, ``room``: campfire configuration
    * ``prefix``: title prefix
    * ``spamTimeout``: default time between two notifications (60 secondes)
    * ``icons``: array of emoticons (sad, smile and error)
* ``Slack`` - notification over [slack](https://slack.com/)
    * ``url``: Slack webhook url
    * ``prefix``: title prefix
    * ``spamTimeout``: default time between two notifications (60 secondes)
    * ``settings``: Slack message settings (array)
    * ``attachment``: Slack message attachment settings (array)
