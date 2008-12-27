<?php 
    $id = $data['WildCategory']['id'];
    $label = $data['WildCategory']['title'];
    $checked = in_array($id, $inCategories) ? ' checked="checked"' : ''; 
?>
<input id="WildCategoryWildCategory<?php echo $id ?>" type="checkbox" value="<?php echo $id ?>" name="data[WildCategory][WildCategory][]"<?php echo $checked ?> />
<label for="WildCategoryWildCategory<?php echo $id ?>"><?php echo hsc($label) ?> <?php echo $html->link('<span>Trash</span>', array('controller' => 'wild_categories', 'action' => 'delete', $id), array('class' => 'trash', 'escape' => false, 'title' => __('Delete this category', true))); ?></label>