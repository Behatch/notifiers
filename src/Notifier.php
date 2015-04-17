<?php

namespace Behatch\Notifier;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Result\StepResult;

abstract class Notifier implements Context
{
    protected static $spamTimeout;

    public function __construct($spamTimeout = 60)
    {
        self::$spamTimeout = $spamTimeout;
    }

    protected static function notify($status, $title, $message)
    {
        throw new \LogicException('You should implement notify() method in your class');
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
            $message .= '> ' . $result->getException()->getMessage();
            $message = str_replace("'", "`", $message);

            self::spamNotify('error', 'Behat step failure', $message);
        }
    }

    private static function spamNotify($status, $title, $message)
    {
        static $lastTimeError = null;

        if ($lastTimeError === null) {
            $lastTimeError = time() - self::$spamTimeout - 100;
        }

        if (time() - $lastTimeError > self::$spamTimeout) {
            static::notify($status, $title, $message);
            $lastTimeError = time();
        }
    }

    /**
     * @AfterSuite
     */
    public static function afterSuite($event)
    {
        $result = $event->getTestResult();

        if ($result->getResultCode() === StepResult::FAILED) {
            static::notify('sad', 'Behat suite ended', 'Failure');
        } else {
            static::notify('smile', 'Behat suite ended', 'Success');
        }
    }
}
