<?php

namespace frontend\models;

use common\models\Role;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use common\models\User;
use yii\web\BadRequestHttpException;

/**
 * Sign Up form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $phone;
    public $roleId;
    public $organizationId;

    public function rules(): array
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     * @throws Exception
     */
    public function signup(): ?bool
    {
        if (!$this->validate()) {
            throw new Exception('Ошибка валидации полей');
        }

        $user = new User();
        $user->username = $this->username;
        $user->status = User::STATUS_ACTIVE;
        $user->email = $this->email ?? null;
        $user->phone = $this->phone ?? null;
        $user->organizationId = $this->organizationId ?? null;
        $user->roleId = $this->roleId ?? Role::GUEST;

        $user->setPassword($this->password);

        $user->generateAuthKey();
        $user->generateAccessToken();
        $user->generateEmailVerificationToken();

        try {
            return $user->save();
        } catch (\Exception $e) {
            throw new BadRequestHttpException("Ошибка регистрации: {$e->getMessage()}");
        }
    }

    /**
     * Sends confirmation email to user
     *
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail(User $user): bool
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
