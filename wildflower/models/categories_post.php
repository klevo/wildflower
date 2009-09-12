<?php
class CategoriesPost extends AppModel {
    public $belongsTo = array('Category', 'Post');
}