<h2 class="section">Editing a widget</h2>

<?php
    echo $form->create('WildWidget', array('url' => '/' . Configure::read('Wildflower.prefix') . '/widgets/update', 'id' => 'edit_widget_form'));
    
    if (!isset($this->data['WildWidget']['items']) or empty($this->data['WildWidget']['items'])) {
        echo '<div class="slider_block">';
        echo '<h3>Cell 1</h3>';
        echo $form->input("WildWidget.items.0.label", array('type' => 'text', 'label' => 'Label'));
        echo $form->input("WildWidget.items.0.url", array('type' => 'text', 'label' => 'URL'));
        echo '</div>';
    } else {
        foreach ($this->data['WildWidget']['items'] as $i => $item) {
            if ($i == 'id') {
            }
            echo '<div class="slider_block">';
            echo '<h3>Cell ', $i + 1, '</h3>';
            echo $form->input("WildWidget.items.$i.label", array('type' => 'text', 'label' => 'Label'));
            echo $form->input("WildWidget.items.$i.url", array('type' => 'text', 'label' => 'URL'));
            echo '</div>';
        }
    }
    
    echo '<p>', $html->link('Add new cell', '#AddNewCell'), '</p>';

    echo $form->hidden('id');
    echo $form->end(__('Save', true));
?>
<div class="cancel-edit"> <?php __('or'); ?> <?php echo $html->link(__('Cancel', true), '#CancelWidgetEdit'); ?></div>