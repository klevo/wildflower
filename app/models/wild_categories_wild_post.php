<?php
class WildCategoriesWildPost extends AppModel {
    public $belongsTo = array('WildCategory', 'WildPost');
}