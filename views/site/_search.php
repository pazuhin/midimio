<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PostSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-search" style="display: flex">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['style' => 'display: flex; flex-direction: column;'],
    ]); ?>
    <div class="inputs" style="display:flex;flex-direction: row">
        <div class="" style="margin-right: 20px;">
            <?= $form->field($model, 'fromDate')->input('date', ['min' => date("Y-m-d",strtotime("-6 month")), 'max' => date("Y-m-d",strtotime("+6 month"))])->label('Дата начала') ?>
        </div>
        <div class="">
            <?= $form->field($model, 'toDate')->input('date', ['min' => date("Y-m-d",strtotime("-6 month")), 'max' => date("Y-m-d",strtotime("+6 month"))])->label('Дата окончания') ?>
        </div>

    </div>
    <div class="form-group">
        <?= Html::submitButton('Искать', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Сбросить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
