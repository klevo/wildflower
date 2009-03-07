<h2 class="section">Editing a widget</h2>

<?php
    echo $form->create('WildWidget', array('url' => '/' . Configure::read('Wildflower.prefix') . '/widgets/update', 'id' => 'edit_widget_form'));
    
    if (!isset($this->data['WildWidget']['item']) or empty($this->data['WildWidget']['item'])) {
        echo '<div class="slider_block">';
        echo '<h3>Cell 1</h3>';
        echo $form->input("WildWidget.0.label", array('type' => 'text', 'label' => 'Label'));
        echo $form->input("WildWidget.0.url", array('type' => 'text', 'label' => 'URL'));
        echo '</div>';
    } else {
        foreach ($this->data['WildWidget']['item'] as $i => $item) {
            echo '<div class="slider_block">';
            echo '<h3>Cell ', $i + 1, '</h3>';
            echo $form->input("WildWidget.label.$i", array('type' => 'text', 'label' => 'Label'));
            echo $form->input("WildWidget.url.$i", array('type' => 'text', 'label' => 'URL'));
            echo '</div>';
        }
    }
    
    echo '<p>', $html->link('Add new item', '#AddNewItem'), '</p>';

    echo $form->hidden('id');
    echo $form->end(__('Save', true));
?>
<div class="cancel-edit"> <?php __('or'); ?> <?php echo $html->link(__('Cancel', true), '#CancelWidgetEdit'); ?></div>