<?php
class CommentsListHelper extends AppHelper {
	
	public $helpers = array('Html', 'Time', 'Text', 'Textile');
	
	private $_defaultSettings = array(
		'class' => 'comments-list'
	);
	private $_emptyMessage = 'No comments yet.';
    
    /**
     * Create a list of pages for sidebar
     *
     * @param array $tags
     * @param array $settings
     * @return string List HTML
     */
    function create($comments, $settings = array(), $spamList = false) {
    	if (empty($comments)) {
    		return "<p>{$this->_emptyMessage}</p>\n";
    	}
    	
    	$settings = array_merge($this->_defaultSettings, $settings);
    	
    	$listHtml = "<ol class=\"{$settings['class']}\">\n";
    	
    	foreach ($comments as $comment) {
        	// Start list item
        	$cssId = "comment-{$comment['Comment']['id']}";
        	
        	// CSS classes
        	$cssClasses = array();
        	
        	$listHtml .= "\t<li id=\"$cssId\" class=\"comment\">";

        	// Author and content
        	$listHtml .= '<p class="comment-metadata">';
        	$author = $comment['Comment']['url'] ? $this->Html->link($comment['Comment']['name'], $comment['Comment']['url']) : $comment['Comment']['name'];
	        $listHtml .= $author;
	        $listHtml .= ' responded ' 
	               . $this->Time->timeAgoInWords($comment['Comment']['created']) 
	               . ' to '
	               . $this->Html->link($comment['Post']['title'], array('controller' => 'posts', 'action' => 'edit', $comment['Post']['id']))
	               . '</p>';
	        $listHtml .= '<div class="entry inplace-edit">' . $this->Textile->format($comment['Comment']['content']) . '</div>';
	        
	        // Action
	        $listHtml .= '<ul class="comment-actions">';
	        $listHtml .= '<li>' . $this->Html->link('Edit', '#Edit', array('class' => 'edit-comment')) . '</li>';
            
	        $spamLink = '';
	        if ($spamList) {
	            $spamLink = $this->Html->link('Not a spam', '#NotSpam', array('class' => 'unspam-comment'));
	        } else {
	            $spamLink = $this->Html->link('Mark as spam', '#Spam', array('class' => 'spam-comment'));
	        }
	        
	        $listHtml .= '<li>' . $spamLink . '</li>';
	        $listHtml .= '<li>' . $this->Html->link('Delete', '#Delete', array('class' => 'delete-comment')) . '</li>';
	        $listHtml .= '</ul>';
	        
        	// Close list item
        	$listHtml .= "</li>";
        }

        // Close list
        $listHtml .= "</ol>\n\n";
        
        return $listHtml;
    }
    
    /**
     * Create a list of pages for sidebar
     *
     * @param array $comments
     * @param array $settings
     * @return string List HTML
     */
    function massEdit($comments, $settings = array()) {
    	if (empty($comments)) {
    		return "<p>{$this->_emptyMessage}</p>\n";
    	}
    	
    	$settings = array_merge($this->_defaultSettings, $settings);
    	
    	$listHtml = "<ol class=\"{$settings['class']}\">\n";
    	
    	foreach ($comments as $comment) {
        	// Start list item
        	$cssId = "comment-{$comment['Comment']['id']}";
        	
        	// CSS classes
        	$cssClasses = array();
        	
        	$listHtml .= "\t<li id=\"$cssId\">"
        	   . '<a href="' 
        	   . $this->Html->url(array('action' => 'edit', 'id' => $comment['Comment']['id']))
        	   . '">'
        	   . $comment['Comment']['name']
        	   . ' <small>' 
        	   . $this->Text->excerpt($comment['Comment']['content'], null, 30, '...') 
        	   . '</small></a>';
	        
        	// Close list item
        	$listHtml .= "</li>";
        }

        // Close list
        $listHtml .= "</ol>\n\n";
        
        return $listHtml;
    }

}
    