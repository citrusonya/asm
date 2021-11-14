<?php

namespace frontend\models;

use api_web\exceptions\ValidationException;
use common\models\User;
use Throwable;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\db\Exception;
use yii\web\BadRequestHttpException;

/**
 * Sign Up form
 */
class UserForm extends Model
{
    public $id;
    public $username;
    public $roleId;
    public $phone;
    public $email;
    public $password;
    public $organizationId;

    public function attributeLabels(): array
    {
        return [
            'username'        => 'Имя пользователя',
            'role_id'         => 'Роль/профессия',
            'phone'           => 'Номер телефона',
            'email'           => 'Email пользователя',
            'organization_id' => 'Идентификатор организации',
            'created_at'      => 'Дата создания записи'
        ];
    }

    public function rules(): array
    {
        return [

            [['username'], 'safe'],
            [['name'], 'required', 'message' => 'Введите имя'],
            ['name', 'string', 'max' => 255, 'tooLong' => 'Имя не должно превышать 255 символов'],
        ];
    }

    /**
     * @throws BadRequestHttpException
     */
    public function createRecord(): ?bool
    {
        try {
            if (!$this->validate()) {
                throw new ValidationException('Ошибка валидации полей');
            }

            $user = new User();

            $user->setAttributes(
                [
                    'name'            => $this->username,
                    'role_id'         => $this->roleId,
                    'phone'           => $this->phone,
                    'email'           => $this->email,
                    'password'        => $this->password,
                    'organization_id' => $this->organizationId,
                    'created_at'      => gmdate('Y-m-d H:i:s'),
                ]
            );

            return $user->save();
        } catch (InvalidArgumentException | ValidationException $e) {
            throw new BadRequestHttpException("Ошибка создания пользователя: {$e->getMessage()}");
        } catch (Throwable $t) {
            throw new BadRequestHttpException("Ошибка создания пользователя: {$t->getName()}: {$t->getLine()}");
        }
    }

    /**
     * @throws Exception
     */
    public function updateRecord(User $user): ?bool
    {
        try {
            if (!$this->validate()) {
                return null;
            }

            if (!empty($username)) {
                $user->updateAttributes(['role_id' => $this->username]);
            }

            if (!empty($roleId)) {
                $user->updateAttributes(['role_id' => $this->roleId]);
            }

            if (!empty($phone)) {
                $user->updateAttributes(['role_id' => $this->phone]);
            }
            if (!empty($organizationId)) {
                $user->updateAttributes(['role_id' => $this->organizationId]);
            }

            $user->updateAttributes(['updated_at' => gmdate('Y-m-d H:i:s')]);

            return $user->save();
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
