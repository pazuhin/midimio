<?php

namespace app\components;

use app\models\Log;
use Yii;

class LogParser
{
    /**
     * @param string $name
     * @return iterable
     */
    public static function getLog(string $name): Iterable
    {
        $handle = @fopen($name, 'r');
        if ($handle) {
            while (($buffer = fgets($handle)) !== false) {
                yield $buffer;
            }
            if (!feof($handle)) {
                yield 'error';
            }
            fclose($handle);
        }
    }

    /**
     * @param $name
     */
    public static function parse($name)
    {
        foreach (self::getLog($name) as $logIndex => $log) {
            $data = self::getBrowserData($log) + self::getIp($log) + self::getDate($log) + self::getUrl($log);

            $log = new Log();
            $log->ip = $data['ip'];
            $log->date = $data['date'];
            $log->url = $data['url'];
            $log->os = $data['os'];
            $log->architecture = $data['architecture'];
            $log->browser = $data['browser'];

            if (!$log->save()) {
                echo 'error' . PHP_EOL;
            }
        }
    }

    public static function getBrowserData(string $log)
    {
        $bname = 'Unknown';
        $platform = 'Unknown';

        if (preg_match('/linux/i', $log)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $log)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $log)) {
            $platform = 'windows';
        }

        if(preg_match('/MSIE/i',$log) && !preg_match('/Opera/i',$log))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$log))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$log))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$log))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$log))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$log))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        $arch_regex = '/\b(x86_64|x86-64|Win64|WOW64|x64|ia64|amd64|ppc64|sparc64|IRIX64)\b/ix';
        $arch = preg_match($arch_regex, $log) ? '64' : '86';

        return [
            'browser'      => $bname,
            'os'  => $platform,
            'architecture' => $arch
        ];
    }

    /**
     * @param string $log
     * @return mixed
     */
    public static function getIp(string $log): array
    {
        preg_match('/^.*?\s/', $log, $ip);

        return ['ip' => $ip[0]];
    }

    /**
     * @param string $log
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function getDate(string $log)
    {
        preg_match('/\[(.+?)\]/', $log, $b);

        $date = Yii::$app->formatter->asDate(str_replace('/', '-', explode(':', $b[1])[0]), 'yyyy-MM-dd');
        return ['date' => $date];
    }

    /**
     * @param string $log
     * @return array
     */
    public static function getUrl(string $log)
    {
        preg_match('/[GET|POST]+([\s\S]+?)HTTP/',$log, $url);

        return ['url' => !empty($url[1]) ? trim($url[1]) : null];
    }
}