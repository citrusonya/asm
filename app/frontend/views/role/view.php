<?php

use frontend\models\RoleForm;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var $form ActiveForm
 * @var $role RoleForm
 */

?>


<div>
    <h1>Role <?= $role->name ?></h1>
    <br>
    <div class="wrapper wrapper-view">
        <div>Name role</div>
        <div><?= $role->name ?></div>
        <div>
            <a href="<?= Url::to(['role/edit', 'id' => $role->id]) ?>">
                <button>Edit ✎</button>
            </a>
        </div>
        <div>
            <a href="<?= Url::to(['role/delete', 'id' => $role->id]) ?>">
                <button>Delete ❌</button>
            </a>
        </div>
    </div>


</div>