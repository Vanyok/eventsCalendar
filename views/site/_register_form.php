<?php
/**
 * register form view
 */
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

$model = new \dektrium\user\models\RegistrationForm()
?>

<div class="row" id="register_form">
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Register</h3>
                <a class="form-close" onclick="$('#register_form').hide();"></a>
            </div>
            <div id="register-form-message" class="form-message">
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'registration-form',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => true,
                    'action' => ['/site/register'],
                    'validationUrl' => ['/user/registration/register'],
                ]); ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'username') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= Html::submitButton(Yii::t('user', 'Sign up'), ['class' => 'btn btn-success btn-block']) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

