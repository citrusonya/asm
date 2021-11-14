<?php
namespace frontend\models;

use yii\base\Model;

class UserFilter extends Model
{
    public $roles;
    public $search;

    public function attributeLabels(): array
    {
        return [
            'roles'  => 'Роль/профессия',
            'search' => 'Поиск',
        ];
    }

    public function rules(): array
    {
        return [
            [['roles', 'search'], 'safe'],
        ];
    }
}
