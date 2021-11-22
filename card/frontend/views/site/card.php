<?php

/**
 * @var $this  yii\web\View
 * @var $form  yii\bootstrap4\ActiveForm
 * @var $model CardForm
 */

use frontend\models\CardForm;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Check Card';
?>
<div class="site-card">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Введите данные</h1>
        <div class="jumbotron text-center bg-transparent">
            <?php $form = ActiveForm::begin(['id' => 'card-form']); ?>

            <?= $form->field($model, 'pan')->label('Номер карты')->textInput(
                ['type' => 'number', 'autofocus' => true]
            ) ?>

            <?= $form->field($model, 'cvc')->label('cvc')->textInput(['type' => 'number', 'autofocus' => true]
            ) ?>

            <?= $form->field($model, 'cardholder')->label('Инициалы держателя карты')->textInput(['type' => 'string', 'autofocus' => true]) ?>

            <?= $form->field($model, 'expire')->label('Дата окончания срока действия')->textInput(
                ['type' => 'number', 'autofocus' => true]
            ) ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'token']); ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="body-content">

        <div class="row">
        </div>

    </div>
</div>
