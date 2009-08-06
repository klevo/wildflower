<?php 
    echo $form->create('Post', array('url' => $html->url(array('action' => 'admin_update', 'base' => false)))); 
?>

<div id="post-categories">
    <ul>
    <?php
        $inCategories = Set::extract($this->data['Category'], '{n}.id');
    ?>
    <?php foreach ($categories as $id => $label) { ?>
        <?php
            $checked = '';
            if (in_array($id, $inCategories)) {
                $checked = ' checked="checked"';
            }
        ?>
        <li>
            <input id="CategoryCategory<?php echo $id ?>" type="checkbox" value="<?php echo $id ?>" name="data[Category][Category][]"<?php echo $checked ?> />
            <label for="CategoryCategory<?php echo $id ?>"><?php echo hsc($label) ?></label>
        </li>
    <?php } ?>
    </ul>
</div>

<div>
<?php
    echo $form->hidden('id');
?>
</div>

<?php echo $form->end(); ?>
