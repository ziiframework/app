<?php

declare(strict_types=1);

namespace Zpp\Queue;

use DateTimeZone;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;
use yii\queue\RetryableJobInterface;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

abstract class AbstractBaseQueue extends BaseObject implements JobInterface, RetryableJobInterface
{
    protected function logger(): Logger
    {
        return new Logger(
            'Queue',
            [
                new StreamHandler(ZDIR_SHARED . '/runtime' . sprintf('/logs/queue.%s.log', date('Y-m')), Logger::INFO),
            ],
            [],
            new DateTimeZone(Yii::$app->timeZone)
        );
    }

    /**
     * @param Queue $queue
     * @throws QueueRuntimeException
     */
    abstract public function execute($queue): void;

    /**
     * @return int
     */
    public function getTtr()
    {
        return 15 * 60;
    }

    /**
     * @param int $attempt
     * @param QueueRuntimeException $error
     * @return bool
     */
    public function canRetry($attempt, $error)
    {
        return ($attempt < 2) && ($error instanceof QueueRuntimeException);
    }
}
