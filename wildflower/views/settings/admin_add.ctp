<?php
echo $form->create('Setting'),
     $form->input('name', array('label' => __('Name', true))),
     $form->input('value', array('label' => __('Value', true))),
     $form->input('description', array('label' => __('Description', true))),
     $form->input('type', array(
     		'label' => __('Type', true),     			
        'type' => 'select', 
        'options' => array('general' => 'General', 'theme' => 'Theme')
     )),
     $form->end(__('Add', true));