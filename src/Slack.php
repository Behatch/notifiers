<?php

namespace Behatch\Notifier;

class Slack extends Notifier
{
    static private $url;
    static private $settings;
    static private $prefix;
    static private $attachment;

    public function __construct($url, $settings = [], $prefix = null, $attachment = [], $spamTimeout = 60)
    {

        if (!class_exists('\\Maknz\\Slack\\Client')) {
            $message = 'Class \Maknz\Slack\Client does not exist. Are you sure you have installed it? i.e. composer require "maknz/slack"';
            throw new \Exception( $message );
        }

        parent::__construct($spamTimeout);

        self::$url = $url;
        self::$prefix = $prefix;

        self::$settings = $settings + [
            'username' => 'Behat',
            'channel' => '#general',
            'link_names' => true,
            'icon' => ':fire:',
        ];

        self::$attachment = $attachment + [
            'fallback' => 'Behat test failed',
            'text' => '',
            'pretext' => '',
            'color' => 'danger',
            'fields' => array(),
        ];
    }

    protected static function notify($status, $title, $message)
    {

        // We don't need the second notification about the suite failing
        if (strpos($title, 'suite ended') !== false) {
            return;
        }

        // If there's a prefix, prepend it to the title of the message
        if (self::$prefix !== null) {
            $title = '[' . self::$prefix . '] ' . $title;
        }

        // Fire up the Slack Client with the given webhook url and settings
        $client = new \Maknz\Slack\Client(self::$url, self::$settings);

        // Split the message on > as the behat step failures give the regex then the message
        // We just want the piece after the final '>'. @TODO: Better option here? As this will
        // fail horribly if there is a '>' character in the Exception
        $splitMessage = explode( '>', $message );

        // Build the attachment
        self::$attachment['pretext'] = $title;

        self::$attachment['fields'] = array(
            array(
                'title' => 'Step',
                'value' => end($splitMessage),
                'short' => false,
            ),
        );

        // Send!
        $client->attach(self::$attachment)->enableMarkdown()->send();

    }
}
