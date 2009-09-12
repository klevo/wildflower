<h2 class="section">Editing a widget</h2>

<?php
    echo $form->create('Widget', array('url' => '/' . Configure::read('Routing.admin') . '/widgets/update', 'id' => 'edit_widget_form'));
    
    if (!isset($this->data['Widget']['items']) or empty($this->data['Widget']['items'])) {
        echo '<div class="slider_block">';
        echo '<h3>Cell 1</h3>';
        echo $form->input("Widget.items.0.label", array('type' => 'text', 'label' => 'Label'));
        echo $form->input("Widget.items.0.url", array('type' => 'text', 'label' => 'URL'));
        echo '</div>';
    } else {
        foreach ($this->data['Widget']['items'] as $i => $item) {
            if ($i == 'id') {
            }
            echo '<div class="slider_block">';
            echo '<h3>Cell ', $i + 1, '</h3>';
            echo $form->input("Widget.items.$i.label", array('type' => 'text', 'label' => 'Label'));
            echo $form->input("Widget.items.$i.url", array('type' => 'text', 'label' => 'URL'));
            echo '</div>';
        }
    }
    
    echo $form->input('randomize', array('type' => 'checkbox', 'label' => 'Randomize cell order'));
    
    echo '<p>', $html->link('Add new cell', '#AddNewCell', array('id' => 'AddNewCell')), '</p>';

    echo $form->hidden('id');
    echo $form->end(__('Save', true));
?>
<div class="cancel-edit"> <?php __('or'); ?> <?php echo $html->link(__('Cancel', true), '#CancelWidgetEdit', array('id' => 'CancelWidgetEdit')); ?></div>

<script type="text/javascript">
    $('#AddNewCell').click(function() {
	    var newBlockEl = $('.slider_block:first').clone();
	    var index = $('.slider_block').size();
	    $('input:first', newBlockEl).val('').attr('name', 'data[Widget][items][' + index + '][label]');
	    $('input:last', newBlockEl).val('').attr('name', 'data[Widget][items][' + index + '][url]');
	    $('h3', newBlockEl).text('Cell ' + (index + 1));
        // newBlockEl = '<div class="slider_block">' + newBlockEl.html() + '</div>';
        
        // newBlockEl = newBlockEl.replace('0', index.toString());
	    $('.slider_block:last').after(newBlockEl);
	    return false;
	});
</script>