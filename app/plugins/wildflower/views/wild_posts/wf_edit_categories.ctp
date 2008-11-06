<?php 
    echo $form->create('WildPost', array('url' => $html->url(array('action' => 'wf_update', 'base' => false)))); 
?>

<div id="post-categories">
    <ul>
    <?php
        $inCategories = Set::extract($this->data['WildCategory'], '{n}.id');
    ?>
    <?php foreach ($categories as $id => $label) { ?>
        <?php
            $checked = '';
            if (in_array($id, $inCategories)) {
                $checked = ' checked="checked"';
            }
        ?>
        <li>
            <input id="WildCategoryWildCategory<?php echo $id ?>" type="checkbox" value="<?php echo $id ?>" name="data[WildCategory][WildCategory][]"<?php echo $checked ?> />
            <label for="WildCategoryWildCategory<?php echo $id ?>"><?php echo hsc($label) ?></label>
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
