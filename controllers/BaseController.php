<?php

namespace app\controllers;

use yii\web;

use app\interfaces;
use function GuzzleHttp\json_encode;

/**
 * BaseController base conroller
 */
class BaseController extends web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Render users by platforms
     *
     * @param string $users
     * @param string $platforms
     * @return string
     */
    public function actionView(array $users, array $platforms)
    {
        $mplatforms = [];
        foreach ($platforms as $platform) {
            $mplatforms[] = \Yii::$app->factory->create($platform);
        }
        $users = \Yii::$app->searcher->search($mplatforms, $users);
        return $this->render('view', ['users' => $users]);
    }

    /**
     * Json response users by platforms
     *
     * @param string $users
     * @param string $platforms
     * @return string
     */
    public function actionApi(array $users, array $platforms)
    {
        $mplatforms = [];
        foreach ($platforms as $platform) {
            $mplatforms[] = \Yii::$app->factory->create($platform);
        }
        $users = \Yii::$app->searcher->search($mplatforms, $users);
        $response = [];
        foreach ($users as $user) {
            $response[] = $user->getData();
        }
        return $this->asJson($response);
    }
}
