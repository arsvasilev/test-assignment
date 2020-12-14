<?php

/* @var $this yii\web\View */
/* @var $users array */

use yii\helpers\Html;

?>

<div>
    <?php foreach ($users as $user) : ?>
        <div style="font: 14px consolas; white-space:pre;"><?= Html::encode($user) ?><hr/></div>
    <?php endforeach; ?>
</div>
