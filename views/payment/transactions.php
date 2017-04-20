<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Transactions for ' . $user['name'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($user['name']) ?></h1>

    <?php if (empty($user['transactions'])): ?>
        <p>
            Действий не обнаружено.
        </p>
    <?php else: ?>
        <p>
            <ul>
                <?php foreach ($user['transactions'] as $transaction): ?>
                    <li>
                        <?= $transaction['user_id'] ?> / <?= $transaction['amount'] ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </p>
    <?php endif; ?>
</div>
