<?php

namespace Behatch\Notifier;

class Campfire extends Notifier
{
    static private $url;
    static private $token;
    static private $room;
    static private $prefix;
    static private $icons = [];

    public function __construct($url, $token, $room, $prefix = null, $spamTimeout = 60, $icons = [])
    {
        parent::__construct($spamTimeout);

        self::$url = $url;
        self::$token = $token;
        self::$room = $room;
        self::$prefix = $prefix;

        self::$icons = $icons + [
            'sad' => ':thumbsdown:',
            'smile' => ':thumbsup::sparkles:',
            'error' => ':thumbsdown::shit:',
        ];
    }

    protected static function notify($status, $title, $message)
    {
        if (self::$prefix !== null) {
            $title = '[' . self::$prefix . '] ' . $title;
        }

        if (isset(self::$icons[$status])) {
            $message .= ' ' . self::$icons[$status];
        }

        $body = "$title\n$message";

        $cmd = sprintf(
            "curl -s -u %s:X -H 'Content-Type: application/json' -d %s %s/room/%s/speak.json",
            self::$token,
            escapeshellarg(json_encode(['message' => ['body' => $body]])),
            trim(self::$url, '/'),
            self::$room
        );

        exec($cmd, $output, $return);

        if ($return !== 0) {
            throw new \Exception(
                sprintf("Unable to send campfire notification with curl :\n%s", implode("\n", $output))
            );
        }
    }
}
