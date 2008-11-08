<?php
class Message extends AppModel {

	public $validate = array(
		'name' => array(
			'rule' => array('between', 5, 100),
			'required' => true,
			'message' => 'Name must be between 5 to 50 alphanumeric characters long'
		),
		'email' => array(
			'rule' => 'email',
			'required' => true,
			'message' => 'Please enter a valid email address'
		),
		'content' => VALID_NOT_EMPTY
	);

}
