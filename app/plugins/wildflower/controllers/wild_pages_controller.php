<?php
uses('Sanitize');
/**
 * Pages Controller
 *
 * Pages are the heart of every CMS.
 */
class WildPagesController extends WildflowerAppController {
	
	public $components = array('RequestHandler', 'Seo');
	public $helpers = array('Cache', 'Form', 'Html', 'Text', 'Time', 'Wildflower.List', 'Wildflower.Tree');
    public $paginate = array(
        'limit' => 25,
        'order' => array('WildPage.lft' => 'asc')
    );
    
    /**
     * A static about Wildflower page
     *
     */
    function wf_about() {
    }
    
    /**
     * Create a new page, with title set, as a draft.
     *
     */
    function wf_create() {
        $this->data[$this->modelClass]['draft'] = 1;
        $this->WildPage->create($this->data);
        $this->WildPage->save();
        $this->redirect(array('action' => 'edit', $this->WildPage->id));
    }

    /**
     * @TODO not implemented yet. Steal something from Wordpress :)
     * 
     * Show difference between current version and a revision
     *
     * @param int $pageId
     */
    function wf_diff($pageId, $revisionId) {
        $pageDiff = $this->WildPage->revisionDiff($pageId, $revisionId);
        $this->set('revisionDiff', $pageDiff);
    }
    
    /**
     * @TODO not done yet
     *
     * Discard any unsaved changes to a page
     *
     * @param int $id
     */
    function wf_discardChanges($id = null, $actionAfter = null) {
        $previewCachePath = TMP . 'preview' . DS . "page_{$id}_preview.txt";
        if (file_exists($previewCachePath)) {
            unlink($previewCachePath);
        }
        
        if ($actionAfter) {
            $this->redirect(array('action' => $actionAfter));
        } else {
            $this->redirect(array('action' => 'edit', $id));
        }
    }
    
    /**
     * Edit a page
     * 
     * @param int $id Page ID
     */
    function wf_edit($id = null, $revisionNumber = null) {
        $this->WildPage->contain('WildUser');
        $page = $this->WildPage->findById($id);
        
        if (empty($page)) return $this->cakeError('object_not_found');
        
        $this->data = $page;
        
        // If viewing a revision, merge with revision content
        if ($revisionNumber) {
            $this->data = $this->WildPage->getRevision($id, $revisionNumber);
            
            $this->set(array('revisionId' => $revisionNumber, 'revisionCreated' => $this->data['WildRevision']['created']));
        }
        
        // View
        $this->pageTitle = $page[$this->modelClass]['title'];
        
        $hasUser = $page['WildUser']['id'] ? true : false;
        $isDraft = ($page[$this->modelClass]['draft'] == 1) ? true : false;
        $isRevision = !is_null($revisionNumber);
        $revisions = $this->WildPage->getRevisions($id);
        $newParentPageOptions = $this->WildPage->getListThreaded();
        $this->set(compact('isRevision', 'hasUser', 'isDraft', 'revisions', 'newParentPageOptions'));
        $this->_setParentSelectBox($page[$this->modelClass]['id']);
    }
    
    function wf_options($id = null) {
        $this->WildPage->contain('WildUser');
        $this->data = $this->WildPage->findById($id);
        
        if (empty($this->data)) return $this->cakeError('object_not_found');
        
        $this->pageTitle = $this->data[$this->modelClass]['title'];
        $parentPageOptions = $this->WildPage->getListThreaded($this->data['WildPage']['id']);
        $this->set(compact('parentPageOptions'));
    }
    
    function wf_update() {
        $this->data[$this->modelClass]['wild_user_id'] = $this->getLoggedInUserId();
        
        $this->WildPage->create($this->data);
        if (!$this->WildPage->exists()) return $this->cakeError('object_not_found');
        
        // Publish?
        if (isset($this->data['__save']['publish'])) {
            $this->data[$this->modelClass]['draft'] = 0;
        }
        unset($this->data['__save']);
        
        if (!$this->WildPage->save()) return $this->cakeError('save_error');

        // JSON response
        if ($this->RequestHandler->isAjax()) {
            $revisions = $this->WildPage->getRevisions($this->WildPage->id, 1);
            
            $this->WildPage->contain('WildUser');
            $page = $this->WildPage->findById($this->WildPage->id);
            
            $this->set(compact('revisions', 'page'));
            return $this->render('wf_update');
        }
        
        $this->redirect(array('action' => 'wf_edit', $this->data[$this->modelClass]['id']));
    }
    
    /**
     * Pages administration overview
     * 
     */
    function wf_index() {
        $this->pageTitle = 'Pages';
        $this->WildPage->recursive = -1;
    	$pages = $this->WildPage->find('all', array('order' => 'lft ASC'));
    	$newParentPageOptions = $this->WildPage->getListThreaded();
    	$this->set(compact('pages', 'newParentPageOptions'));
    }
    
    /**
     * Insert link dialog
     *
     */
    function wf_link() {
        $this->autoLayout = false;
        $pages = $this->WildPage->findAll();
        $pagesForSelectBox = $pageUrlMap = array();
        foreach ($pages as $page) {
            $pagesForSelectBox[$page[$this->modelClass]['id']] = $page[$this->modelClass]['title'];
            $pageUrlMap[$page[$this->modelClass]['id']] = $page[$this->modelClass]['url'];
        }
        
        $posts = $this->requestAction('/admin/posts/findAll');
        $postsForSelectBox = $postUrlMap = array();
        foreach ($posts as $post) {
            $postsForSelectBox[$post['Post']['id']] = $post['Post']['title'];
            $postUrlMap[$post['Post']['id']] = "/p/{$post['Post']['slug']}";
        }
        
        $this->set(array(
           'pages' => $pagesForSelectBox, 
           'posts' => $postsForSelectBox, 
           'postUrlMap' => $postUrlMap,
           'pageUrlMap' => $pageUrlMap));
        
        $this->layout = 'wf_dialog';
    }
    
    /**
     * Move a page up in the hierarchy
     *
     * @param int $id
     */
    function wf_moveup($id = null) {
    	$this->WildPage->moveup($id);
    	$this->Session->write('ChangeIndicator.id', $id);
        $this->redirect("/admin/pages/#page-$id");
    }
    
    /**
     * Move a page down in the hierarchy
     *
     * @param int $id
     */
    function wf_movedown($id = null) {
        $this->WildPage->movedown($id);
        $this->Session->write('ChangeIndicator.id', $id);
        $this->redirect("/admin/pages/#page-$id");
    }
    
    /**
     * Regenerate URL field for each page
     * 
     * Maintenance function.
     */
    function wf_setUrlFields() {
    	$pages = $this->WildPage->findAll();
    	
    	// Resave each page
    	$success = true;
    	foreach ($pages as $page) {
    		// Unset URL field, we want them to be generated anew
    		unset($page[$this->modelClass]['url']);
    		unset($page[$this->modelClass]['level']);
    		$this->WildPage->id = $page[$this->modelClass]['id'];
    		if (!$this->WildPage->save($page)) {
    			$success = false;
    		}
    	}
    	
    	if ($success) {
    		exit('Succesfuly regenerated pages table URL fields.');
    	} else {
    		exit('Transaction failed.');
    	}
    }
    
    function beforeRender() {
        parent::beforeRender();
        $this->set('isPage', true);
        $this->params['Wildflower']['view']['isPage'] = true;
    }
    
    /**
     * Get page data
     * 
     * This method works only for requestAction. Use it to get the Page array
     * anywhere in the CMS.
     *
     * @param string $slug
     * @return array
     */
    function get($slug = null) {
        $this->assertInternalRequest();
        return $this->WildPage->findBySlug($slug);
    }
    
    /**
     * Get pages from branch
     *
     * @param int $left Parent tree left value
     * @param int $right Parent tree right value
     * @return array
     */
    function getBranch($left, $right) {
        $this->assertInternalRequest();
        $fields = array('id', 'title', 'lft', 'rght', 'url', 'slug', 'parent_id');
        $order = "{$this->modelClass}.lft ASC";
        $conditions = "{$this->modelClass}.lft BETWEEN $left AND $right";
        return $this->WildPage->find('all', compat('conditions', 'fields', 'order'));
    }
    
    function getChildPagesForMenu($pageSlug) {
        $this->assertInternalRequest();
        return $this->WildPage->getChildrenForMenu($pageSlug);
    }

    /**
     * View a page
     * 
     * Handles redirect if the correct url for page is not entered.
     */
    function view() {
        if (Configure::read('AppSettings.cache') == 'on') {
            $this->cacheAction = 60 * 60 * 24 * 3; // Cache for 3 days
        }
        
        // Parse attributes
        $args = func_get_args();
        $corrected = false;
        $argsCountBeforeFilter = count($args);
        $args = array_filter($args);
        $url = '/' . $this->params['url']['url'];
        
        // Redirect if the entered URL is not correct
        if (count($args) !== $argsCountBeforeFilter) {
            return $this->redirect($url);
        }
        
        // Determine if this is the site root (home page)
        $homeArgs = array('app', 'webroot');
        if (empty($args) or $args === $homeArgs) {
            $this->isHome = true;
        }
        
        $this->params['Wildflower']['view']['isHome'] = $this->isHome;
        
        // Find the requested page
		$this->WildPage->recursive = -1;
        $page = array();
        
        if (isset($this->params['id'])) {
            $page = $this->WildPage->findByIdAndDraft($this->params['id'], 0);
        } else if ($this->isHome) {
            $page = $this->WildPage->findByIdAndDraft($this->homePageId, 0);
        } else {
            $slug = end(explode('/', $url));
	        $slug = self::slug($slug);
            $page = $this->WildPage->findBySlugAndDraft($slug, 0);
        }

		// Give 404 if no page found or requesting a parents page without a parent in the url
		$isChildWithoutParent = (!$this->isHome and ($page[$this->modelClass]['url'] !== $url));
		if (empty($page) or $isChildWithoutParent) {
			return $this->do404();
        }
        
        $this->pageTitle = $page[$this->modelClass]['title'];
        
        // View variables
        $this->set(array(
            'page' => $page,
            'currentPageId' => $page[$this->modelClass]['id'],
            'isPage' => true
        ));
        
        $this->params['pageMeta'] = array(
            'descriptionMetaTag' => $page[$this->modelClass]['description_meta_tag'],
            'keywordsMetaTag' => $page[$this->modelClass]['keywords_meta_tag']
        );
        
        // Parameters @TODO unify parameters
        $this->params['current'] = array(
            'type' => 'page', 
            'slug' => $page[$this->modelClass]['slug'], 
            'id' => $page[$this->modelClass]['id']);
        $this->params['Wildflower']['page']['slug'] = $page[$this->modelClass]['slug'];        
        
        $this->_chooseTemplate($page[$this->modelClass]['slug']);
    }
    
    function update_root_cache() {
        if (!isset($this->params['requested'])) {
            return $this->do404();
        }
        
        $rootPages = $this->{$this->modelClass}->find('all', array(
            'conditions' => 'parent_id IS NULL AND url <> \'/\'',
            'recursive' => -1,
            'fields' => array('id', 'url', 'slug'),
        ));
        
        if (!Configure::read('Wildflower.disableRootPageCache')) {
            WildflowerRootPagesCache::write($rootPages);
        }
        
        return $rootPages;
    }
    
    /**
     * Renders a template if it exists depending on the slug
     *
     * @param string $slug
     */
    private function _chooseTemplate($slug) {
        // If there is a specific template for this page render it
        $appWfPages = APP . 'views' . DS . 'wild_pages' . DS;
        
        $templateFile = $appWfPages . $slug . '.ctp';
        $template = $slug;
        
        // For home page home.ctp is the default
        if ($this->isHome) {
            $template = 'home';
            $templateFile = $appWfPages . $template . '.ctp';
        }
        
        if (file_exists($templateFile)) {
            $this->render($template, $this->layout, $templateFile);
        } else {
        	$this->render('view');
        }
    }
    
    /**
     * Set 'parentPages' view variable
     * If page ID is specified this page is excluded from the list
     * 
     * @param int $id Page ID
     */
    private function _setParentSelectBox($id = null) {
        $list = $this->WildPage->getListThreaded($id, 'title');
        $this->set('parentPages', $list);
    }
    
}
