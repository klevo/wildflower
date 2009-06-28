<?php 
    $id = $data['WildPage']['id'];
    $label = $data['WildPage']['title'];
    $checked = in_array($id, $inPages) ? ' checked="checked"' : ''; 
?>
<input id="WildPageWildPage<?php echo $id ?>" type="checkbox" value="<?php echo $id ?>" name="data[WildPage][WildPage][]"<?php echo $checked ?> />
<label for="WildPageWildPage<?php echo $id ?>"><?php echo hsc($label) ?></label>