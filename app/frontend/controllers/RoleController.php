<?php

namespace frontend\controllers;

use common\models\Role;
use frontend\models\RoleForm;
use Throwable;
use Yii;
use yii\data\Pagination;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class RoleController extends Controller
{
    /**
     * Exception message not found Position
     */
    public const NOT_FOUND_ROLE = 'Роль не найдена, проверьте правильность введенных данных';

    /**
     * Default quantity of positions to view per page
     */
    private const DEFAULT_ROLES_PAGINATION = 10;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'edit', 'delete', 'create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index', 'view', 'edit', 'delete', 'create'],
                        'allow' => false,
                        'roles' => ['@'],
                        'denyCallback' => function ($rule, $action) {
                            return $action->controller->redirect('/login');
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * View page with all Positions
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $roles = Role::find();

        $pagination = new Pagination(
            [
                'defaultPageSize' => self::DEFAULT_ROLES_PAGINATION,
                'totalCount' => $roles->count(),
            ]
        );

        $roles->offset($pagination->offset);
        $roles->limit($pagination->limit);

        return $this->render(
            'index',
            [
                'positions' => $roles->all(),
                'pagination' => $pagination,
            ]
        );
    }


    /**
     * View page for create new Position
     */
    public function actionCreate()
    {
        $role = new RoleForm();

        if ($role->load(Yii::$app->request->post()) && $role->createRecord()) {
            Yii::$app->session->setFlash('success', 'Роль успешно добавлена');

            return $this->redirect(['../roles']);
        }

        return $this->render('create', [
            'role' => $role,
        ]);
    }

    /**
     * Action delete Position
     *
     * @throws NotFoundHttpException
     * @throws StaleObjectException|BadRequestHttpException
     */
    public function actionDelete($id): Response
    {
        $model = Role::findOne($id);

        if (empty($model)) {
            throw new NotFoundHttpException(self::NOT_FOUND_ROLE, 404);
        }

        try {
            $model->delete();
        } catch (StaleObjectException $e) {
            throw new StaleObjectException($e->getMessage());
        } catch (Throwable $t) {
            throw new BadRequestHttpException($t->getMessage());
        }

        return $this->goBack();
    }

    /**
     * View page of Position
     *
     * @throws NotFoundHttpException
     */
    public function actionView($id): string
    {
        $model = Role::findOne($id);

        if (empty($model)) {
            throw new NotFoundHttpException(self::NOT_FOUND_ROLE, 404);
        }

        return $this->render('view', [
            'role' => $model,
        ]);
    }

    /**
     * View page for update Position
     *
     * @throws NotFoundHttpException
     */
    public function actionEdit($id)
    {
        $model = Role::findOne($id);
        $roleForm = new RoleForm($model);

        if (empty($model)) {
            throw new NotFoundHttpException(self::NOT_FOUND_ROLE, 404);
        }

        if ($roleForm->load(Yii::$app->request->post()) && $roleForm->updateRecord($model)) {
            Yii::$app->session->setFlash('success', 'Роль успешно обновлена');

            return $this->redirect(['../roles']);
        }

        return $this->render('create', [
            'role' => $roleForm,
        ]);
    }
}
