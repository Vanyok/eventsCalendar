<?php
/**
 * invite form view
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$model = new \app\models\InviteForm()
?>

<div class="row" id="invite_form">
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Send invitation</h3>
                <a class="form-close" onclick="$('#invite_form').hide();"></a>
            </div>
            <div id="invite-form-message" class="form-message">
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'invite-form',
                ]); ?>

                <?= $form->field($model, 'email') ?>

                <?= Html::button(Yii::t('user', 'Send'), ['class' => 'btn btn-success btn-block', 'type' => 'button', 'onclick' => 'sendInvite();']) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var sendInvite = function () {
            $("#invite-form-message").empty();
            $("#invite-form-message").hide();
            $("#loading").show();
            $.ajax({
                url: "<?php echo \yii\helpers\Url::toRoute('site/invite') ?>", // Url to which the request is send
                type: "POST",             // Type of request to be send, called as method
                data: $("#invite-form").serialize(), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                dataType: "json",
                success: function (data)   // A function to be called if request succeeds
                {
                    $("#loading").hide();
                    $("#invite-form-message").html(data.mess);
                    $("#invite-form-message").show();
                    return false;
                }
            });
            return false;
        };
    </script>
</div>