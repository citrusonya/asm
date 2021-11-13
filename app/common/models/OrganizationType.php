<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class OrganizationType extends ActiveRecord
{
    /**
     * This is the model class for table "organization_type"
     *
     * @property int            $id   Идентификатор записи в таблице
     * @property string         $name Наименование типа организации в системе
     * @property Organization[] $organizations
     */

    public const STATION = 1;           # Станция
    public const STARSHIP = 2;          # Звездолет
    public const MILITARY_STARSHIP = 3; # Военный корабль
    public const CARGO_STARSHIP = 4;    # Грузовой корабль
    public const GENERATION_SHIP = 5;   # Корабль поколений
    public const REST_POINT = 6;        # Станция для восстановления сил и пополнения запасов
    public const CONTROL_POINT = 7;     # Контрольная точка

    public static $stations = [
        self::STATION,
        self::REST_POINT,
        self::CONTROL_POINT,
    ];

    public static $starships = [
        self::STARSHIP,
        self::MILITARY_STARSHIP,
        self::CARGO_STARSHIP,
        self::GENERATION_SHIP,
    ];

    public static function tableName(): string
    {
        return '{{%organization_type}}';
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function getOrganizations(): ActiveQuery
    {
        return $this->hasMany(Organization::className(), ['type_id' => 'id']);
    }

    /**
     * Array of all organization types
     * @return array
     */
    public static function getList()
    {
        $models = OrganizationType::find()
            ->select(['id', 'name'])
            ->asArray()
            ->all();

        return ArrayHelper::map($models, 'id', 'name');
    }
}
