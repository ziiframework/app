<?php

namespace Zpp\Commands;

use yii\console\ExitCode;

final class HelloController extends BaseController
{
    public function actionIndex(string $message = 'Hello World'): int
    {
        echo $message . " from HQ.\n";

        return ExitCode::OK;
    }
}
