<?php
	echo $form->create('Dashboard', array('url' => '/search', 'class' => 'search'));
	echo $form->input('query', array('label' => __('Search', true), 'class' => 'search-query focus'));
	echo $form->submit(__('Search', true));
	echo $form->end();
?>