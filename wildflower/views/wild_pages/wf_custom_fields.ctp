<?php
    echo 
    $form->create('WildPage', array('url' => $here, 'type' => 'file'));
?>

<h2 class="section">Custom fields for <?php echo $html->link($this->data['WildPage']['title'], array('action' => 'edit', $this->data['WildPage']['id'])); ?></h2>

<?php 
    if (empty($customFields)) {
        echo '<p>', __('This page has no custom fields defined.', true), '</p>';
    } else {
        foreach ($customFields as $field) {
            echo $form->input($field['name'], array('type' => $field['type'], 'value' => $field['value'], 'between' => '<br />'));
        }
    }
?>

<?php
    echo 
    $form->end('Save');
?>

