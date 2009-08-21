<?php
/**
 * Pages Controller
 *
 * Pages are the heart of every CMS.
 */
class PagesController extends AppController {
	
	public $components = array('RequestHandler', 'Seo');
	public $helpers = array('Form', 'Html', 'Text', 'Time', 'List', 'Tree');
    public $paginate = array(
        'limit' => 25,
        'order' => array('Page.lft' => 'asc')
    );
    public $pageTitle = 'Pages';
    
    function beforeFilter() {
        parent::beforeFilter();
        if (Configure::read('Wildflower.htmlCache') and $this->params['action'] == 'view') {
            $this->helpers[] = 'HtmlCache';
        }
    }
    
    /**
     * A static about Wildflower page
     *
     */
    function admin_about() {
    }
    
    /**
     * Create a new page, with title set, as a draft.
     *
     */
    function admin_create() {
        $this->data[$this->modelClass]['draft'] = 1;
        $this->data[$this->modelClass]['content'] = '';
        $this->Page->create($this->data);
        $this->Page->save();
        $this->redirect(array('action' => 'edit', $this->Page->id));
    }

    /**
     * @TODO not implemented yet. Steal something from Wordpress :)
     * 
     * Show difference between current version and a revision
     *
     * @param int $pageId
     */
    function admin_diff($pageId, $revisionId) {
        $pageDiff = $this->Page->revisionDiff($pageId, $revisionId);
        $this->set('revisionDiff', $pageDiff);
    }
    
    /**
     * @TODO not done yet
     *
     * Discard any unsaved changes to a page
     *
     * @param int $id
     */
    function admin_discardChanges($id = null, $actionAfter = null) {
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
    function admin_edit($id = null) {
        if (isset($this->params['named']['rev'])) {
            $page = $this->Page->getRevision($id, $this->params['named']['rev']);
        } else {
            $page = $this->Page->findById($id);
        }
        
        $this->data = $page;
        $this->pageTitle = $page[$this->modelClass]['title'];

        $newParentPageOptions = $this->Page->getListThreaded();
        $revisions = $this->Page->getRevisions($id, 10);
        $isDraft = ($page['Page']['draft']);
        $this->set(compact('newParentPageOptions', 'revisions', 'isDraft'));
    }
    
    function admin_preview($id, $previewCacheFileName = null) {
        if (isset($this->params['named']['rev'])) {
            $page = $this->Page->getRevision($id, $this->params['named']['rev']);
        } else {
            $page = $this->Page->findById($id);
        }
        
        if (!is_null($previewCacheFileName)) {
            $previewData = $this->__readPreviewCache($previewCacheFileName);
            $page = am($page, $previewData);
        }
        
        $this->set(compact('page'));
    }
    
    function admin_options($id = null) {
        $this->Page->contain('User');
        $this->data = $this->Page->findById($id);
        
        if (empty($this->data)) return $this->cakeError('object_not_found');
        
        $this->pageTitle = $this->data[$this->modelClass]['title'];
        $parentPageOptions = $this->Page->getListThreaded($this->data['Page']['id']);
        $this->set(compact('parentPageOptions'));
    }
    
    function admin_reorder() {
        $this->pageTitle = 'Reordering pages';
        $this->Page->recursive = -1;
        $order = 'lft ASC';
        $fields = array('id', 'lft', 'rght', 'parent_id', 'title');
    	$pages = $this->Page->find('all', compact('order', 'fields'));
    	$this->set(compact('pages'));
    }
    
    function admin_sidebar($id = null) {
        $this->Page->contain('User');
        $this->data = $this->Page->findById($id);
        
        if (empty($this->data)) return $this->cakeError('object_not_found');
        
        $this->pageTitle = $this->data[$this->modelClass]['title'];
    }
    
    function admin_update() {
        $this->data[$this->modelClass]['user_id'] = $this->getLoggedInUserId();
        
        $this->Page->create($this->data);
        if (!$this->Page->exists()) return $this->cakeError('object_not_found');
        
        // Publish?
        if (isset($this->data['__save']['publish'])) {
            $this->data[$this->modelClass]['draft'] = 0;
        }
        unset($this->data['__save']);
        
        $oldUrl = $this->Page->field('url');
        
        $page = $this->Page->save();
        if (empty($page)) return $this->cakeError('save_error');
        
        $this->Page->contain('User');
        $page = $this->Page->findById($this->Page->id);
        
        if (Configure::read('AppSettings.home_page_id') != $this->Page->id) {
            $this->Page->updateChildPageUrls($this->Page->id, $oldUrl, $page['Page']['url']);
        }
		$hasUser = $page['User']['id'] ? true : false;
        // JSON response
        if ($this->RequestHandler->isAjax()) {
            $this->data = $page;
            $this->set(compact('page', 'hasUser'));
            return $this->render('admin_update');
        }
        
        $this->redirect(array('action' => 'edit', $this->data[$this->modelClass]['id']));
    }
    
    /**
     * Pages administration overview
     * 
     */
    function admin_index() {
        $this->pageTitle = 'Pages';
        $this->Page->recursive = -1;
    	$pages = $this->Page->find('all', array('order' => 'lft ASC'));
    	$newParentPageOptions = $this->Page->getListThreaded();
    	$this->set(compact('pages', 'newParentPageOptions'));
    }
    
    /**
     * Insert link dialog
     *
     */
    function admin_link() {
        $this->autoLayout = false;
        $pages = $this->Page->findAll();
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
        
        $this->layout = 'admin_dialog';
    }
    
    /**
     * Move a page up in the hierarchy
     *
     * @param int $id
     */
    function admin_moveup($id = null) {
    	$this->Page->moveup($id);
    	$this->Session->write('ChangeIndicator.id', $id);
        $this->redirect("/admin/pages/#page-$id");
    }
    
    /**
     * Move a page down in the hierarchy
     *
     * @param int $id
     */
    function admin_movedown($id = null) {
        $this->Page->movedown($id);
        $this->Session->write('ChangeIndicator.id', $id);
        $this->redirect("/admin/pages/#page-$id");
    }
    
    /**
     * Regenerate URL field for each page
     * 
     * Maintenance function.
     */
    function admin_setUrlFields() {
    	$pages = $this->Page->findAll();
    	
    	// Resave each page
    	$success = true;
    	foreach ($pages as $page) {
    		// Unset URL field, we want them to be generated anew
    		unset($page[$this->modelClass]['url']);
    		unset($page[$this->modelClass]['level']);
    		$this->Page->id = $page[$this->modelClass]['id'];
    		if (!$this->Page->save($page)) {
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
		// b2c may resolve this issue. is this relevent or not - http://github.com/klevo/wildflower/issues#issue/7
        return $this->Page->findBySlug($slug);
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
        return $this->Page->find('all', compat('conditions', 'fields', 'order'));
    }
    
    function getChildPagesForMenu($pageSlug) {
        $this->assertInternalRequest();
        return $this->Page->getChildrenForMenu($pageSlug);
    }

    /**
     * View a page
     * 
     * Handles redirect if the correct url for page is not entered.
     */
    function view() {
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
        if ($url === '//' or $args === $homeArgs or $url === '/app/webroot/') {
            $this->isHome = true;
        }
        
        $this->params['Wildflower']['view']['isHome'] = $this->isHome;
        
        // Find the requested page
		$this->Page->contain('Sidebar');
        $page = array();
        
        if (isset($this->params['id'])) {
            $page = $this->Page->findByIdAndDraft($this->params['id'], 0);
        } else if ($this->isHome) {
            $page = $this->Page->findByIdAndDraft($this->homePageId, 0);
        } else {
            $slug = end(explode('/', $url));
	        $slug = self::slug($slug);
            $page = $this->Page->findBySlugAndDraft($slug, 0);
        }

		// Give 404 if no page found or requesting a parents page without a parent in the url
		$isChildWithoutParent = (!$this->isHome and ($page[$this->modelClass]['url'] !== $url));
		if (empty($page) or $isChildWithoutParent) {
			return $this->do404();
        }
        
        $this->pageTitle = $page[$this->modelClass]['title'];
        
        // Decode custom fields
        $page['Page']['custom_fields'] = json_decode($page['Page']['custom_fields'], true);
        
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
        $this->set($this->params['pageMeta']);
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
        
        // Get all pages without a parent except the home page and also all the home page children
        $homePageId = Configure::read('Wildflower.settings.home_page_id');
        $rootPages = $this->{$this->modelClass}->find('all', array(
            'conditions' => "parent_id IS NULL AND url <> '/' OR parent_id = $homePageId",
            'recursive' => -1,
            'fields' => array('id', 'url', 'slug'),
        ));
        
        if (!Configure::read('Wildflower.disableRootPageCache')) {
            WildflowerRootPagesCache::write($rootPages);
        }
        
        return $rootPages;
    }
    
    /**
     * Edit and save page custom fields
     *
     * @param int $id Page ID
     */
    function admin_custom_fields($id) {
        $page = $this->Page->findById($id);
        $customFields = $page[$this->modelClass]['custom_fields'];
        $customFields = json_decode($customFields, true);
        
        if (!empty($this->data)) {
            foreach ($customFields as &$field) {
                foreach ($this->data[$this->modelClass] as $name => $value) {
                    if ($field['name'] == $name) {
                        if ($field['type'] != 'file') {
                            $field['value'] = $value;
                        }
                        
                        // Upload file
                        if ($field['type'] == 'file' and !empty($value['name'])) {
                            App::import('Model', 'Asset');
                            $field['value'] = Asset::upload($value);
                        }
                    }
                }
            }
            $customFields = json_encode($customFields);
            $this->Page->id = intval($id);
            $this->Page->saveField('custom_fields', $customFields);
            return $this->redirect(array('action' => 'custom_fields', $id));
        }
        
        $this->data = $page;
        $this->set(compact('customFields'));
    }
    
    /**
     * View a page
     * 
     * Handles redirect if the correct url for page is not entered.
     */
    function admin_versions($id = null) {
        $this->Page->contain('User');
        $this->data = $this->Page->findById($id);
        
        if (empty($this->data)) return $this->cakeError('object_not_found');
        
        $revisions = $this->Page->getRevisions($id);
        
        $this->set(compact('parentPageOptions', 'revisions'));
        $this->pageTitle = 'Version of page ' . $this->data[$this->modelClass]['title'];
    }
    
    /**
     * Renders a normal page view or home view
     *
     * @param string $slug
     */
    private function _chooseTemplate($slug) {
        // For home page home.ctp is the default
        $template = 'view';
        if ($this->isHome) {
            $template = 'home';
        }
        $render = $template;
        
        if (isset($this->theme)) {
            $possibleThemeFile = APP . 'views' . DS . 'themed' . DS . $this->theme . DS . 'pages' . DS . $slug . '.ctp';
            if (file_exists($possibleThemeFile)) {
                $render = $possibleThemeFile;
            }
        } else {
            $possibleThemeFile = APP . 'views' . DS . 'pages' . DS . $slug . '.ctp';
            if (file_exists($possibleThemeFile)) {
                $render = $possibleThemeFile;
            }
        }
        
        return $this->render($render);
    }
    
    /**
     * Set 'parentPages' view variable
     * If page ID is specified this page is excluded from the list
     * 
     * @param int $id Page ID
     */
    private function _setParentSelectBox($id = null) {
        $list = $this->Page->getListThreaded($id, 'title');
        $this->set('parentPages', $list);
    }
    
}
