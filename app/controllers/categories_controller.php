<?php
class CategoriesController extends AppController {

	public $helpers = array('Tree');
	public $pageTitle = 'Categories';

	public $paginate = array(
        'limit' => 3,
        'order' => array('Post.created' => 'desc')
    );
	
    /**
     * Reorder categories
     * 
     */
    function admin_index() {
        if (!empty($this->data)) {
            if ($this->Category->save($this->data)) {
                return $this->redirect(array('action' => 'index'));
            }
        }
        
		$categoriesForTree = $this->Category->find('all', array('order' => 'lft ASC', 'recursive' => -1));
		$categoriesForSelect = $this->Category->find('list', array('fields' => array('id', 'title')));
        $this->set(compact('categoriesForTree', 'categoriesForSelect'));
    }
    
    /**
     * Create a new category 
     *
     * Returns the updated category list as JSON.
     */
    function admin_create() {
    	$postId = intval($this->data[$this->modelClass]['post_id']);
    	unset($this->data[$this->modelClass]['post_id']);
    	
    	if ($this->Category->save($this->data)) {
    	    // Category list
        	$post = $this->Category->Post->find('first', array(
        	    'conditions' => array('Post.id' => $postId),
        	    'fields' => array('id'),
        	    'contain' => 'Category',
        	));
        	$inCategories = Set::extract($post['Category'], '{n}.id');
            $categoriesForTree = $this->Category->find('all', array('order' => 'lft ASC', 'recursive' => -1));
            $this->set(compact('inCategories', 'categoriesForTree'));
    	}
    }
    
    // @TODO make POST only
    function admin_delete($id) {
        if ($this->_isFixed($id)) {
            return $this->_renderNoEdit($id);
        }
        $this->Category->delete(intval($id));
        $this->redirect(array('action' => 'index'));
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
        if ($this->_isFixed($id)) {
            return $this->_renderNoEdit($id);
        }
        
    	if (!empty($this->data)) {
    	    if ($this->Category->save($this->data['Category'])) {
        	    return $this->redirect(array('action' => 'edit', $id));
        	}
    	}
    	
    	$this->data = $this->Category->findById($id);
    	
    	if (empty($this->data)) return $this->cakeError('object_not_found');
    	
		$parentCategories = $this->Category->generatetreelist(null, null, null, '-');
        $this->set(compact('parentCategories'));
        $this->pageTitle = $this->data[$this->modelClass]['title'];
    }
    
    function view($slug = null) {
    	$category = $this->Category->findBySlug($slug);
    	$this->set('category', $category);
    	
    	// Parameters
        $this->params['breadcrumb']['current'] = array('title' => $category['Category']['title']);
        $this->params['current'] = array('type' => 'category', 'slug' => $category['Category']['slug']);
        
        $this->Seo->title($category['Category']['title']);
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
        $this->data = $this->Category->findById($id);
        $this->pageTitle = $this->data[$this->modelClass]['title'];
        return $this->render('no_edit');
    }
    
}
