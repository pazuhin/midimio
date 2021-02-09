<?php


namespace app\models\search;

use app\models\Log;
use kartik\daterange\DateRangeBehavior;
use ReflectionClass;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class LogsSearch
 * @package app\models\search
 */
class LogsSearch extends Log
{
    public $fromDate;
    public $toDate;

    /**
     * @param $params
     * @return SqlDataProvider
     * @throws \yii\db\Exception
     * @throws \ReflectionException
     */
    public function search($params)
    {
        $classname = (new ReflectionClass($this))->getShortName();

        $sql = 'select date, count(*) as day_count, 
            (select url from logs t1 where t1.date = t.date group by url order by count(1) desc limit 1) as day_url, 
            (select browser from logs t2 where t2.date = t.date group by browser order by count(1) desc limit 1) as day_browser
            from logs t group by date';


        $sql .= $this->getSql($params, $classname);

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'pagination' => [
                'pageSize' => 5
            ],
            'sort' => [
                'attributes' => [
                    'date' => [
                        'asc' => ['date' => SORT_ASC],
                        'desc' => ['date' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'day_count' => [
                        'asc' => ['day_count' => SORT_ASC],
                        'desc' => ['day_count' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'day_url' => [
                        'asc' => ['day_url' => SORT_ASC],
                        'desc' => ['day_url' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'day_browser' => [
                        'asc' => ['day_browser' => SORT_ASC],
                        'desc' => ['day_browser' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * @param array $params
     * @param string $classname
     * @return string
     */
    public function getSql(array $params, string $classname): string
    {
        $filter = '';

        if (!empty($params)) {
            $filter .= ' having ';
            if (!empty($params[$classname]['fromDate']) && !empty($params[$classname]['toDate'])) {
                $filter .= " date between '{$params[$classname]['fromDate']}' and '{$params[$classname]['toDate']}'";
            } else {
                if ($params[$classname]['fromDate'] !== '') {
                    $filter .= " date > '{$params[$classname]['fromDate']}'";
                }

                if ($params[$classname]['toDate'] !== '') {
                    $filter .= " date < '{$params[$classname]['toDate']}'";
                }
            }
        }

        return $filter;
    }
}