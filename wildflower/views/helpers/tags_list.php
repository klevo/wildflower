<?php
class TagsListHelper extends AppHelper {
	
	public $helpers = array('Html');
	
	private $_defaultSettings = array(
		'id' => 'tags-list',
		'class' => 'list'
	);
	private $_defaultSidebarSettings = array(
		'id' => 'tags-pages-list',
		'class' => 'sidebar-list'
	);
	private $_emptyMessage = 'No tags yet.';
    
    /**
     * Create a list of pages for sidebar
     *
     * @param array $tags
     * @param array $settings
     * @return string List HTML
     */
    function createSidebar($tags, $settings = array()) {
    	if (empty($tags)) {
    		return '';
    	}
    	
    	$settings = array_merge($this->_defaultSidebarSettings, $settings);
    	
    	$listHtml = "<ul id=\"{$settings['id']}\" class=\"{$settings['class']}\">\n";
    	
    	// All tags link
    	$currentClass = '';
    	if (!isset($this->params['named']['tag'])) {
    		$currentClass = ' current';
    	}
    	$listHtml .= "<li class=\"all$currentClass\">" 
    		. $this->Html->link('All files', array('controller' => 'uploads', 'action' => 'index'))
    		. "</li>\n";

    	foreach ($tags as $tag) {
        	// Start list item
        	$cssId = "sidebar-tag-{$tag['Tag']['id']}";
        	
        	// CSS classes
        	$cssClasses = array();
        	
        	// Is current edited?
        	$viewedTagId = null;
        	if (isset($this->params['named']['tag'])) {
        		$viewedTagId = $this->params['named']['tag'];
        	}
        	if ($tag['Tag']['id'] == $viewedTagId) {
        		$cssClasses[] = 'current';
        	}
        	
        	$classAttr = '';
        	if (!empty($cssClasses)) {
        		$classAttr = ' class="' . join(' ', $cssClasses) . '"';
        	}
        	
        	$listHtml .= "\t<li id=\"$cssId\"$classAttr>";
        	 
        	// Edit link (don't escape, alredy happened in model)
        	$listHtml .= $this->Html->link($tag['Tag']['name'],
		        	array('controller' => 'uploads', 'action' => 'index', 'tag' => $tag['Tag']['id']))
	        	. "\n";
	        
        	// Close list item
        	$listHtml .= "</li>";
        }

        // Close list
        $listHtml .= "</ul>\n\n";
        
        return $listHtml;
    }

}
    