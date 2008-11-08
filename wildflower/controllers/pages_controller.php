<?php
uses('Sanitize');

/**
 * Pages Controller
 *
 * Pages are the heart of every CMS.
 */
class PagesController extends AppController {
	
	public $components = array('RequestHandler', 'Seo');
	public $helpers = array('Cache', 'Form', 'Html', 'List', 'Tree', 'Text', 'Time');
    public $paginate = array(
        'limit' => 25,
        'order' => array('Page.lft' => 'asc')
    );
    
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
        $this->data['Page']['draft'] = 1;
        $this->Page->save($this->data);
        $this->set('data', array('id' => $this->Page->id));
        $this->render('/elements/json');
    }
    
    function admin_create_preview() {
        $cacheDir = TMP . 'preview' . DS;
        
        // Create a unique file name
        $fileName = time();
        $path = $cacheDir . $fileName . '.json';
        while (file_exists($path)) {
            $fileName++;
            $path = $cacheDir . $fileName . '.json';
        }
        
        // Write data to preview file
        $data = json_encode($this->data['Page']);
        file_put_contents($path, $data);
        
        // Garbage collector
        $this->previewCacheGC($cacheDir);
        
        $responce = array('previewFileName' => $fileName);
        $this->set('data', $responce);
        $this->render('/elements/json');
    }
    
    /**
     * View all drafts
     *
     */
    function admin_drafts() {
        $this->Page->recursive = -1;
        $pages = $this->Page->find('all', array('order' => 'created DESC', 'conditions' => 'draft = 1'));
        $this->set(compact('pages'));
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
     * Edit page
     * 
     * @param int $id Page ID
     */
    function admin_edit($id = null) {
        $this->Page->contain(array('User.id', 'User.name'));
        $this->data = $this->Page->findById($id);
        
        // Fill revisions browser
        $revisions = $this->Page->getRevisions($id);
        $this->set(compact('revisions'));
        
        // If viewing a revision, merge with revision content
        if (isset($this->params['named']['rev'])) {
        	$revNum = intval($this->params['named']['rev']);
            $this->data = $this->Page->getRevision($id, $revNum);
            
            $this->set(array('revisionId' => $revNum, 'revisionCreated' => $this->data['Revision']['created']));
        }
        
        $this->populateView($this->data);
    }
    
    function admin_update() {
        $this->data['Page']['user_id'] = intval($this->Session->read('User.User.id')); // @TODO weird, refactor
        $this->Page->create($this->data);
        if (!$this->Page->exists()) return;

        if (!$this->Page->save()) {
            $this->populateView();
            return $this->render('admin_edit');
        }

		$cacheName = str_replace('-', '_', $this->data['Page']['slug']);
		clearCache($cacheName, 'views', '.php');

		if ($this->RequestHandler->isAjax()) {
            $revisions = $this->Page->getRevisions($this->Page->id, 1);
            $this->set(compact('revisions'));
            return $this->render('admin_ajax_update');
        }

        $this->redirect(array('action' => 'edit', $this->Page->id));
    }
    
    private function populateView(&$data) {
        $hasUser = $data['User']['id'] ? true : false;
        $isDraft = ($data['Page']['draft'] == 1) ? true : false;
        $isRevision = isset($this->params['named']['rev']);
        $this->set(compact('isRevision', 'hasUser', 'isDraft'));
        $this->_setParentSelectBox($data['Page']['id']);
        $this->pageTitle = $data['Page']['title'];
    }
    
    /**
     * Pages administration overview
     * 
     */
    function admin_index() {
        $this->Page->recursive = -1;
    	$pages = $this->Page->find('all', array('order' => 'Page.lft ASC'));
    	
    	// Sort the array alpabeticaly for the sidebar display
   		function sortCakeArray($a, $b) {
    		$result = strcmp(low($a['Page']['title']), low($b['Page']['title']));
    		if ($result === 0) {
    			return 0;
    		} else if ($result > 0) {
    			return 1;
    		} else {
    			return -1;
    		}
    	}
    	$sidebarPages = $pages;
    	usort($sidebarPages, 'sortCakeArray');
    	
    	$isTableView = (isset($this->params['named']['view']) and $this->params['named']['view'] == 'table');
    	
    	$this->set(compact('pages', 'sidebarPages', 'isTableView'));
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
            $pagesForSelectBox[$page['Page']['id']] = $page['Page']['title'];
            $pageUrlMap[$page['Page']['id']] = $page['Page']['url'];
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
    		unset($page['Page']['url']);
    		unset($page['Page']['level']);
    		$this->Page->id = $page['Page']['id'];
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
    
    /**
     * Mass add pages
     * Avaible only when DEBUG is set
     * 
     * @param int $count
     */
    function admin_generateRandomPages($count = 5) {
    	if (DEBUG < 1) {
    		return;
    	}
    	set_time_limit(0);
    	$pages = array(
    	   array(
    	       'title' => 'How To Create Awesome Web Applications', 
               'content' => 'Lorem ipsum...'
           ),
           array(
               'title' => 'Lorem impsum dolor sit amet', 
               'content' => 'Lorem ipsum...'
           ),
           array(
               'title' => 'Lorem impsum dolor sit amet', 
               'content' => 'Lorem ipsum...'
           ),
           array(
               'title' => 'Random page', 
               'content' => 'Lorem ipsum...'
           ),
           array(
               'title' => 'Generated from Pages Controller', 
               'content' => 'Lorem ipsum...'
           )
        );
        $this->Page->begin();
        for ($i = 0; $i < $count; $i++) {
        	$randomKey = array_rand($pages);
        	$data = array('Page' => $pages[$randomKey]);
        	if ($this->Page->id && rand(0, 1)) {
        		$data['Page']['parent_id'] = $this->Page->id;
        	}
        	$this->Page->create();
        	$this->Page->save($data);
        }
        $this->Page->commit();
        $this->indexRedirect();
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
     * Return all pages, ready for navigation creation
     *
     * @return array
     */
    function navigation() {
        $this->assertInternalRequest();
    
        if (!isset($this->params['navOptions']['include']) 
            or !is_array($this->params['navOptions']['include'])
            or empty($this->params['navOptions']['include'])) {
            return;
        }
        
        $navPagesIds = $this->params['navOptions']['include'];
        $fields = array('id', 'title', 'lft', 'rght', 'url', 'slug', 'parent_id');
        $order = 'Page.lft ASC';
        $conditions = 'Page.draft = 0';
        
        // Remove strings from $navPagesIds
        $__navPagesIds = $navPagesIds;
        $i = 0;
        foreach ($navPagesIds as $key => $value) {
        	if (!is_integer($key)) {
        	    $__navPagesIds[$i]['Page'] = array(
        	       'id' => null,
        	       'title' => $value[0],
        	       'url' => $value[1]
        	    ); 
                $i++;
                        	    
        	    unset($navPagesIds[$key]);
        	}
        }
    	
    	$_navPagesIds = implode(', ', $navPagesIds);
    	$conditions .= " AND Page.id IN ($_navPagesIds)";
        
        $pages = $this->Page->find('all', compact('fields', 'order', 'conditions'));
        
        //    Append string back to $pages 
        $pagesById = array();
        foreach ($pages as $page) {
            extract($page['Page']);
            $pagesById[$id] = $page;   
        }
debug($__navPagesIds);
        $pages = array_merge($__navPagesIds, $pagesById);
        
        return $pages;
    }
    
    /**
     * Preview a page
     *
     * @param int $id
     */
    function preview($fileName) {
    	$this->assertAdminLoggedIn();
    	
    	$previewPageData = $this->readPreviewCache($fileName);
    	$id = intval($previewPageData['Page']['id']);
        $page = $this->Page->findById($id);
        if (empty($page)) {
            exit("Page with id $id does not exist!");
        }
        
        if (is_array($previewPageData) && !empty($previewPageData)) {
            $page['Page'] = am($page['Page'], $previewPageData['Page']);
        }
        
        // View variables must be exactly the same as with PagesController::view()
        $this->set(array(
            'page' => $page,
            'currentPageId' => $page['Page']['id'],
            'descriptionMetaTag' => $page['Page']['description_meta_tag']
        ));
        
        // Parameters
        $this->_getTruePath($page); //@todo filing breadcrumb should be more elegant
        unset($page['Page']['content']);
        $this->params['breadcrumb'][] = $page['Page'];
        $this->params['current'] = array(
            'type' => 'page', 
            'slug' => $page['Page']['slug'], 
            'id' => $page['Page']['id']);
        
        $this->_chooseTemplate($page['Page']['slug']);
    }
    
    /**
     * Generate random page
     *
     */
    function random($parentSlug = null) {
        $page = array();
        if ($parentSlug) {
            $parentSlug = Sanitize::escape($parentSlug);
            $parentId = $this->Page->field('id', "Page.slug = '$parentSlug'");
            if (!$parentId) {
                return null;
            }
            
            $page = $this->Page->find("Page.parent_id = $parentId", null, 'rand()', 1);
        } else {
            $page = $this->Page->find(null, null, 'rand()', 1);
        }
        
        return $page;
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
        $url = '/' . join('/', $args);

		// Sanitize
        $url = Sanitize::paranoid($url, array('_', '-', '/'));
        
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
        $page = array();        
        if ($this->isHome) {
            $page = $this->Page->find("Page.id = $this->homePageId AND Page.draft = 0");
        } else {
            $slug = end(explode('/', $url));
            $page = $this->Page->find("Page.slug = '$slug' AND Page.draft = 0");
        }

		// Give 404 if no page found or requesting a parents page without a parent in the url
		$isChildWithoutParent = (!$this->isHome and ($page['Page']['url'] !== $url));
		if (empty($page) or $isChildWithoutParent) {
			return $this->do404();
        }
        
        $this->pageTitle = $page['Page']['title'];
        
        // View variables
        $this->set(array(
            'page' => $page,
            'currentPageId' => $page['Page']['id'],
            'isPage' => true
        ));
        
        $this->params['pageMeta'] = array(
            'descriptionMetaTag' => $page['Page']['description_meta_tag'],
            'keywordsMetaTag' => $page['Page']['keywords_meta_tag']
        );
        
        // Parameters @TODO unify parameters
        $this->params['current'] = array(
            'type' => 'page', 
            'slug' => $page['Page']['slug'], 
            'id' => $page['Page']['id']);
        $this->params['Wildflower']['page']['slug'] = $page['Page']['slug'];        
        
        $this->_chooseTemplate($page['Page']['slug']);
    }
    
    /**
     * Read page data from preview cache
     * 
     * @param string $fileName
     * @return array
     */
    private function readPreviewCache($fileName) {
        $previewCachePath = TMP . 'preview' . DS . $fileName . '.json';
        if (!file_exists($previewCachePath)) {
            trigger_error("Cache file $previewCachePath does not exist!");
        }
        
        $json = file_get_contents($previewCachePath);
        $page['Page'] = json_decode($json, true);
        
        return $page;
    }
    
    /**
     * @deprecated: Page has URL field, 
     * @TODO: right now used to fill breadcrumb REFACTOR!
     * 
     * Get true path to a page
     * 
     * @param array $data Cake Page data
     * @return string Path /path/to/a/page
     * @deprecated We have a URL field
     */
    function _getTruePath($data) {
        $id = intval($data['Page']['id']);
        $slug = $data['Page']['slug'];
        $treeLeft = $data['Page']['lft'];
        $treeRight = $data['Page']['rght'];
        // Determine full path to current page (current page not included)
        $ancestors = $this->Page->findPath($treeLeft, $treeRight);
        
        // Add every ancestor to breadcrumb
        $this->breadcrumb = $ancestors;
        $lastSlug = '/';
        foreach ($ancestors as $a) {
            $a['Page']['url'] = "$lastSlug{$a['Page']['slug']}";
            unset($a['Page']['content']);
            $this->params['breadcrumb'][] = $a['Page'];
            $lastSlug .= $a['Page']['slug'] . '/';
        }
        
        // If Home page is defined remove it from $ancestors (only if it's the first item)
        if (isset($ancestors[0]) && $ancestors[0]['Page']['id'] == $this->homePageId) {
            array_shift($ancestors);
        }
        $ancestors = Set::extract($ancestors, '{n}.Page.slug');
        // If not home page, current page slug must be added
        if ($id !== $this->homePageId) {
            $ancestors[] = $slug;
        }
        // Generate path to current page
        $truePath = '';
        if (!empty($ancestors)) {
            $truePath = join('/', $ancestors);
        }
        return "/$truePath";
    }
    
    /**
     * Renders a template if it exists depending on the slug
     *
     * @param string $slug
     */
    private function _chooseTemplate($slug) {
        // If there is a specific template for this page render it
        $pageTemplatesDir = APP . 'views' . DS . 'pages' . DS;
        
        $templateFile = $pageTemplatesDir . $slug . '.ctp';
        $template = $slug;
        
        // For home page home.ctp is the default
        if ($this->isHome) {
            $template = 'home';
            $templateFile = $pageTemplatesDir . $template . '.ctp';
        }
        
        if (file_exists($templateFile)) {
            $this->render($template);
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
        $list = $this->Page->getSelectBoxData($id, 'title');
        $this->set('parentPages', $list);
    }
    
}
