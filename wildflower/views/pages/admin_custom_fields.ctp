<?php
    echo 
    $form->create('Page', array('url' => $here, 'type' => 'file'));
?>

<h2 class="section">Custom fields for <?php echo $html->link($this->data['Page']['title'], array('action' => 'edit', $this->data['Page']['id'])); ?></h2>

<?php 
    if (empty($customFields)) {
        echo '<p>', __('This page has no custom fields defined.', true), '</p>';
    } else {
		$mprefix = Configure::read('Wildflower.mediaRoute');
        foreach ($customFields as $field) {
            echo $form->input($field['name'], array('type' => $field['type'], 'value' => $field['value'], 'between' => '<br />'));
            if ($field['type'] == 'file' and !empty($field['value'])) {
                echo '<img width="80" height="80" src="', $html->url("/$mprefix/thumbnail/{$field['value']}/80/80/1"), '" alt="" />';
            }
        }
    }
?>

<?php
    echo 
    $form->end('Save');
?>

