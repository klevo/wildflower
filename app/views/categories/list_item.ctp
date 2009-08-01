<?php 
    $id = $data['Category']['id'];
    $label = $data['Category']['title'];
    $checked = in_array($id, $inCategories) ? ' checked="checked"' : ''; 
?>
<input id="CategoryCategory<?php echo $id ?>" type="checkbox" value="<?php echo $id ?>" name="data[Category][Category][]"<?php echo $checked ?> />
<label for="CategoryCategory<?php echo $id ?>"><?php echo hsc($label) ?> <?php echo $html->link('<span>Trash</span>', array('controller' => 'categories', 'action' => 'delete', $id), array('class' => 'trash', 'escape' => false, 'title' => __('Delete this category', true))); ?></label>