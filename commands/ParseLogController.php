<?php

namespace app\commands;

use app\components\LogParser;
use yii\console\Controller;
use yii\console\ExitCode;

class ParseLogController extends Controller
{
    /**
     * @param string $message
     * @return int
     */
    public function actionIndex()
    {
        LogParser::parse(__DIR__.'/../runtime/logs/modimio.access.log.1');

        return ExitCode::OK;
    }
}