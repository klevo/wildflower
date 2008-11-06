<?php
class WildRevision extends WildflowerAppModel {

	public $belongsTo = array(
		'WildUser' => array(
			'className' => 'Wildflower.WildUser',
			'foreignKey' => 'user_id'
		)
	);

    public $useTable = 'revisions';

}
