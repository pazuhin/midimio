<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';

use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use okeanos\chartist\Chartist;
use yii\helpers\Json;
use yii\web\JsExpression;

echo $this->render('_search', ['model' => $searchModel]);

echo 'График 1';
echo Chartist::widget([
    'tagName' => 'div',
    'data' => new JsExpression(Json::encode([
        'labels' => $graph['date'],
        'series' => [
            $graph['cnt'],
        ]
    ])),
    'chartOptions' => [
        'options' => [
            'seriesBarDistance' => 15
        ],
        'responsiveOptions' => [
            [	'screen and (max-width: 640px)',
                [
                    'seriesBarDistance' => 5,
                    'axisX' => [
                        'labelInterpolationFnc' => new JsExpression('function (value) { return value[0]; }'),
                    ]
                ]
            ]
        ]
    ],
    'widgetOptions' => [
        'type' => 'Bar',
        'useClass' => 'chartist-chart'
    ],
    'htmlOptions' => [
        'class' => 'chartist-chart ct-chart ct-golden-section',
    ]
]);

echo 'График 2 (% от числа запросов для трех самых популярных браузеров)';
echo Chartist::widget([
    'tagName' => 'div',
    'data' => new JsExpression(Json::encode([
        'labels' => $graphPercentArr['date'],
        'series' => [
            $graphPercentArr['sum']
        ]
    ])),
    'widgetOptions' => [
        'type' => 'Bar',
    ],
    'htmlOptions' => [
        'class' => 'ct-chart ct-golden-section',
        'id' => 'chartistLineEvents',
    ]
]);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'date',
            'label' => 'Дата',
            'format' => 'date',
            'class' => 'yii\grid\DataColumn',
            'value' => function ($data) {
                return $data['date'];
            }
        ],
        [
            'attribute' => 'day_count',
            'format' => 'text',
            'label' => 'Число запросов',
            'class' => 'yii\grid\DataColumn',
            'value' => function ($data) {
                return $data['day_count'];
            },
        ],
        [
            'attribute' => 'day_url',
            'format' => 'text',
            'label' => 'Самый популярный URL',
            'class' => 'yii\grid\DataColumn',
            'value' => function ($data) {
                return $data['day_url'];
            },
        ],
        [
            'format' => 'text',
            'attribute' => 'day_browser',
            'label' => 'Самый популярный Браузер',
            'class' => 'yii\grid\DataColumn',
            'value' => function ($data) {
                return $data['day_browser'];
            },
        ],
    ],
]);

?>
