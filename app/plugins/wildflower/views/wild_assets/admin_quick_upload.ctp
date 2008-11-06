<?php
	echo $form->create('Upload', array('type' => 'file', 'action' => 'index', 'id' => 'QuickUploadForm'));
	echo $form->input('file', array('type' => 'file', 'label' => 'Browse your computer', 'id' => 'QuickUploadFile'));
	echo $form->submit('Upload');
	echo $form->end();
?>
