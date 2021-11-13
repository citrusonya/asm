<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Organization extends ActiveRecord
{
    /**
     * This is the model class for table "organization"
     *
     * @property int              $id                            Идентификатор записи в таблице
     * @property int              $typeId                        Идентификатор типа организации
     * @property string           $name                          Название организации
     * @property int              $phone                         Телефон организации
     * @property string           $email                         Email организации
     * @property string           $createdAt                     Дата и время создания записи в таблице
     * @property string           $updatedAt                     Дата и время последнего изменения записи в таблице
     * @property string           $logo                          Логотип организации
     * @property int              $rating                        Рейтинг организации
     * @property string           $country                       Страна организации
     * @property int              $engineerId                    Идентификатор техника, ответственного за обслуживание организации
     * @property int              $gmt                           Временная зона по Гринвичу GMT
     * @property string           $lang                          Основной язык, используемый на территории организации
     * @property array|null       $additionalFields              Дополнительные сведения
     * @property boolean          $blacklisted                   Показатель нахождения организации в "чёрном списке"
     * @property boolean          $isDeleted                     Показатель, удалена ли организация
     * @property OrganizationType $type                          Идентификатор типа организации
     * @property Organization     $parent
     * @property User             $engineer
     * @property User[]           $users
     * @property mixed            $pictureUrl
     */

    public const NOT_DELETED = 0;
    public const IS_DELETED = 1;

    public const NOT_BLACKLISTED = 0;
    public const BLACKLISTED = 1;

    public static function tableName(): string
    {
        return '{{%organization}}';
    }

    public function rules(): array
    {
        return [
            [
                'name',
                'required',
                'on'      => ['complete', 'settings'],
                'message' => ('Пожалуйста, заполните название'),
            ],
            [['rating'], 'default', 'value' => 0],
            [['type_id'], 'required'],
            [['id', 'type_id', 'phone', 'rating', 'engineer_id', 'gmt'], 'integer'],
            [['created_at', 'updated_at', 'additional_fields'], 'safe'],
            [['name', 'email', 'logo', 'country'], 'string', 'max' => 255],
            [['lang'], 'string', 'max' => 10],
            [
                ['engineer_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => User::class,
                'targetAttribute' => ['engineer_id' => 'id'],
            ],
            [
                ['type_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => OrganizationType::class,
                'targetAttribute' => ['type_id' => 'id'],
            ],
            [['logo'], 'image', 'extensions' => 'jpg, jpeg, gif, png', 'on' => ['settings', 'logo']],
            [['blacklisted', 'is_deleted'], 'boolean'],
            [
                ['name', 'country'],
                'required',
                'when' => static function (self $model) {
                    return $model->typeId === OrganizationType::$stations;
                }
            ],
        ];
    }

    public function behaviors(): array
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'timestamp' => [
                    'class'              => TimestampBehavior::class,
                    'createdAtAttribute' => 'created_at',
                    'updatedAtAttribute' => 'updated_at',
                    'value'              => gmdate('Y-m-d H:i:s')
                ],
            ]
        );
    }

    public static function getInfo($id): ?array
    {
        $model = Organization::findOne($id);

        if (!empty($model)) {
            return $model->attributes;
        }

        return null;
    }

    public function getType(): ActiveQuery
    {
        return $this->hasOne(OrganizationType::class, ['id' => 'type_id']);
    }

    public function isStation()
    {
        return in_array($this->type_id, OrganizationType::$stations);
    }

    public function isStarship(): bool
    {
        return in_array($this->type_id, OrganizationType::$starships);
    }
}
