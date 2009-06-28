<?php
/**
 * Category Helper
 * 
 */
class CategoryHelper extends AppHelper {
    
    public $helpers = array('Html');
    
    /**
     * Get formated list of categories
     *
     * @param array $categories
     * @param string $type
     * @return string List HTML
     */
    function getList($categories = array(), $type = 'inline') {
    	$catList = array();
	    foreach ($categories as $cat) {
	        $catList[] = $this->Html->link($cat['title'], "/c/$cat[slug]");
	    }
	    return join(', ', $catList);
    }
    
}
