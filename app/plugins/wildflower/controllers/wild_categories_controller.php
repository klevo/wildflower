<?php
//uses('Sanitize');

class WildCategoriesController extends WildflowerAppController {

	public $helpers = array('Wildflower.List', 'Wildflower.Tree');
	public $components = array('Wildflower.Seo');

	public $paginate = array(
        'limit' => 3,
        'order' => array('WildPost.created' => 'desc')
    );
	
    /**
     * Categories overview
     * 
     */
    function wf_index() {
		$categories = $this->WildCategory->findAll(null, null, 'lft ASC', null, 1, 0);
		$parentCategories = $this->WildCategory->generatetreelist(null, null, null, '-');
		
        $this->set(compact('categories', 'parentCategories'));
        
        $this->pageTitle = 'Post categories';
    }
    
    function wf_create() {
		if (empty($this->data[$this->modelClass]['parent_id'])) {
    		// Make sure parent_id will be NULL
    		unset($this->data[$this->modelClass]['parent_id']);
    	}
    	
    	if ($category = $this->WildCategory->save($this->data)) {
    	    $category[$this->modelClass]['id'] = $this->WildCategory->id;
    		$this->set(compact('category'));
    	}
    }
    
    /**
     * View a category and it's posts
     *
     * @param int $id
     * @deprecated Add this info to admin_edit
     */
    function wf_view($id = null) {
        $category = $this->WildCategory->findById($id);
        $this->set('category', $category);
    }
    
    /**
     * Edit a category
     * 
     * @param int $id
     */
    function wf_edit($id = null) {
    	if (!empty($this->data)) {
    	    if ($this->WildCategory->save($this->data['WildCategory'])) {
        	    return $this->redirect(array('action' => 'edit', $id));
        	}
    	}
    	
    	$this->data = $this->WildCategory->findById($id);
    	
    	if (empty($this->data)) return $this->cakeError('object_not_found');
    	
		$parentCategories = $this->WildCategory->generatetreelist(null, null, null, '-');
        $this->set(compact('parentCategories'));
    	
    	$this->pageTitle = $this->data[$this->modelClass]['title'];
    }
    
    function view($slug = null) {
    	$category = $this->WildCategory->findBySlug($slug);
    	$this->set('category', $category);
    	
    	// Parameters
        $this->params['breadcrumb']['current'] = array('title' => $category['WildCategory']['title']);
        $this->params['current'] = array('type' => 'category', 'slug' => $category['WildCategory']['slug']);
        
        $this->Seo->title($category['WildCategory']['title']);
    }
    
}
