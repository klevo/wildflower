<h2 class="section">Editing a widget</h2>

<?php
    echo $form->create('WildWidget', array('url' => '/' . Configure::read('Wildflower.prefix') . '/widgets/update', 'id' => 'edit_widget_form'));
    
    if (!isset($this->data['WildWidget']['item']) or empty($this->data['WildWidget']['item'])) {
        echo '<div class="slider_block">';
        echo $form->input("WildWidget.0.label", array('type' => 'text', 'label' => 'Item 1 Label'));
        echo $form->input("WildWidget.0.url", array('type' => 'text', 'label' => 'Item 1 URL'));
        echo '</div>';
    } else {
        foreach ($this->data['WildWidget']['item'] as $i => $item) {
            echo '<div class="slider_block">';
            echo $form->input("WildWidget.label.$i", array('type' => 'text', 'label' => 'Item ' . ($i + 1) . ' Label'));
            echo $form->input("WildWidget.url.$i", array('type' => 'text', 'label' => 'Item ' . ($i + 1) . ' URL'));
            echo '</div>';
        }
    }
    
    echo $html->link('Add new item', '#AddNewItem');

    echo $form->hidden('id');
    echo $form->end(__('Save', true));
?>
<div class="cancel-edit"> <?php __('or'); ?> <?php echo $html->link(__('Cancel', true), '#CancelWidgetEdit'); ?></div>