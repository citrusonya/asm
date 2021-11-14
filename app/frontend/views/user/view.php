<?php

use common\models\User;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $form ActiveForm */
/* @var $user User */

?>


<div>
    <h1>Role <?= $user->roleName ?></h1>
    <br>
    <div class="wrapper wrapper-view">
        <div>Username</div>
        <div><?= $user->username ?></div>
        <div>Created at</div>
        <div><?= date('d.m.y', strtotime($user->createdAt)) ?></div>
        <div>Role</div>
        <div><?= $user->roleName ?></div>
        <div>
            <a href="<?= Url::to(['employee/edit', 'id' => $user->id]) ?>">
                <button>Edit ✎</button>
            </a>
        </div>
        <div>
            <a href="<?= Url::to(['employee/delete', 'id' => $user->id]) ?>">
                <button>Delete ❌</button>
            </a>
        </div>
    </div>
</div>