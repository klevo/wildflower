<?php
    // What actions to show
    if (!isset($actions)) {
        $actions = array('Publish', 'Unpublish', 'Delete');
    }
    $actionsHtml = '';
    $separator =  ' &nbsp;|&nbsp; ';
    foreach ($actions as $action) {
        $actionsHtml .= $html->link(__($action, true), '#' . Inflector::camelize($action), array('rel' => low(Inflector::camelize($action))));
        $actionsHtml .= $separator;
    }
    $actionsHtml = rtrim($actionsHtml, $separator);
?>

<div class="select-actions">
    <?php __('Selection'); ?>: 
    
    <?php echo $actionsHtml; ?>
    
    <span>
        <a href="#SelectAll"><?php __('Select All'); ?></a> | <a href="#SelectNone"><?php __('None'); ?></a>
    </span>
</div>