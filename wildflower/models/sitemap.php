<?php
class Sitemap extends AppModel {

	var $name = 'Sitemap';
	var $validate = array(
		'loc' => VALID_NOT_EMPTY
	);
	public $actsAs = array('Tree');

}
?>