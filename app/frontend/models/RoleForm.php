<?php

namespace frontend\models;

use common\models\{Role};
use yii\base\Model;

/**
 * Signup form
 */
class RoleForm extends Model
{
    public $id;
    public $name;

    public function attributeLabels(): array
    {
        return [
            'name' => 'Роль/профессия',
        ];
    }

    public function rules(): array
    {
        return [[['name'], 'safe']];
    }

    public function createRecord(): ?bool
    {
        if (!$this->validate()) {
            return null;
        }

        $role = new Role();
        $role->name = $this->name;

        return $role->save();
    }

    public function updateRecord(Role $role): ?bool
    {
        if (!$this->validate()) {
            return null;
        }

        $role->updateAttributes(['name' => $this->name]);

        return $role->save();
    }
}
