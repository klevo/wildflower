<?php
echo $form->create('Setting'),
     $form->input('name'),
     $form->input('value'),
     $form->input('description'),
     $form->input('type', array(
        'type' => 'select', 
        'options' => array('general' => 'General', 'theme' => 'Theme')
     )),
     $form->end('Add');