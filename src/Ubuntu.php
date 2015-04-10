<?php

namespace Behatch\Notifier;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Result\StepResult;

class Ubuntu implements Context
{
    static private $icons = [];
    static private $notifier = null;
    static private $spamTimeout = 60;

    public function __construct($spamTimeout = 60, $icons = [])
    {
        self::$spamTimeout = $spamTimeout;

        $behatchDir = __DIR__ . '/Resources';

        self::$icons = $icons + [
            'sad' => "$behatchDir/images/gnome-sad.png",
            'smile' => "$behatchDir/images/gnome-smile.png",
            'error' => "$behatchDir/images/gnome-error.png",
        ];
    }

    /**
     * @BeforeSuite
     */
    public static function beforeSuite($event)
    {
        // @FIXME override user config icon
        self::$notifier = new self(self::$spamTimeout, self::$icons);
    }

    /**
     * @AfterStep
     */
    public function afterStep($event)
    {
        $result = $event->getTestResult();

        if ($result->getResultCode() === StepResult::FAILED) {
            $definition = $result->getStepDefinition();

            $message = "$definition\n";
            $message .= '> '.$result->getException()->getMessage();
            $message = str_replace("'", "`", $message);

            $this->notify('error', 'Behat step failure', $message);
        }
    }

    /**
     * @AfterSuite
     */
    static public function afterSuite($event)
    {
        $result = $event->getTestResult();

        $notifier = new self(self::$spamTimeout, self::$icons);
        if ($result->getResultCode() === StepResult::FAILED) {
            $notifier->notify('sad', 'Behat suite ended', 'Failure');
        }
        else {
            $notifier->notify('smile', 'Behat suite ended', 'Success');
        }
    }

    private function notify($iconName, $title, $message)
    {
        static $lastTimeError = null;

        if ($lastTimeError === null) {
            time() - self::$spamTimeout - 100;
        }

        if (time() - $lastTimeError > self::$spamTimeout) {
            $cmd = sprintf(
                "notify-send -i '%s' '%s' '%s - %s'",
                self::$icons[$iconName], $title, $message, self::$icons[$iconName]
            );

            exec($cmd);
            $lastTimeError = time();
        }
    }
}
