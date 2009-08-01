<?php 
    $id = $data['Page']['id'];
    $label = $data['Page']['title'];
    $checked = in_array($id, $inPages) ? ' checked="checked"' : ''; 
?>
<input id="PagePage<?php echo $id ?>" type="checkbox" value="<?php echo $id ?>" name="data[Page][Page][]"<?php echo $checked ?> />
<label for="PagePage<?php echo $id ?>"><?php echo hsc($label) ?></label>