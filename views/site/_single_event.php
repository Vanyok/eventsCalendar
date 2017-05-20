<?php
/**
 * Created by PhpStorm.
 * User: vanyok
 * Date: 19.05.17
 * Time: 13:53
 * single event view
 */


$can_update = !Yii::$app->user->isGuest && (Yii::$app->user->identity->getIsAdmin() || Yii::$app->user->id == $event->author->id);
$delete_html = ($can_update && $buttons) ? '<div class="delete_icons"><a class="delete_icon" title="delete" href="javascript:void(0);" onclick="removeEvent(' . $event->id . ');"></a><div class="expand_icon" title="drag me to expand" edit_type="end" e_id="' . $event->id . '"></div></div>' : '';
$tittle = $can_update ? 'click for details or drag to another date to change' : 'click for details';
$class = $can_update ? 'event_div event_draggable' : 'event_div ';
?>
<div title="<?php echo $tittle ?>" onclick='detailsOf(<?php echo $event->id ?>);' class='<?php echo $class ?>'
     id="event_<?php echo $event->id ?>"
     style='background-color: <?php echo $event->color ?>;color: <?php echo $event->getFontColor() ?>;'
     edit_type="start" e_id="<?php echo $event->id ?>">
    <?php echo '<div class="event_info"><span>' . $event->getStartEndTime() . "  " . $event->name . $event->getStatusMess() . '</span>' . $delete_html . '</div>' ?>
    <div class='event_details' id='event_details_<?php echo $event->id ?>'>
        <?php echo $event->description . "<p>" . $event->author->username . "</p>" ?>
    </div>
</div>