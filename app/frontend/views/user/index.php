<?php

use common\models\Role;
use frontend\models\UserFilter;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

/**
 * @var $users       array
 * @var $pagination  Pagination
 * @var $filters     UserFilter
 */

?>
<div>
    <h1>Users</h1>
    <br>
    <a href="<?= Url::to(['user/create']) ?>">
        <button>Add new</button>
    </a>
    <br>
    <br>
    <br>
    <?php

    $form = ActiveForm::begin(
        [
            'id'      => 'filter-form',
            'options' => ['class' => 'search-form'],
            'method'  => 'get'
        ]
    );

    ?>

    <div style="border: black 1px solid">

        <fieldset>

            <legend class="checkbox-filter">Filter by roles</legend>
            <?php echo $form->field(
                $filters,
                'roles',
                [
                    'template'     => '{input}',
                    'labelOptions' => ['class' => 'checkbox__legend']
                ]
            )->checkboxList(
                Role::getPositionsForm(),
                [
                    'item' => function ($index, $label, $name, $checked, $value) {
                        $chek = $checked ? 'checked' : '';
                        return "<label class=\"checkbox__legend\">
                                <input class=\"visually-hidden checkbox__input\" type=\"checkbox\" name=\"{$name}\" value=\"{$value}\" {$chek}>
                                <span>{$label}</span>
                            </label>";
                    },
                ]
            ) ?>

        </fieldset>

        <fieldset>
            <legend>Search by username</legend>
            <?php

            echo $form->field(
                $filters,
                'search',
                [
                    'template'     => '{label}{input}',
                    'options'      => ['class' => ''],
                    'labelOptions' => ['class' => '']
                ]
            )
                ->input(
                    'search',
                    [
                        'class' => 'input-middle input',
                        'style' => 'width: 100%'
                    ]
                );

            echo Html::submitButton('Search', ['class' => 'button']);

            ActiveForm::end();

            ?>
            <br>
            <br>
            <div class="user__search-link">
                <p>Sort by</p>
                <ul class="user__search-list">
                    <li class="user__search-item user__search-item--current">
                        <span>Created at</span>
                        <a href="?sort=hiring_date&direction=asc" class="link-regular" style="border: #1b3f5f 1px solid; margin: 3px;">
                            ↑
                        </a>
                        <a href="?sort=hiring_date&direction=desc" class="link-regular" style="border: #1b3f5f 1px solid; margin: 3px; padding: 2px">
                            ↓
                        </a>
                    </li>
                </ul>
            </div>

        </fieldset>
    </div>

    <div class="wrapper wrapper-employee">
        <div>Name</div>
        <div>Created at</div>
        <div>Role</div>
        <div>Show</div>
        <div>Edit</div>
        <div>Delete</div>
        <?php foreach ($users as $user) { ?>
            <div><?= $user->name ?></div>
            <div><?= date('d.m.y', strtotime($user->createdAt)) ?></div>
            <div><?= $user->role->name ?></div>
            <div>
                <a href="<?= Url::to(['user/view', 'id' => $user->id]) ?>">
                    <button>👁</button>
                </a>
            </div>
            <div>
                <a href="<?= Url::to(['user/edit', 'id' => $user->id]) ?>">
                    <button>✎</button>
                </a>
            </div>
            <div>
                <a href="<?= Url::to(['user/delete', 'id' => $user->id]) ?>">
                    <button>❌</button>
                </a>
            </div>
        <?php } ?>
    </div>

    <div class="new-task__pagination">

        <?= LinkPager::widget(
            [
                'pagination'         => $pagination,
                'options'            => [
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
