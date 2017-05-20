<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
$this->registerJsFile(
    '@web/js/jquery-ui/jquery-ui.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerCssFile(
    '@web/js/jquery-ui/jquery-ui.min.css'
)
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Events calendar',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Calendar', 'url' => ['/site/calendar'], 'linkOptions' => ['onclick' => '']],

            !Yii::$app->user->isGuest ? (
            ['label' => 'Create event', 'url' => 'javascript: void(0);', 'linkOptions' => ['onclick' => '$("#event_form").toggle()']]) : '',
            !Yii::$app->user->isGuest && Yii::$app->user->identity->getIsAdmin() ? (
            ['label' => 'Send Invitation', 'url' => 'javascript: void(0);', 'linkOptions' => ['onclick' => '$("#invite_form").toggle()']]) : '',
            Yii::$app->user->isGuest ? (
            ['label' => 'Login', 'url' => 'javascript: void(0);', 'linkOptions' => ['onclick' => '$("#login_form").toggle()']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Ivan.K <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>
<div id="loading"></div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
