<?php

namespace frontend\controllers;

use common\models\Role;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class RoleApiController extends ActiveController
{
    public $modelClass = Role::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'update', 'delete'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class,
        ];

        return $behaviors;
    }
}