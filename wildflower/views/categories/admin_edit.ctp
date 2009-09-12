<h2 class="section"><?php echo __('Category:', true); ?> <?php echo hsc($this->data['Category']['title']); ?></h2>

<?php
    echo 
    $form->create('Category', array('url' => $html->url(array('action' => $this->params['action'], $this->params['pass'][0], 'base' => false)))),
    $form->input('title', array('between' => '<br />', 'size' => '50')),
    $form->input('slug', array('between' => '<br />', 'size' => '50'));
	if (!empty($parentCategories)) {
        echo $form->input('parent_id', array('type' => 'select', 'options' => $parentCategories, 'empty' => '(none)', 'between' => '<br />'));
    }
    echo 
    $form->input('description', array('between' => '<br />', 'type' => 'textbox', 'cols' => '48', 'rows' => '6')),
    $form->hidden('id'),
    $form->end('Save changes');
?>

<p class="cancel-edit"><?php echo $html->link(__('Cancel', true), array('action' => 'index')); ?></p>
