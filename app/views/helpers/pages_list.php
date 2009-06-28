<?php
class PagesListHelper extends AppHelper {
	
	public $helpers = array('Html', 'Time');
	
	private $_defaultSettings = array(
		'id' => 'pages-list',
		'class' => 'list'
	);
	private $_defaultSidebarSettings = array(
		'id' => 'sidebar-pages-list',
		'class' => 'sidebar-list'
	);
	private $_emptyMessage = 'No pages yet.';
	private $_limits = null;
	private $_treeSettings = array(
        'model' => 'Page',
        'alias' => 'title',
        'left' => 'lft',
        'right' => 'rght',
        'primaryKey' => 'id'
    );
	
	/**
	 * Create list of last modified pages
	 *
	 * @param array $pages
	 * @param array $settings
	 * @return string List HTML
	 */
    function createLastModified($pages, $settings = array()) {
        if (empty($pages)) {
            return "<p>{$this->_emptyMessage}</p>";
        }
        
        $settings = array_merge($this->_defaultSettings, $settings);
        
        $listHtml = "<ul id=\"{$settings['id']}\" class=\"{$settings['class']}\">\n";

        foreach ($pages as $page) {
        	// Start list item
        	$cssId = "sidebar-page-{$page['Page']['id']}";
        	$listHtml .= "\t<li id=\"$cssId\" class=\"actions-handle\">";
        	 
        	// Edit link (don't escape, alredy happened in model)
        	$listHtml .= "<h3>"
	        	. $this->Html->link($page['Page']['title'],
	        	array('controller' => 'pages', 'action' => 'edit', $page['Page']['id']),
	        	null,
	        	null,
	        	false)
	        	. "</h3>\n";
	        
	        // If a page is modified sooner then 3 weeks ago display a date, else time ago in words
	        $threeWeeksAgoStamp = strtotime('-3 week');
	        $pageTimeStamp = strtotime($page['Page']['updated']);
	        $date = null;
	        if ($pageTimeStamp > $threeWeeksAgoStamp) {
	        	$date = $this->Time->timeAgoInWords($page['Page']['updated']);
        	} else {
        		$date = $this->Time->niceShort($pageTimeStamp);
        	}

        	// Date and user
	        $listHtml .= "<p>$date</p>\n";
	        	
	        // Actions
	        $listHtml .= '<small class="actions">'
	        	. $this->Html->link($this->Html->image('cross.gif'), 
                        array('controller' => 'pages', 'action' => 'delete', $page['Page']['id']), 
                        array('class' => 'delete', 'rel' => 'post'), 
                        null, 
                        false)
	        	. " "
	        	. $this->Html->link('View', $page['Page']['url'], array('class' => 'view'))
	        	. "</small>\n";
	        	
        	// Close list item
        	$listHtml .= "</li>\n\n";
        }

        // Close list
        $listHtml .= "</ul>\n\n";
        
        return $listHtml;
    }
    
    /**
     * Create a list of pages for sidebar
     *
     * @param array $pages
     * @param array $settings
     * @return string List HTML
     */
    function createSidebar($pages, $settings = array()) {
    	if (empty($pages)) {
    		return '';
    	}
    	
    	$settings = array_merge($this->_defaultSidebarSettings, $settings);
    	
    	$listHtml = "<ul id=\"{$settings['id']}\" class=\"{$settings['class']}\">\n";
    	
    	// All pages link
    	$currentClass = '';
    	if ($this->params['action'] == 'admin_index') {
    		$currentClass = ' current';
    	}
    	$listHtml .= "<li class=\"all$currentClass\">" 
    		. $this->Html->link('All pages', array('controller' => 'pages', 'action' => 'index'))
    		. "</li>\n";
    		
    	foreach ($pages as $page) {
        	// Start list item
        	$cssId = "sidebar-page-{$page['Page']['id']}";
        	
        	// CSS classes
        	$cssClasses = array();
        	
        	// Is current edited?
        	$editedPageId = null;
        	if ($this->params['action'] == 'admin_edit') {
        		$editedPageId = $this->params['pass'][0];
        	}
        	if ($page['Page']['id'] == $editedPageId) {
        		$cssClasses[] = 'current';
        	}
        	
        	// Is home page?
        	$homePageId = Configure::read('AppSettings.home_page_id');
        	if ($page['Page']['id'] == $homePageId) {
        		$cssClasses[] = 'homepage';
        	}
        	
        	$classAttr = '';
        	if (!empty($cssClasses)) {
        		$classAttr = ' class="' . join(' ', $cssClasses) . '"';
        	}
        	
        	$listHtml .= "\t<li id=\"$cssId\"$classAttr>";
        	 
        	// Edit link (don't escape, alredy happened in model)
        	$listHtml .= $this->Html->link($page['Page']['title'],
		        	array('controller' => 'pages', 'action' => 'edit', $page['Page']['id']),
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
    
    /**
     * Generate nested list from tree data
     *
     * @param array $data
     * @param array $indexArray Settings
     * @param string $ulClass
     * @return string Nested list
     */
    function createSitemap($data, $indexArray = array(), $ulClass = 'list', $addData = null) {
        extract($this->_treeSettings);
        extract($indexArray);
        
        // determin limits to know when the last top node is found.
        if (isset($data[0][$model][$left])) {
            $floor= $data[0][$model][$left];
            $ceil= $data[0][$model][$right];
            foreach ($data as $node) {
                if ($node[$model][$right] > $ceil) {
                    $ceil= $node[$model][$right];
                }
            }
        } else {
            $floor = $ceil = 0;
        }
        $this->_limits = array(array($floor - 1, $ceil + 1));
        
        $this->_childCount= 1;
        
        if ($ulClass) {
            $return= "\r\n" . '<ul class="' . $ulClass . '">' . "\n";
        } else {
            $return= "\n" . '<ul>' . "\n";
        }
        
        foreach ($data as $i => $node) {
            $return .= "\n" . str_repeat("\t", $this->_childCount);
            $hasChildren= false;
            $last= false;
            $class= array ();
            if ($node[$model][$right] <> $node[$model][$left] + 1) { // Has some children
                $hasChildren= true;
                list ($parentLeft, $parentRight)= $this->_get_parent_indexes($node[$model][$left]);
                $last= ife($parentRight == ($node[$model][$right] + 1), true, false);
                $this->_limits[] = array($node[$model][$left], $node[$model][$right]);
            }
            if (!$hasChildren && (!isset ($data[$i +1]) || ($node[$model][$right] + 1 <> $data[$i +1][$model][$left]))) {
                // it's the last item or the last in the current series
                $last= true;
                $class[]= 'last';
            }
            
            $title = $node[$model][$alias];
            $id = $node[$model][$primaryKey];
            $controller = low(Inflector::pluralize($model));
            $lModel = low($model);
            
            $class[] = "sitemap-$lModel-$id";
            
            if ($class) {
                $return .= '<li class="' . implode($class, ' ') . '">';
            } else {
                $return .= '<li>';
            }
            
            // Link to the item
            $return .= $this->Html->link($title, $node[$model]['url']);
                
            if (isset ($data[$i +1])) {
                // If it's not the absolute last item
                if ($node[$model][$right] < $data[$i +1][$model][$left]) { // Close uls
                    for ($j= 1; $j <= ($data[$i +1][$model][$left] - $node[$model][$right] - 1); $j++) {
                        $this->_childCount--;
                        if ($this->_childCount < 0) {
                            trigger_error(__('child count less than 0 in ' . __METHOD__, E_USER_WARNING));
                        }
                        $return .= '</li>';
                        $return .= "\n" . str_repeat("\t", $this->_childCount) . '</ul>';
                    }
                    $return .= '</li>';
                }
                elseif ($node[$model][$right] <> $node[$model][$left] + 1) { // Has some children
                    $return .= "\n" . str_repeat("\t", $this->_childCount) . '<ul>';
                    $this->_childCount++;
                } else {
                    $return .= '</li>';
                }
            } else {
                // Last item in data list, close ul items
                $return .= '</li>';
                for (; $this->_childCount > 1; $this->_childCount--) {
                    $return .= "\n" . str_repeat("\t", $this->_childCount - 1) . '</ul>';
                    $return .= "\n" . str_repeat("\t", $this->_childCount - 1) . '</li>';
                }
            }
        }
        $return .= "\n" . '</ul>' . "\n";
        return $return;
    }
    
    /**
     * Find path to a page
     * 
     * @param array $data
     * @param string $model Model name
     * @param int $startId id to start with
     */
    function _findPath($data, $model, $startId) {
        $parentId = null;
        $path = '';
        foreach($data as $node) {
            if($node[$model]['id'] == $startId) {
                $parentId = $node[$model]['parent_id'];
                $path = $node[$model]['slug'];
            }
        }
        if ($parentId) {
            return $this->_findPath($data, $model, $parentId) . "/$path";
        } else {
            return "/$path";
        }
    }
    
    function _get_parent_indexes($thisLeft) {
        if (!$this->_limits) {
            return array (
                0,
                0
            );
        }
        $parentLeft= $this->_limits[count($this->_limits) - 1][0];
        $parentRight= $this->_limits[count($this->_limits) - 1][1];
        if ($parentRight < $thisLeft) {
            unset ($this->_limits[count($this->_limits) - 1]);
            $this->_limits= array_values($this->_limits);
            return $this->_get_parent_indexes($thisLeft);
        }
        return array (
            $parentLeft,
            $parentRight
        );
    }

}
    