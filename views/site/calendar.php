<?php
/**
 * main calendar page
 */
/* @var $this yii\web\View */

$this->title = 'Events Calendar';
?>
<div class="site-index">
    <?php if (isset($message)) {
        echo $this->render('message', array('message' => $message));
    } ?>
    <div class="header">
        <h1>Events calendar!</h1>
        <div class="forms">
            <?php if (!Yii::$app->user->isGuest) { ?>
                <?php echo $this->render('_event_form', array());
                echo $this->render('_invite_form', array());
            } ?>
            <?php echo $this->render('_login_form', array());
            ?>
            <?php if ($register) echo $this->render('_register_form', array());
            ?>

        </div>
    </div>
    <!-- For registered users-->

</div>

<div class="body-content">
    <div id="calendar">
        <?php echo $this->render('_month', array(
            'currDate' => $currDate
        ));
        ?>
    </div>
    <!-- scripts for event management -->
    <script type="text/javascript">

        var detailsOf = function (id) {
            jQuery("#event_details_" + id).toggle();
        };

        var handleEventDrop = function (event, ui) {
            jQuery('#loading').show();
            var dayDate = $(this).attr('date');
            var etype = ui.draggable.attr('edit_type');
            var eventId = ui.draggable.attr('e_id');
            jQuery.ajax({
                url: "<?php echo \yii\helpers\Url::toRoute('events/update'); ?>", // Url to which the request is send
                type: "POST",             // Type of request to be send, called as method
                data: "dayDate=" + dayDate + "&eventId=" + eventId + "&type=" + etype, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                dataType: 'json',
                success: function (data)   // A function to be called if request succeeds
                {
                    jQuery('#loading').hide();
                    getMonth('this');
                    return false;
                }
            });
            return true;
        };

        var removeEvent = function (id) {
            jQuery('#loading').show();
            jQuery.ajax({
                url: "<?php echo \yii\helpers\Url::toRoute('events/remove'); ?>", // Url to which the request is send
                type: "POST",             // Type of request to be send, called as method
                data: "eventId=" + id, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                dataType: 'json',
                success: function (data)   // A function to be called if request succeeds
                {
                    jQuery('#loading').hide();
                    getMonth('this');
                    return false;
                }
            });
            return true;
        };

        var createCEvent = function () {
            $("#event-form-message").empty();
            $("#event-form-message").hide();
            $("#loading").show();
            $.ajax({
                url: "<?php echo \yii\helpers\Url::toRoute('events/create') ?>", // Url to which the request is send
                type: "POST",             // Type of request to be send, called as method
                data: $("#event-form").serialize(), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                dataType: "json",
                success: function (data)   // A function to be called if request succeeds
                {
                    $("#loading").hide();
                    $("#event-form-message").html(data.mess);
                    $("#event-form-message").show();
                    getMonth('this');
                    return false;
                }
            });
            return false;
        };
    </script>
</div>
</div>
