<?php

use yii\data\Pagination;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/**
 * @var $roles      array
 * @var $pagination Pagination
 */

?>
<div>
    <h1>Roles</h1>
    <br>
    <a href="<?= Url::to(['role/create']) ?>">
        <button>Create new</button>
    </a>
    <br>
    <div class="wrapper">
        <div>Name</div>
        <div>Показать</div>
        <div>Изменить</div>
        <div>Удалить</div>
        <?php foreach ($roles as $role) { ?>
            <div><?= $role->name ?></div>
            <div><a href="<?= Url::to(['role/view', 'id' => $role->id]) ?>">
                    <button>👁</button>
                </a></div>
            <div>
                <a href="<?= Url::to(['role/edit', 'id' => $role->id]) ?>">
                    <button>✎</button>
                </a>
            </div>
            <div>
                <a href="<?= Url::to(['role/delete', 'id' => $role->id]) ?>">
                    <button>❌</button>
                </a>
            </div>
        <?php } ?>
    </div>

    <div>

        <?= LinkPager::widget(
            [
                'pagination' => $pagination,
                'options' => [
                    'class' => 'pagination-list',
                ],
                'activePageCssClass' => 'pagination__item--current',
                'pageCssClass'       => 'pagination__item',
                'prevPageCssClass'   => 'pagination__item',
                'nextPageCssClass'   => 'pagination__item',
                'nextPageLabel'      => 'next',
                'prevPageLabel'      => 'prev',
                'hideOnSinglePage'   => true
            ]
        ) ?>

    </div>
</div>
