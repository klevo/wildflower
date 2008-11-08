<?php
class PostsListHelper extends AppHelper {
	
	public $helpers = array('Html', 'Time');
	
	private $_defaultSettings = array(
		'id' => 'posts-list',
		'class' => 'list'
	);
	private $_defaultSidebarSettings = array(
		'id' => 'sidebar-posts-list',
		'class' => 'sidebar-list'
	);
	private $_emptyMessage = '';
	
    /** 
     * Constructor
     * @since Internalization (for international labels emptyMessage)
     *
     */
    function __construct() {
       parent::__construct();
       $this->_emptyMessage = __('No posts yet', true) . '.' ;
    }
	
	/**
	 * Create list of posts
	 *
	 * @param array $posts
	 * @param array $settings
	 * @return string List HTML
	 */
    function create($posts, $settings = array()) {
        if (empty($posts)) {
            return "<p>{$this->_emptyMessage}</p>";
        }
        
        $settings = array_merge($this->_defaultSettings, $settings);
        
        $listHtml = "<ul id=\"{$settings['id']}\" class=\"{$settings['class']}\">\n";
        
        foreach ($posts as $post) {
        	// Start list item
        	$cssId = "post-{$post['Post']['id']}";
        	$listHtml .= "\t<li id=\"$cssId\" class=\"actions-handle\">";
        	 
        	// Edit link (don't escape, alredy happened in model)
        	$listHtml .= "<h3>"
	        	. $this->Html->link($post['Post']['title'],
	        	array('controller' => 'posts', 'action' => 'edit', $post['Post']['id']),
	        	null,
	        	null,
	        	false)
	        	. "</h3>\n";
	        
	        // If post is older then 3 week display nice date, else time ago in words
	        $threeWeeksAgoStamp = strtotime('-3 week');
	        $postTimeStamp = strtotime($post['Post']['created']);
	        $date = null;
	        if ($postTimeStamp > $threeWeeksAgoStamp) {
	        	$date = $this->Time->timeAgoInWords($post['Post']['created']);
        	} else {
        		$date = $this->Time->niceShort($postTimeStamp);
        	}

        	// Date and user
	        $listHtml .= "<p>$date by "
	        	. $this->Html->link($post['User']['name'],
		        	array('controller' => 'users', 'action' => 'edit', $post['User']['id']),
		        	null,
		        	null,
		        	false)
	        	. "</p>\n";
	        	
	        // @TODO categories
	        
	        // Actions
	        $index = WILDFLOWER_POSTS_INDEX;
	        $listHtml .= '<small class="actions">'
	        	. $this->Html->link($this->Html->image('cross.gif'), 
                        array('controller' => 'posts', 'action' => 'delete', $post['Post']['id']), 
                        array('class' => 'delete', 'rel' => 'post'), 
                        null, 
                        false)
	        	. " "
	        	. $this->Html->link('View', "/$index/{$post['Post']['slug']}", array('class' => 'view'))
	        	. "</small>\n";
	        	
        	// Close list item
        	$listHtml .= "</li>\n\n";
        }

        // Close list
        $listHtml .= "</ul>\n\n";
        
        return $listHtml;
    }

    /**
     * Create a list of posts for sidebar
     *
     * @param array $posts
     * @param array $settings
     * @return string List HTML
     */
    function createSidebar($posts, $settings = array()) {
    	if (empty($posts)) {
    		return '';
    	}
    	
    	$settings = array_merge($this->_defaultSidebarSettings, $settings);
    	
    	$listHtml = "<ul id=\"{$settings['id']}\" class=\"{$settings['class']}\">\n";
    	
    	// All posts link
    	$currentClass = '';
    	if ($this->params['action'] == 'admin_index') {
    		$currentClass = ' current';
    	}
    	$listHtml .= "<li class=\"all$currentClass\">" 
    		. $this->Html->link('All posts', array('controller' => 'posts', 'action' => 'index'))
    		. "</li>\n";

    	foreach ($posts as $post) {
        	// Start list item
        	$cssId = "sidebar-post-{$post['Post']['id']}";
        	
        	// CSS classes
        	$cssClasses = array();
        	
        	$editedPostId = null;
        	if ($this->params['action'] == 'admin_edit') {
        		$editedPostId = $this->params['pass'][0];
        	}
        	if ($post['Post']['id'] == $editedPostId) {
        		$cssClasses[] = 'current';
        	}
        	
        	$classAttr = '';
        	if (!empty($cssClasses)) {
        		$classAttr = ' class="' . join(' ', $cssClasses) . '"';
        	}
        	
        	$listHtml .= "\t<li id=\"$cssId\"$classAttr>";
        	 
        	// Edit link (don't escape, alredy happened in model)
        	$listHtml .= $this->Html->link($post['Post']['title'],
		        	array('controller' => 'posts', 'action' => 'edit', $post['Post']['id']),
		        	null,
		        	null,
		        	false)
	        	. "\n";
	        
        	// Close list item
        	$listHtml .= "</li>";
        }

        // Close list
        $listHtml .= "</ul>\n\n";
        
        return $listHtml;
    }
    
}
    