<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';

use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use okeanos\chartist\Chartist;
use yii\helpers\Json;
use yii\web\JsExpression;
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

echo 'График 2';
echo Chartist::widget([
    'tagName' => 'div',
    'data' => new JsExpression(Json::encode([
        'labels' => $graphPercentArr['date'],
        'series' => [
            $graphPercentArr['max'],
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

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
//    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'Date',
            'label' => 'Дата',
            'format' => 'text',
//            'filter' => '<div class="drp-container input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>' .
//                DateRangePicker::widget([
//                    'name' => 'date',
//                    'pluginOptions' => [
//                        'locale' => [
//                            'separator' => 'to',
//                        ],
//                        'opens' => 'right'
//                    ]
//                ]) . '</div>',
            'content' => function ($data) {
                return Yii::$app->formatter->asDatetime($data['date'], "php:d-M-Y");
            }
        ],
        [
            'header' => 'Число запросов',
            'class' => 'yii\grid\DataColumn',
            'value' => function ($data) {
                return $data['cnt'];
            },
        ],
        [
            'header' => 'Самый популярный URL',
            'class' => 'yii\grid\DataColumn',
            'value' => function ($data) {
                return $data['url'];
            },
        ],
        [
            'header' => 'Самый популярный Браузер',
            'class' => 'yii\grid\DataColumn',
            'value' => function ($data) {
                return $data['browser'];
            },
        ],
    ],
]);

?>
