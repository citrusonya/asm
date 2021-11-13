<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Role extends ActiveRecord
{
    /**
     * This is the model class for table "Role"
     *
     * @property int    $id   Идентификатор записи в таблице
     * @property string $name Наименование роли/профессии пользователя в системе
     * @property User[] $users
     */
    public const COMPUTER_TECHNICIAN = 1; # Специалист по компьютерным системам
    public const SECURITY_OFFICER = 2;    # Офицер безопасности
    public const MEDICAL_OFFICER = 3;     # Врач
    public const CAPTAIN = 4;             # Командир
    public const PILOT = 5;               # Пилот
    public const ENGINEER = 6;            # Техник
    public const ELECTRICIAN = 7;         # Электрик
    public const HYDROLOGIAN = 8;         # Гидролог
    public const FLIGHT_ENGINEER = 9;     # Бортинженер
    public const SUPPLIER = 10;           # Снабженец
    public const GARDENER = 11;           # Садовник
    public const COOK = 12;               # Повар
    public const CLEANER = 13;            # Уборщик
    public const GUEST = 0;               # Мимокрокодил

    public static $headRoleList = [
        self::COMPUTER_TECHNICIAN,
        self::SECURITY_OFFICER,
        self::CAPTAIN
    ];

    public static $commonRoleList = [
        self::MEDICAL_OFFICER,
        self::PILOT,
        self::ENGINEER,
        self::ELECTRICIAN,
        self::HYDROLOGIAN,
        self::FLIGHT_ENGINEER,
        self::SUPPLIER,
        self::GARDENER,
        self::COOK
    ];

    public static $limitedRightsRoleList = [
        self::CLEANER,
        self::GUEST
    ];

    public static function tableName()
    {
        return '{{%role}}';
    }

    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class'              => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value'              => gmdate('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * Get list of roles for creating dropdowns
     *
     * @param int|null $orgType
     * @return array
     */
    public static function dropdown(int $orgType = null): array
    {
        static $dropdown;

        if ($dropdown === null) {
            if (isset($orgType) && $orgType) {
                $models = static::findAll(['organization_type' => $orgType]);
            } else {
                $models = static::find()->all();
            }

            foreach ($models as $model) {
                $dropdown[$model->id] = $model->name;
            }
        }

        return $dropdown;
    }

    public static function getRoleName(int $roleId): string
    {
        $role = static::findOne(['id' => $roleId]);

        return $role->name ?? '';
    }
}
