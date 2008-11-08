<?php
	echo $form->create('Upload', array('type' => 'file', 'action' => 'index', 'id' => 'QuickUploadForm'));
	echo $form->input('file', array('type' => 'file', 'label' => __('Browse your computer', true), 'id' => 'QuickUploadFile'));
	echo $form->submit(__('Upload', true));
	echo $form->end();
?>
