<?php


namespace app\models;


use Yii;
use yii\db\ActiveRecord;

/**
 * Class Log
 * @package app\models
 */
class Log extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'logs';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                'id, ip, date, url, os, architecture, browser', 'safe'
            ],
        ];
    }

    /**
     * @param array $date
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getRequestCountByThreeBrowsers(array $date): array
    {
        $result = [];
        foreach ($date as $item) {
            $result[] = Yii::$app->db->createCommand('select date, sum(value_occurrence) sum from (SELECT date, browser, COUNT(browser) AS value_occurrence
            FROM  logs
            where date = :date
            GROUP BY date, browser
            ORDER BY  value_occurrence DESC
            limit 3) t  group by date;')
            ->bindValue(':date', $item)
            ->queryOne();
        }

        return $result;
    }
}