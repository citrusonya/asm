<?php

namespace common\models;

use frontend\models\UserFilter;
use Yii;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "User"
 *
 * @property integer $id                 Идентификатор записи в таблице
 * @property string  $email              Email пользователя
 * @property integer $phone              Телефон пользователя
 * @property string  $username           Имя пользователя
 * @property string  $password           Пароль пользователя
 * @property integer $roleId             Идентификатор роли/профессии
 * @property integer $organizationId     Идентификатор организации, к которой прикреплен пользователь
 * @property string  $language           Основной язык пользователя
 * @property bool    $status             Статус активности пользователя
 * @property string  $authKey            Ключ аутентификации
 * @property string  $accessToken        Токен пользователя
 * @property string  $createdAt          Дата и время создания записи в таблице
 * @property string  $updatedAt          Дата и время последнего изменения записи в таблице
 * @property string  $bannedAt           Дата и время бана
 * @property string  $bannedReason       Причина бана
 * @property bool    $smsSubscribe       Подписка на sms информирование
 * @property bool    $emailSubscribe     Подписка на email информирование
 * @property string  $noticeTimeFromTime Время, со скольки можно информировать
 * @property string  $noticeTimeToTime   Время, до скольки можно информировать
 * @property Role    $roleName           Название роли
 */

class User extends ActiveRecord implements IdentityInterface
{
    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;

    public const NOT_BANNED = 0;
    public const BANNED = 1;

    public const SMS_SUBSCRIBE = 1;
    public const EMAIL_SUBSCRIBE = 1;

    public static function tableName(): string
    {
        return '{{%user}}';
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

    /**
     * Get Employees with (optional)filtration and (optional)sort
     * @param UserFilter $filters
     * @param null       $sortDirection
     * @return ActiveQuery
     */
    public static function getUsers(UserFilter $filters, $sortDirection = null): ActiveQuery
    {
        $users = self::find()
            ->andFilterWhere(['ilike', 'name', $filters->search])
            ->andFilterWhere(['role_id' => $filters->roles]);

        if  ($sortDirection) {
            $users->orderBy($sortDirection);
        }

        return $users;
    }

    public function rules(): array
    {
        return [
            [
                ['email', 'username', 'password', 'language', 'auth_key', 'access_token', 'banned_reason'],
                'string',
                'max' => 255
            ],
            [['phone', 'role_id', 'organization_id'], 'integer'],
            ['email', 'filter', 'filter' => 'strtolower'],
            [
                ['email', 'username'],
                'unique',
                'on'      => ['register', 'admin', 'manage', 'manageNew'],
                'message' => 'Данный email уже зарегистрирован в системе',
            ],
            [['email', 'username'], 'filter', 'filter' => 'trim'],
            [
                ['username'],
                'match',
                'pattern' => '/^\w+$/u',
                'except'  => 'social',
                'message' => ('Имя должно содержать только буквы, числа и "_"')
            ],
            [
                ['newPasswordConfirm'],
                'compare',
                'compareAttribute' => 'newPassword',
                'message'          => ('Пароль не соответствует правилам')
            ],
            [['email'], 'required', 'message' => ('Введите email')],
            [['role_id'], 'required', 'on' => ['admin', 'manage', 'manageNew']],
            [['banned_reason'], 'string', 'max' => 255, 'on' => 'admin'],
            [['sms_subscribe', 'email_subscribe', 'status'], 'boolean'],
            [['organization_id','type'],'integer'],
            [
                ['organization_id'],
                'exist',
                'skipOnEmpty'     => true,
                'targetClass'     => Organization::class,
                'targetAttribute' => 'id',
                'allowArray'      => false,
                'message'         => ('Организация не найдена')
            ],
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername(string $username): ?User
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken(string $token): ?User
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'access_token' => $token,
            'status'       => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken(string $token): ?User
    {
        return static::findOne([
            'verification_token' => $token,
            'status'             => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid(string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey(): ?string
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey): ?bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws Exception
     */
    public function setPassword(string $password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     * @throws Exception
     */
    public function generateAuthKey()
    {
        $this->authKey = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     * @throws Exception
     */
    public function generateAccessToken()
    {
        $this->accessToken = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     * @throws Exception
     */
    public function generateEmailVerificationToken()
    {
        $this->accessToken = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->accessToken = null;
    }

    public function setRole(int $roleId): User
    {
        $this->roleId = $roleId;
        $this->save();

        return $this;
    }

    public function getConsentOnNotice(): bool
    {
        return $this->smsSubscribe && $this->emailSubscribe;
    }

    public static function isHeadRole(): bool
    {
        return in_array($this->roleId, Role::$headRoleList);
    }

    public static function isCommonRole(): bool
    {
        return in_array($this->roleId, Role::$commonRoleList);
    }

    public static function getRoleName(): string
    {
        $model = Role::findOne($this->roleId);

        return $model->name;
    }
}
