<?php
class Tag extends AppModel {

	public $hasAndBelongsToMany = array(
        'Upload' => array(
            'className' => 'Upload',
                'joinTable'              => 'tags_uploads',
                'foreignKey'             => 'upload_id',
                'associationForeignKey'  => 'tag_id',
                'conditions'             => '',
                'order'                  => '',
                'limit'                  => '',
                'uniq'                   => true,
                'finderQuery'              => '',
                'deleteQuery'            => '',
                'insertQuery'             => '')
    );

}
