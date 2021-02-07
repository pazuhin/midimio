<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PostSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'fromDate')->input('date') ?>

    <?= $form->field($model, 'toDate')->input('date') ?>

    <div class="form-group">
        <?= Html::submitButton(['Искатьать class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(['Сброситьситьтььclass' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
