<?php
uses('Sanitize');

class CategoriesController extends AppController {

	public $helpers = array('List', 'Html', 'Form', 'Tree');
	public $components = array('Seo');

	public $paginate = array(
        'limit' => 3,
        'order' => array('Post.created' => 'desc')
    );
	
    /**
     * Categories list
     * 
     */
    function admin_index() {
        $this->set('categories', $this->Category->findAll(null, null, 'lft ASC', null, 1, 0));
        $this->set('parentCategories', $this->Category->generatetreelist(null, null, null, '-'));

		if ($this->data) {
			// Create new category
			if (empty($this->data['Category']['parent_id'])) {
	    		// Make sure parent_id will be NULL
	    		unset($this->data['Category']['parent_id']);
	    	}
	    	if ($this->Category->save($this->data)) {
	    		$this->redirect(array('action' => 'index'));
	    	}
		}
    }
    
    /**
     * View a category and it's posts
     *
     * @param int $id
     * @deprecated Add this info to admin_edit
     */
    function admin_view($id = null) {
        $category = $this->Category->findById($id);
        $this->set('category', $category);
    }
    
    /**
     * Edit a category
     * 
     * @param int $id
     */
    function admin_edit($id = null) {
    	$this->set('parentCategories', $this->Category->getSelectBoxData($id));
    	if (empty($this->data)) {
    		$this->data = $this->Category->findById($id);
    		return $this->render();
    	}

    	if ($this->Category->save($this->data['Category'])) {
    	    return $this->redirect(array('action' => 'index'));
    	}
    }
    
    function view($slug = null) {
    	$category = $this->Category->findBySlug($slug);
    	$this->set('category', $category);
    	
    	// Parameters
        $this->params['breadcrumb']['current'] = array('title' => $category['Category']['title']);
        $this->params['current'] = array('type' => 'category', 'slug' => $category['Category']['slug']);
        
        $this->Seo->title($category['Category']['title']);
    }
    
}
