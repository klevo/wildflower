<?php
	echo $form->create('Dashboard', array('url' => '/search', 'class' => 'search'));
	echo $form->input('query', array('label' => 'Search', 'class' => 'search-query focus'));
	echo $form->submit('Search');
	echo $form->end();
?>