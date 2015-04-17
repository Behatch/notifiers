<?php

namespace Behatch\Notifier;

class Desktop extends Notifier
{
    static private $icons = [];

    public function __construct($spamTimeout = 60, $icons = [])
    {
        parent::__construct($spamTimeout);

        $behatchDir = __DIR__ . '/Resources';

        self::$icons = $icons + [
            'sad' => "$behatchDir/images/gnome-sad.png",
            'smile' => "$behatchDir/images/gnome-smile.png",
            'error' => "$behatchDir/images/gnome-error.png",
        ];
    }

    protected static function notify($status, $title, $message)
    {
        $cmd = sprintf(
            "notify-send -i '%s' '%s' '%s'",
            self::$icons[$status], $title, $message
        );

        exec($cmd);
    }
}
