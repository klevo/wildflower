<?php
//uses('Sanitize');

class WildCategoriesController extends AppController {

	public $helpers = array('Tree');
	public $pageTitle = 'Categories';

	public $paginate = array(
        'limit' => 3,
        'order' => array('WildPost.created' => 'desc')
    );
	
    /**
     * Reorder categories
     * 
     */
    function wf_index() {
        if (!empty($this->data)) {
            if ($this->WildCategory->save($this->data)) {
                return $this->redirect(array('action' => 'index'));
            }
        }
        
		$categoriesForTree = $this->WildCategory->find('all', array('order' => 'lft ASC', 'recursive' => -1));
		$categoriesForSelect = $this->WildCategory->find('list', array('fields' => array('id', 'title')));
        $this->set(compact('categoriesForTree', 'categoriesForSelect'));
    }
    
    /**
     * Create a new category 
     *
     * Returns the updated category list as JSON.
     */
    function wf_create() {
    	$postId = intval($this->data[$this->modelClass]['wild_post_id']);
    	unset($this->data[$this->modelClass]['wild_post_id']);
    	
    	if ($this->WildCategory->save($this->data)) {
    	    // Category list
        	$post = $this->WildCategory->WildPost->find('first', array(
        	    'conditions' => array('WildPost.id' => $postId),
        	    'fields' => array('id'),
        	    'contain' => 'WildCategory',
        	));
        	$inCategories = Set::extract($post['WildCategory'], '{n}.id');
            $categoriesForTree = $this->WildCategory->find('all', array('order' => 'lft ASC', 'recursive' => -1));
            $this->set(compact('inCategories', 'categoriesForTree'));
    	}
    }
    
    // @TODO make POST only
    function wf_delete($id) {
        if ($this->_isFixed($id)) {
            return $this->_renderNoEdit($id);
        }
        $this->WildCategory->delete(intval($id));
        $this->redirect(array('action' => 'index'));
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
        if ($this->_isFixed($id)) {
            return $this->_renderNoEdit($id);
        }
        
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
    
    private function _isFixed($id) {
        $fixedCategories = Configure::read('App.fixedWildCategories');
        if (!is_array($fixedCategories)) $fixedCategories = array();
        $id = intval($id);
        if (in_array($id, $fixedCategories)) {
            return true;
        }
        return false;
    }
    
    private function _renderNoEdit($id) {
        $this->data = $this->WildCategory->findById($id);
        $this->pageTitle = $this->data[$this->modelClass]['title'];
        return $this->render('no_edit');
    }
    
}
