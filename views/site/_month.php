<?php
/**
 * Created by PhpStorm.
 * User: vanyok
 * Date: 18.05.17
 * Time: 19:04
 *
 * calendar view
 */
use yii\web\View;

?>
<div class="calendar_header">
    <a class="btn btn-default" href="javascript:void(0)" onclick="getMonth('prev');"><<</a>
    <h2><?php echo date('M Y', strtotime($currDate)) ?></h2>
    <a class="btn btn-default" onclick="getMonth('next');" href="javascript:void(0)">>></a>
</div>
<div class="calendar_body" id="calendar_body">
    <div class="day_headers">
        <div class="day_header">
            Sunday
        </div>
        <div class="day_header">
            Monday
        </div>
        <div class="day_header">
            Tuesday
        </div>
        <div class="day_header">
            Wednesday
        </div>
        <div class="day_header">
            Thursday
        </div>
        <div class="day_header">
            Friday
        </div>
        <div class="day_header">
            Saturday
        </div>

    </div>
    <?php $startOfMonth = date('Y-m-', strtotime($currDate)) . '01';
    $startTime = strtotime($startOfMonth);
    $endTime = strtotime($startOfMonth . ' + 1 month');
    $endOfMonth = date('Y-m-d', $endTime);
    $iTime = $startTime;
    $startDay = date('w', $startTime);
    $d = 0;
    $oTime = strtotime(' -' . date('w', $iTime) . ' day', $startTime);
    ?>
    <div class="week_row">
        <?php
        while ($d < $startDay) {
            $oDate = date('Y-m-d', $oTime);
            ?>
        <div class="gray_day day_of_week<?php echo $d ?>" date="<?php echo $oDate ?>">
            <?php echo '<p>' . date('d', strtotime($oDate)) . '</p>'; ?>
            </div><?php
            $d++;
            $oTime = strtotime($oDate . ' + 1 day');
        }
        while ($iTime < $endTime) {
            $d = date('w', $iTime);
            $iDate = date('Y-m-d', $iTime);
            $events = \app\models\Event::getEventsByDate($iDate);
            if ($d == 0) {
                ?><div class="week_row"><?php
            }
            ?>
        <div class="white_day day_of_week<?php echo $d ?>" date="<?php echo $iDate ?>"
             id="day-<?php echo date('d', strtotime($iDate)) ?>">
            <?php echo '<p>' . date('d', strtotime($iDate)) . '</p>';
            foreach ($events as $event) {

                echo $this->render('_single_event', array('event' => $event, 'buttons' => ($iDate == date('Y-m-d', strtotime($event->date_end)))));
            }
            ?>
            </div><?php
            if ($d == 6) {
                ?>
                </div>
                <?php
            }
            $iTime = strtotime($iDate . ' + 1 day');

        }
        while ($d < 6) {
        $d++;
        $iDate = date('Y-m-d', $iTime);
        ?>
        <div class="gray_day day_of_week<?php echo $d ?>" date="<?php echo $iDate ?>">
            <?php echo '<p>' . date('d', strtotime($iDate)) . '</p>'; ?>
        </div><?php
        if ($d == 6){
        ?>
    </div>
<?php
}
$iTime = strtotime($iDate . ' + 1 day');
}
//enable droppable on days cells

$this->registerJs("jQuery( '.white_day' ).droppable( {
        hoverClass: 'hovered',
        drop: handleEventDrop
    } );", View::POS_END);

$this->registerJs("jQuery( '.gray_day' ).droppable( {
        hoverClass: 'hovered',
        drop: handleEventDrop
    } );", View::POS_END);
//enable draggable
$this->registerJs("jQuery( '.expand_icon' ).draggable( {
        containment: '#calendar_body',
        cursor: 'move',
        refreshPositions: true
    } );", View::POS_END);

$this->registerJs("jQuery( '.event_draggable' ).draggable( {
        containment: '#calendar_body',
        cursor: 'move',
        refreshPositions: true
    } );", View::POS_END);
$this->registerJs(
    ' var getMonth = function(direct){
                var pDate = "' . date('Y-m-d', strtotime("-1 month", strtotime($currDate))) . '";
                var nDate = "' . date('Y-m-d', strtotime("+1 month", strtotime($currDate))) . '";
                var dDate = "' . $currDate . '";
                var day;
                if(direct == "prev"){
                    day = pDate;
                }else if(direct == "next"){
                    day = nDate;
                }else{
                    day = dDate;
                }
                jQuery.ajax({
                    url: "' . \yii\helpers\Url::toRoute('site/month') . '", // Url to which the request is send
                    type: "POST",             // Type of request to be send, called as method
                    data: "day="+day, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                    dataType: "json",
                    success: function(data)   // A function to be called if request succeeds
                    {
                        jQuery("#loading").hide();
                        jQuery("#calendar").html(data.html);
                        return false;
                    }
                });
            };', View::POS_END)
?>

</div>
