<?php
echo $navigation->create(array(
        'Show All' => array('action' => 'index')
    ), array('id' => 'sub-nav', 'class' => 'always-current'));
?>

<div id="category-form">
    <h3><?php echo __('Editing') ?><em><?php echo hsc($this->data['Category']['title']) ?></em> <?php echo __('category') ?></h3>
    <?php
        echo 
        $form->create('Category'),
        '<div class="options-left">',
        $form->input('title', array('between' => '<br />', 'label'=>__('Title', true)));
        if (!empty($parentCategories)) {
            echo $form->label('parent_id', __('Parent category', true)), 
                '<br />',
                $form->select('parent_id', $parentCategories);
        }
        echo 
        '</div>',
        '<div class="options-right">',
        $form->input('Description', array('between' => '<br />', 'type' => 'textbox', 'label'=>__('Description', true))),
        '</div>',
        $form->submit(__('Save', true)),
        $form->hidden('id'),
        $form->end();
    ?>
</div>