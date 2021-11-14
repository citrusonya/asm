<?php

use frontend\models\UserForm;
use yii\db\Query;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/**
 * @var $form  ActiveForm
 * @var $user  UserForm
 * @var $roles Query
 */

?>

<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <h1>Fill fields</h1>

    <div class="row">
        <div class="col-lg-5">
            <?php

            $form = ActiveForm::begin([
                'id'            => 'form-signup',
                'errorCssClass' => 'has-error',
                'fieldConfig'   => [
                    'errorOptions' => ['tag' => 'span', 'class' => 'registration__text-error']
                ]
            ]);

            echo $form->field($user, 'username');

            echo $form->field($user, 'phone')->widget(
                DatePicker::className(),
                ['int', 'options' => ['class' => 'form-control']]
            );

            echo $form->field($user, 'email')->widget(
                DatePicker::classname(),
                ['string', 'options' => ['class' => 'form-control']]
            );

            echo $form->field($user, 'role_id')->dropdownList($roles, ['prompt' => '']);

            echo Html::submitButton(empty($user->id) ? 'Add' : 'Save', ['class' => 'btn btn-primary']);

            $form = ActiveForm::end();

            ?>
        </div>
    </div>
</div>