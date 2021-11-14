<?php

use frontend\models\RoleForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $form ActiveForm
 * @var $role RoleForm
 */

?>

<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <h1>Enter role</h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

            <?= $form->field($role, 'name')->textInput(['autofocus' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton(empty($role->id) ? 'Add' : 'Save', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>