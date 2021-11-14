<?php

namespace frontend\controllers;

use common\models\User;
use common\models\Role;
use frontend\models\UserFilter;
use frontend\models\UserForm;
use Yii;
use yii\data\Pagination;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UserController extends Controller
{
    /**
     * Exception message not found User
     */
    public const NOT_FOUND_USER = 'Пользователь не найден, проверьте правильность введенных данных';
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
     * View page with all/filtered Users
     * @return string
     */
    public function actionIndex(): string
    {
        $filters = new UserFilter();
        $filters->load(Yii::$app->request->get());

        if ($sort = Yii::$app->request->get('sort')) {
            $sortDirection = [$sort => (Yii::$app->request->get('direction') === 'asc') ? SORT_ASC : SORT_DESC];
        }

        $users = User::getUsers($filters, $sortDirection ?? null);

        $pagination = new Pagination(
            [
                'defaultPageSize' => self::DEFAULT_ROLES_PAGINATION,
                'totalCount'      => $users->count(),
            ]
        );

        $users->offset($pagination->offset);
        $users->limit($pagination->limit);

        return $this->render(
            'index',
            [
                'users'      => $users->all(),
                'pagination' => $pagination,
                'filters'    => $filters,
            ]
        );
    }


    /**
     * View page for create new User
     */
    public function actionCreate()
    {
        $user = new UserForm();

        if ($user->load(Yii::$app->request->post()) && $user->createRecord()) {
            Yii::$app->session->setFlash('success', 'Сотрудник успешно добавлен');

            return $this->redirect(['../roles']);
        }

        $roles = Role::getPositions();

        return $this->render('create', [
            'user'  => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Action delete User
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    public function actionDelete($id): Response
    {
        $user = User::findOne($id);

        if (empty($user)) {
            throw new NotFoundHttpException(self::NOT_FOUND_USER, 404);
        }

        try {
            $user->delete();
        } catch (StaleObjectException | \Throwable $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return $this->goBack();
    }

    /**
     * View page of User
     * @throws NotFoundHttpException
     */
    public function actionView($id): string
    {
        $user = User::findOne($id);

        if (empty($user)) {
            throw new NotFoundHttpException(self::NOT_FOUND_USER, 404);
        }

        return $this->render('view', [
            'user' => $user,
        ]);
    }

    /**
     * View page for update User
     * @throws NotFoundHttpException|Exception
     */
    public function actionEdit($id)
    {
        $model = User::findOne($id);

        $userForm = new UserForm($model);

        if (empty($userForm)) {
            throw new NotFoundHttpException(self::NOT_FOUND_USER, 404);
        }

        if ($userForm->load(Yii::$app->request->post()) && $userForm->updateRecord($model)) {
            Yii::$app->session->setFlash('success', 'Данные о пользователе успешно обновлены');

            return $this->redirect(['../users']);
        }

        $roles = Role::getPositions();

        return $this->render('create', [
            'user'  => $userForm,
            'roles' => $roles,
        ]);
    }
}
