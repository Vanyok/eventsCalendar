<?php
/**
 * login form view
 */
use dektrium\user\models\LoginForm;
use dektrium\user\widgets\Connect;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$model = new LoginForm(new \dektrium\user\Finder());
$this->render('/_alert', ['module' => Yii::$app->getModule('user')]);
?>

<div class="row" id="login_form">
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Sign in</h3>
                <a class="form-close" onclick="$('#login_form').hide();"></a>
            </div>
            <div id="login-form-message" class="form-message">
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                    'validateOnBlur' => false,
                    'validateOnType' => false,
                    'validateOnChange' => false,
                    'action' => ['/user/security/login']
                ]) ?>

                <?= $form->field($model, 'login',
                    ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']]
                );
                ?>

                <?= $form->field(
                    $model,
                    'password',
                    ['inputOptions' => ['class' => 'form-control', 'tabindex' => '2']])
                    ->passwordInput()
                    ->label(
                        Yii::t('user', 'Password')
                        . (
                            ' (' . Html::a(
                                Yii::t('user', 'Forgot password?'),
                                ['/user/recovery/request'],
                                ['tabindex' => '5']
                            )
                            . ')')
                    ) ?>


                <?= $form->field($model, 'rememberMe')->checkbox(['tabindex' => '3']) ?>

                <?= Html::submitButton(
                    Yii::t('user', 'Sign in'),
                    ['class' => 'btn btn-primary btn-block', 'tabindex' => '4']
                ) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>


        <?= Connect::widget([
            'baseAuthUrl' => ['/user/security/auth'],
        ]) ?>
    </div>
</div>