<?php


namespace app\models\search;

use app\models\Log;
use kartik\daterange\DateRangeBehavior;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class LogsSearch extends Log
{
    public $createTimeRange;
    public $createTimeStart;
    public $createTimeEnd;

    public function behaviors()
    {
        return [
            [
                'class' => DateRangeBehavior::className(),
                'attribute' => 'createTimeRange',
                'dateStartAttribute' => 'createTimeStart',
                'dateEndAttribute' => 'createTimeEnd',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['os','string'],
            ['architecture','string'],
            [['createTimeRange'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
        ]);
    }

    /**
     * @param $params
     * @return SqlDataProvider
     * @throws \yii\db\Exception
     */
    public function search($params)
    {
        $date = explode( 'to', $params['date']);

        $query = new Query();
        $query->select('date, count(*) as cnt');
        $query->from('logs');
        $query->andWhere('os LIKE "%' . $this->os . '%"');
        $query->andWhere('architecture LIKE "%' . $this->architecture . '%"');

        if ($params['date']) {
            $query->andWhere('date>=:dateStart',[':dateStart' => $date[0]])
                ->andWhere('date<=:dateEnd',[':dateEnd' => $date[1]]);
        }

        $query->groupBy('date');


        $command = $query->createCommand();
        $count = Yii::$app->db->createCommand('SELECT COUNT(date) FROM logs group by date')->queryAll();
        $dataProvider = new SqlDataProvider([
            'sql' => '
            WITH Log_CNT AS (SELECT date, url, COUNT(url) AS value_occurrence
                  FROM  logs
                  GROUP BY date, url
                  ORDER BY  value_occurrence DESC
            ),
            Log_CNT2 AS (SELECT date, browser, COUNT(browser) AS value_occurrence2
            FROM  logs
            GROUP BY date, browser
            ORDER BY  value_occurrence2 DESC
            )
        select t3.date, t1.url, t2.browser, t3.cnt from (select date, COUNT(*) AS cnt from logs group by date) t3
            join (SELECT o1.* FROM Log_CNT2 o1 LEFT JOIN Log_CNT2 b1 ON o1.date = b1.date AND o1.value_occurrence2 < b1.value_occurrence2
                WHERE b1.value_occurrence2 is NULL) t2 on t3.date = t2.date
            join (SELECT o.* FROM Log_CNT o LEFT JOIN Log_CNT b ON o.date = b.date AND o.value_occurrence < b.value_occurrence
                WHERE b.value_occurrence is NULL) t1 on t1.date = t3.date;
            ',
            'totalCount' => count($count),
            'params' => [':dateStart' => $date[0], ':dateEnd' => $date[1]],
            'Pagination' => [
                'pageSize' => 5
            ],
        ]);

        $this->load($params);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'ip',
                'date',
                'url',
                'os',
                'architecture',
                'browser'
            ]
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }


        $query->andWhere('os LIKE "%' . $this->os . '%"');
        $query->andWhere('architecture LIKE "%' . $this->architecture . '%"');

        return $dataProvider;
    }
}