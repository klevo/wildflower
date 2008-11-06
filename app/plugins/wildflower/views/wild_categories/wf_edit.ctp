<?php
echo $navigation->create(array(
        'All categories' => array('action' => 'index')
    ), array('id' => 'sub-nav', 'class' => 'always-current'));
?>

<h3>Editing a category</h3>
<?php
    echo 
    $form->create('WildCategory', array('url' => $html->url(array('action' => $this->params['action'], $this->params['pass'][0], 'base' => false)))),
    $form->input('title', array('between' => '<br />', 'size' => '50'));
	if (!empty($parentCategories)) {
        echo $form->input('parent_id', array('type' => 'select', 'options' => $parentCategories, 'empty' => '(none)', 'between' => '<br />'));
    }
    echo 
    $form->input('description', array('between' => '<br />', 'type' => 'textbox', 'cols' => '48', 'rows' => '6')),
    $form->hidden('id'),
    $wild->submit('Save'),
    $form->end();
?>
