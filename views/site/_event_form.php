<?php
/**
 * Created by PhpStorm.
 * User: vanyok
 * Date: 18.05.17
 * Time: 19:34
 *
 * form for event
 */
$this->registerJsFile(
    '@web/js/jquery.tinycolorpicker.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerJsFile(
    '@web/js/jquery.timepicker.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$model = new \app\models\EventForm();
$model->date_start = date('Y-m-d');
$model->date_end = date('Y-m-d');
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\web\View;
use yii\widgets\ActiveForm; ?>
    <div class="row" id="event_form">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">New event</h3>
                    <a class="form-close" onclick="$('#event_form').hide();"></a>
                </div>
                <div id="event-form-message" class="form-message">
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'event-form',
                        'enableAjaxValidation' => true,
                        'enableClientValidation' => true,
                        'validateOnBlur' => false,
                        'validateOnType' => false,
                        'validateOnChange' => true,
                        'validationUrl' => \yii\helpers\Url::toRoute('events/validate'),
                    ]) ?>

                    <?= $form->field($model, 'name')->textInput() ?>
                    <?= $form->field($model, 'description')->textarea() ?>
                    <?= $form->field($model, 'date_start')->widget(DatePicker::classname(), [
                        'options' => ['minDate' => date('Y-m-d')]
                    ]) ?>
                    <?= $form->field($model, 'hrs_start')->input('text', ['class' => 'timepicker_input']) ?>
                    <?= $form->field($model, 'date_end')->widget(DatePicker::classname(), [
                        'options' => ['minDate' => date('Y-m-d')]
                    ]) ?>
                    <?= $form->field($model, 'hrs_end')->input('text', ['class' => 'timepicker_input']) ?>
                    <label for="colorPicker">Color of the event:</label>
                    <div id="colorPicker">
                        <a class="color">
                            <div class="colorInner"></div>
                        </a>
                        <div class="track"></div>
                        <ul class="dropdown">
                            <li></li>
                        </ul>
                        <input type="hidden" name="EventForm[color]" class="colorInput" value=""/>
                    </div>
                    <?= Html::button(
                        'Create',
                        ['class' => 'btn btn-primary btn-block', 'tabindex' => '4', 'type' => 'button', 'onclick' => 'createCEvent();']
                    ) ?>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>

            <script type="text/javascript">

            </script>
        </div>
    </div>
<?php
/* register scripts for widgets*/
$this->registerJs(
    'jQuery(".timepicker_input").timepicker();
    jQuery("#colorPicker").tinycolorpicker();',
    View::POS_READY,
    'tpicker-handler'
);
?>