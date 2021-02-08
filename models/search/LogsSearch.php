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
//        $query = new Query();
//        $query->select('date, count(*) as cnt');
//        $query->from('logs');
//        $query->andWhere('os LIKE "%' . $this->os . '%"');
//        $query->andWhere('architecture LIKE "%' . $this->architecture . '%"');

//        if (isset($params['date'])) {
//            $date = explode( 'to', $params['date']);
//            $query->andWhere('date>=:dateStart',[':dateStart' => $date[0]])
//                ->andWhere('date<=:dateEnd',[':dateEnd' => $date[1]]);
//        }
//
//        $query->groupBy('date');


//        $command = $query->createCommand();
//        $count = Yii::$app->db->createCommand('SELECT COUNT(date) FROM logs group by date')->queryAll();
        $dataProvider = new SqlDataProvider([
            'sql' => '
            select date, count(*) as day_count, (
                select url from logs t1 where t1.date = t.date group by url order by count(1) desc limit 1
            ) as day_url, (select browser from logs t2 where t2.date = t.date group by browser order by count(1) desc limit 1) as day_browser
            from logs t
            group by date
            ',
//            'totalCount' => count($count),
            //'params' => [':dateStart' => $date[0], ':dateEnd' => $date[1]],
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


//        $query->andWhere('os LIKE "%' . $this->os . '%"');
//        $query->andWhere('architecture LIKE "%' . $this->architecture . '%"');

        return $dataProvider;
    }
}