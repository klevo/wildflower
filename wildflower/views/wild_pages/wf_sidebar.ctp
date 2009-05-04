<?php
    echo 
    $form->create('WildPage', array('url' => $html->url(array('action' => 'update', 'base' => false)), 'class' => 'horizontal-form')),
    '<div>',
    $form->hidden('id'),
    $form->hidden('draft'),
    '</div>';
?>

<h2 class="section">Sidebar for <?php echo $html->link($this->data['WildPage']['title'], array('action' => 'edit', $this->data['WildPage']['id'])); ?></h2>
<?php
    echo 
    $form->input('sidebar_content', array(
        'type' => 'textarea',
        'class' => 'tinymce',
        'rows' => 25,
        'cols' => 60,
        'label' => false,
        'div' => array('class' => 'input editor')));
?>

<div>
    <div class="submit save-section">
        <input type="submit" value="<?php __('Save as the newest version'); ?>" />
    </div>
    <div class="cancel-edit"> <?php __('or'); ?> <?php echo $html->link(__('Cancel', true), array('action' => 'edit', $this->data['WildPage']['id'])); ?></div>
</div>

<?php 
    echo 
    $form->end();
?>


<?php $partialLayout->blockStart('sidebar'); ?>
    <?php echo $this->element('../wild_pages/_page_edit_right_menu'); ?>
<?php $partialLayout->blockEnd(); ?>