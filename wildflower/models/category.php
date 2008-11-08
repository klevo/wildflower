<?php
class Category extends AppModel {

	var $name = 'Category';
	var $actsAs = array(
	   'Tree', 
	   'Slug' => array('label' => 'title')
	);
	var $validate = array(
		'title' => VALID_NOT_EMPTY
	);
	var $hasAndBelongsToMany = array(
			'Post' => array('className' => 'Post',
						'joinTable' => 'categories_posts',
						'foreignKey' => 'category_id',
						'associationForeignKey' => 'post_id',
						'conditions' => '',
						'fields' => '',
						'order' => '',
						'limit' => '',
						'offset' => '',
						'unique' => '',
						'finderQuery' => '',
						'deleteQuery' => '',
						'insertQuery' => ''),
	);

}
?>