<h2 class="section">Editing a widget</h2>

<?php
    echo $form->create('WildWidget', array('url' => '/' . Configure::read('Wildflower.prefix') . '/widgets/update'));
    
    if (!isset($this->data['WildWidget']['item']) or empty($this->data['WildWidget']['item'])) {
        echo $form->input("WildWidget.0.item", array('type' => 'text', 'label' => 'Item 1'));
    } else {
        foreach ($this->data['WildWidget']['item'] as $i => $item) {
            echo $form->input("WildWidget.item.$i", array('type' => 'text', 'label' => 'Item ' . ($i + 1)));
        }
    }
    
    echo $html->link('Add new item', '#AddNewItem');

    echo $form->hidden('id');
    echo $form->end('Save');
?>