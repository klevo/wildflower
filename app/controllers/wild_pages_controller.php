<?php
uses('Sanitize');
/**
 * Pages Controller
 *
 * Pages are the heart of every CMS.
 */
class WildPagesController extends AppController {
	
	public $components = array('RequestHandler', 'Seo');
	public $helpers = array('Form', 'Html', 'Text', 'Time', 'List', 'Tree');
    public $paginate = array(
        'limit' => 25,
        'order' => array('WildPage.lft' => 'asc')
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
    function wf_about() {
    }
    
    /**
     * Create a new page, with title set, as a draft.
     *
     */
    function wf_create() {
        $this->data[$this->modelClass]['draft'] = 1;
        $this->data[$this->modelClass]['content'] = '';
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
    function wf_edit($id = null) {
        if (isset($this->params['named']['rev'])) {
            $page = $this->WildPage->getRevision($id, $this->params['named']['rev']);
        } else {
            $page = $this->WildPage->findById($id);
        }
        
        $this->data = $page;
        $this->pageTitle = $page[$this->modelClass]['title'];

        $newParentPageOptions = $this->WildPage->getListThreaded();
        $revisions = $this->WildPage->getRevisions($id, 10);
        $isDraft = ($page['WildPage']['draft']);
        $this->set(compact('newParentPageOptions', 'revisions', 'isDraft'));
    }
    
    function wf_preview($id, $previewCacheFileName = null) {
        if (isset($this->params['named']['rev'])) {
            $page = $this->WildPage->getRevision($id, $this->params['named']['rev']);
        } else {
            $page = $this->WildPage->findById($id);
        }
        
        if (!is_null($previewCacheFileName)) {
            $previewData = $this->__readPreviewCache($previewCacheFileName);
            $page = am($page, $previewData);
        }
        
        $this->set(compact('page'));
    }
    
    function wf_options($id = null) {
        $this->WildPage->contain('WildUser');
        $this->data = $this->WildPage->findById($id);
        
        if (empty($this->data)) return $this->cakeError('object_not_found');
        
        $this->pageTitle = $this->data[$this->modelClass]['title'];
        $parentPageOptions = $this->WildPage->getListThreaded($this->data['WildPage']['id']);
        $this->set(compact('parentPageOptions'));
    }
    
    function wf_reorder() {
        $this->pageTitle = 'Reordering pages';
        $this->WildPage->recursive = -1;
        $order = 'lft ASC';
        $fields = array('id', 'lft', 'rght', 'parent_id', 'title');
    	$pages = $this->WildPage->find('all', compact('order', 'fields'));
    	$this->set(compact('pages'));
    }
    
    function wf_sidebar($id = null) {
        $this->WildPage->contain('WildUser');
        $this->data = $this->WildPage->findById($id);
        
        if (empty($this->data)) return $this->cakeError('object_not_found');
        
        $this->pageTitle = $this->data[$this->modelClass]['title'];
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
        
        $oldUrl = $this->WildPage->field('url');
        
        $page = $this->WildPage->save();
        if (empty($page)) return $this->cakeError('save_error');
        
        $this->WildPage->contain('WildUser');
        $page = $this->WildPage->findById($this->WildPage->id);
        
        if (Configure::read('AppSettings.home_page_id') != $this->WildPage->id) {
            $this->WildPage->updateChildPageUrls($this->WildPage->id, $oldUrl, $page['WildPage']['url']);
        }
		$hasUser = $page['WildUser']['id'] ? true : false;
        // JSON response
        if ($this->RequestHandler->isAjax()) {
            $this->data = $page;
            $this->set(compact('page', 'hasUser'));
            return $this->render('wf_update');
        }
        
        $this->redirect(array('action' => 'edit', $this->data[$this->modelClass]['id']));
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
		$this->WildPage->contain('WildSidebar');
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
        
        // Decode custom fields
        $page['WildPage']['custom_fields'] = json_decode($page['WildPage']['custom_fields'], true);
        
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
    function wf_custom_fields($id) {
        $page = $this->WildPage->findById($id);
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
                            App::import('Model', 'WildAsset');
                            $field['value'] = WildAsset::upload($value);
                        }
                    }
                }
            }
            $customFields = json_encode($customFields);
            $this->WildPage->id = intval($id);
            $this->WildPage->saveField('custom_fields', $customFields);
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
    function wf_versions($id = null) {
        $this->WildPage->contain('WildUser');
        $this->data = $this->WildPage->findById($id);
        
        if (empty($this->data)) return $this->cakeError('object_not_found');
        
        $revisions = $this->WildPage->getRevisions($id);
        
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
            $possibleThemeFile = APP . 'views' . DS . 'themed' . DS . $this->theme . DS . 'wild_pages' . DS . $slug . '.ctp';
            if (file_exists($possibleThemeFile)) {
                $render = $possibleThemeFile;
            }
        } else {
            $possibleThemeFile = APP . 'views' . DS . 'wild_pages' . DS . $slug . '.ctp';
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
        $list = $this->WildPage->getListThreaded($id, 'title');
        $this->set('parentPages', $list);
    }
    
}
