<?php
class Page extends AppModel {

	public $actsAs = array(
	   'Containable',
	   'Slug' => array('separator' => '-', 'overwrite' => false, 'label' => 'title'), 
	   'Tree',
	   'Versionable' => array('title', 'content', 'description_meta_tag', 'keywords_meta_tag')
    );
    public $belongsTo = array('User');
    public $hasAndBelongsToMany = array('Sidebar');
	public $validate = array(
	   'title' => array(
	       'rule' => array('maxLength', 255), 
	       'allowEmpty' => false, 
	       'required' => true, 
	       'on' => 'wf_create'
	    )
	);
	public static $statusOptions = array(
	   '0' => 'Published',
	   '1' => 'Draft'
	);
	
	function afterSave($arg) {
	    parent::afterSave($arg);
	    
	    // Update root pages cache
        WildflowerRootPagesCache::update($this->findAllRoot());
	}
	
    /**
     * Before save callback
     *
     * @return bool Success
     */
    function beforeSave() {
        parent::beforeSave();
        
    	// Construct the absolute page URL
    	if (isset($this->data[$this->name]['slug'])) {
	    	$level = 0;
	    	if (intval($this->id) === intval(Configure::read('AppSettings.home_page_id'))) {
	    		// Home page has the URL of root
	    		$this->data[$this->name]['url'] = '/';
	    	} else if (!isset($this->data[$this->name]['parent_id']) or !is_numeric($this->data[$this->name]['parent_id'])) {
	    	    // Page has no parent
	    	    $this->data[$this->name]['url'] = "/{$this->data[$this->name]['slug']}";
	    	} else {
	    		$parentPage = $this->findById($this->data[$this->name]['parent_id'], array('url'));
	    		
	    		$url = "/{$this->data[$this->name]['slug']}";
	    		if ($parentPage[$this->name]['url'] !== '/') {
	    		    $url = $parentPage[$this->name]['url'] . $url;
	    		}
	    		
	    		$this->data[$this->name]['url'] = $url;
	    	}
    	}
    	
    	// Publish?
        if (isset($this->data[$this->name]['publish'])) {
            $this->data[$this->name]['draft'] = 0;
            unset($this->data[$this->name]['publish']);
        }
    	
    	return true;
    }
    
    function updateChildPageUrls($id, $oldUrl, $newUrl) {
        // Update child pages URLs
    	$children = $this->find('all', array(
    	    'conditions' => "{$this->name}.url LIKE '$oldUrl%' AND {$this->name}.id != $id",
    	    'recursive' => -1,
    	    'fields' => array('id', 'url', 'slug'),
    	));
        if (!empty($children)) {
            foreach ($children as $page) {
                        $childNewUrl = str_replace($oldUrl, $newUrl, $page[$this->name]['url']);
                        $this->query("UPDATE {$this->useTable} SET url = '$childNewUrl' WHERE id = {$page[$this->name]['id']}");
            }
        }
    }
    
    function findAllRoot() {
        return $this->find('all', array(
            'conditions' => 'parent_id IS NULL',
            'recursive' => -1,
            'fields' => array('id', 'slug', 'url'),
            'order' => 'lft ASC',
        ));
    }
    
    function findChildrenBySlug($slug) {
        $pageId = $this->field('id', array('slug' => $slug));
        if ($pageId === false) {
            return false;
        }
        $pages = $this->children($pageId, true);
        // Filter out drafts
        function noDrafts($page) {
            if ($page['Page']['draft'] == 1) {
                return false;
            }
            return true;
        }
        $pages = self::filterOutDrafts($pages);
        return $pages;
    }
    
    function findAllBySlugWithChildren($slug) {
        $page = $this->find('first', array('conditions' => array('slug' => $slug)));
        if ($page === false) {
            return false;
        }
        $pages = $this->children($page['Page']['id'], false);
        array_unshift($pages, $page);
        $pages = self::filterOutDrafts($pages);
        return $pages;
    }
    
    static function filterOutDrafts($pages) {
        function noDrafts($page) {
            if ($page['Page']['draft'] == 1) {
                return false;
            }
            return true;
        }
        return array_filter($pages, 'noDrafts');
    }

    function beforeValidate() {
        if (isset($this->data[$this->name]['parent_id']) && !is_numeric($this->data[$this->name]['parent_id'])) {
            $this->data[$this->name]['parent_id'] = null;
        }
        
        // We don't want titles constructed from spaces
        if (isset($this->data[$this->name]['title'])) {
        	$this->data[$this->name]['title'] = trim($this->data[$this->name]['title']);
        }
        
        return true;
    }
    
    /**
     * Mark a page(s) as a draft
     *
     * @param mixed $ids
     * @return void
     */
    function draft($ids) {
        if (!is_array($ids)) {
            $ids = array(intval($ids));
        }
        $ids = join(', ', $ids);
        $this->query("UPDATE {$this->useTable} SET draft = 1 WHERE id IN ($ids)");
    }
    
    /**
     * Alias for $this->draft()
     *
     * @param mixed $ids
     * @return void
     */
    function unpublish($ids) {
        $this->draft($ids);
    }
    
    function getStatusOptions() {
        return self::$statusOptions;
    }
    
    /**
     * Find all pages ordered alphabetically
     *
     * @param array $fields
     * @return array
     */
    function findAllForSidebar($fields = array('id', 'title')) {
    	return $this->findAll(null, $fields, "{$this->name}.title ASC", null, 1);
    }
    
    /**
     * Return [title => url] pairs of children pages
     *
     * @param string $pageSlug
     * @return array
     */
    function getChildrenForMenu($pageSlug) {
        $page = $this->findBySlug($pageSlug);
        $pages = $this->children($page[$this->name]['id']);
        if (empty($pages)) {
            return null;
        }
        $titles = Set::extract($pages, "{n}.{$this->name}.title");
        $urls = Set::extract($pages, "{n}.{$this->name}.url");
        return array_combine($titles, $urls);
    }
    
    /**
     * Find possible parents of a page for select box
     * 
     * @deprecated: Use Cake's TreeBehavior::genera...
     * @param int $skipId id to skip
     */
    function getListThreaded($skipId = null, $alias = 'title') {
        $parentPages = $this->findAll(null, null, "{$this->name}.lft ASC", null, 1, 0);
        
        // Array for form::select
        $selectBoxData = array();
        $skipLeft = false;
        $skipRight = false;
        
        if (empty($parentPages)) return $selectBoxData;
        
        $rightNodes = array();
        foreach ($parentPages as $key => $page) {
            $level = 0;
            // Check if we should remove a node from the stack
            while (!empty($rightNodes) && ($rightNodes[count($rightNodes) - 1] < $page[$this->name]['rght'])) {
               array_pop($rightNodes);
            }
            $level = count($rightNodes);
            
            $dashes = '';
            if ($level > 0) {
                $dashes = str_repeat('&nbsp;', $level) . '-';
            }
            
            if ($skipId == $page[$this->name]['id']) {
                $skipLeft = $page[$this->name]['lft'];
                $skipRight = $page[$this->name]['rght'];
            } else {
                if (!($skipLeft 
                   && $skipRight 
                   && $page[$this->name]['lft'] > $skipLeft 
                   && $page[$this->name]['rght'] < $skipRight)) {
                       $alias = hsc($page[$this->name]['title']);
                       if (!empty($dashes)) $alias = "$dashes $alias";
                       $selectBoxData[$page[$this->name]['id']] = $alias;
                       
                }
            }
            
            $rightNodes[] = $page[$this->name]['rght'];
        }
        
        return $selectBoxData;
    }

    /**
     * Mark a page(s) as published
     *
     * @param mixed $ids
     * @return void
     */
    function publish($ids) {
        if (!is_array($ids)) {
            $ids = array(intval($ids));
        }
        $ids = join(', ', $ids);
        $this->query("UPDATE {$this->useTable} SET draft = 0 WHERE id IN ($ids)");
    }
    
	/**
     * Search title and content fields
     * 
     * @TODO Create a Search behavior
     *
     * @param string $query
     * @return array
     */
    function search($query) {
        $query = Sanitize::escape($query);
    	$fields = null;
    	$titleResults = $this->findAll("{$this->name}.title LIKE '%$query%'", $fields, null, null, 1);
    	$contentResults = array();
    	if (empty($titleResults)) {
    		$titleResults = array();
			$contentResults = $this->findAll("MATCH ({$this->name}.content) AGAINST ('$query')", $fields, null, null, 1);
    	} else {
    		$alredyFoundIds = join(', ', Set::extract($titleResults, '{n}.' . $this->name . '.id'));
    		$notInQueryPart = '';
    		if (!empty($alredyFoundIds)) {
    			$notInQueryPart = " AND {$this->name}.id NOT IN ($alredyFoundIds)";
    		}
    		$contentResults = $this->findAll("MATCH ({$this->name}.content) AGAINST ('$query')$notInQueryPart", $fields, null, null, 1);
    	}
    	
    	if (!is_array(($contentResults))) {
    		$contentResults = array();
    	}
        
    	$results = array_merge($titleResults, $contentResults);
    	return $results;
    }
    
}
