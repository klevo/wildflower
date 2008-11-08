<?php
class PostsController extends AppController {
	public $helpers = array('Cache', 'Category', 'Form', 'Habtm', 'Html', 'List', 'PostsList', 'Rss', 'Textile', 'Time');
    public $paginate = array(
        'limit' => 20,
        'order' => array('Post.created' => 'desc'),
        'fields' => array('id', 'title', 'slug', 'draft', 'created')
    );

    /**
     * Create a new post, with title set, as a draft.
     *
     */
    function admin_create() {
        $this->data['Post']['draft'] = 1;
        $this->Post->save($this->data);
        $this->set('data', array('id' => $this->Post->id));
        $this->render('/elements/json');
    }
    
    /**
     * Posts overview
     * 
     */
    function admin_index() {
    	$posts = $this->paginate('Post');
        $this->set('posts', $posts);
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
        $data = json_encode($this->data['Post']);
        file_put_contents($path, $data);
        
        // Garbage collector
        $this->previewCacheGC($cacheDir);
        
        $responce = array('previewFileName' => $fileName);
        $this->set('data', $responce);
        $this->render('/elements/json');
    }
    
    /**
     * Edit page
     * 
     * @param int $id post ID
     */
    function admin_edit($id = null) {
        $this->Post->contain(array('User.id', 'User.name', 'Category'));
        $this->data = $this->Post->findById($id);
        
        // Fill revisions browser
        $revisions = $this->Post->getRevisions($id);
        $this->set(compact('revisions'));
        
        // If viewing a revision, merge with revision content
        if (isset($this->params['named']['rev'])) {
            $revNum = intval($this->params['named']['rev']);
            $this->data = $this->Post->getRevision($id, $revNum);
            
            $this->set(array('revisionId' => $revNum, 'revisionCreated' => $this->data['Revision']['created']));
        }
        
        $this->populateView($this->data);
    }

    /**
     * @deprecated 
     *
     * @return unknown
     */
    function admin_findAll() {
        return $this->Post->findAll(null, array('id', 'slug', 'title'), 'Post.created DESC', null, 1, 0);
    }
    
    /**
     * @deprecated 
     *
     * @return unknown
     */
    function admin_list() {
    	$posts = $this->Post->generateList();
    	return $posts;
    }
    
    function admin_update() {
        $this->data['Post']['user_id'] = intval($this->Session->read('User.User.id')); // @TODO weird, refactor
        $this->Post->create($this->data);
        if (!$this->Post->exists()) return;

        if (!$this->Post->save()) {
            $this->populateView();
            return $this->render('admin_edit');
        }

		$cacheName = str_replace('-', '_', $this->data['Post']['slug']);
		clearCache($cacheName, 'views', '.php');
		
        if ($this->RequestHandler->isAjax()) {
            $revisions = $this->Post->getRevisions($this->Post->id, 1);
            $this->set(compact('revisions'));
            return $this->render('admin_ajax_update');
        }

        $this->redirect(array('action' => 'edit', $this->Post->id));
    }
    
    function beforeFilter() {
    	parent::beforeFilter();
    	$this->params['current']['type'] = 'post';
    	$this->params['current']['slug'] = WILDFLOWER_POSTS_INDEX;
    }
    
    function beforeRender() {
        parent::beforeRender();
        $this->set('isPosts', true);
        $this->params['Wildflower']['view']['isPosts'] = true;
    }
    
    /**
     * Display posts from a certain category
     *
     */
    function category() {
    	$slug = WildflowerHelper::slug($this->params['slug']);
    	
    	$this->Post->Category->Behaviors->attach('Containable');
        $this->Post->Category->contain("Post.id");
        $category = $this->Post->Category->findBySlug($slug);

        $ids = array();
        foreach ($category['Post'] as $post) {
            $ids[] = $post['id'];
        }
        
        $in = implode(', ', $ids);
        $scope = "Post.id IN ($in)";
    	$posts = $this->paginate('Post', $scope);
    	
    	$this->set(array('posts' => $posts, 'postsCategory' => $category));
    }
    
    /**
     * RSS feed for posts
     *
     * Also echoes the XML definition. (when in view there is a whitespace before it <- bad)
     */
    function feed() {
        $this->layout = 'rss/default';
        $posts = $this->Post->findAll(null, null, 'Post.created desc');
        $this->set('posts', $posts);
        header('Content-type: text/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>';
    }
     
    /**
     * Posts index
     * 
     */
    function index() {
    	$this->cacheAction = true;
    	
    	$this->params['breadcrumb'][] = array('title' => 'News');
    	$this->pageTitle = 'News';
    	
        $this->paginate = array(
	        'limit' => 5,
	        'order' => array('Post.created' => 'desc'),
			'conditions' => 'Post.draft = 0'
	    );
	    $posts = $this->paginate('Post');
	    
        if (isset($this->params['requested'])) {
            return $posts;
        }
        $this->set('posts', $posts);
    }
    
    /**
     * Preview a post
     *
     * @param int $id
     */
    function preview($fileName) {
        $this->assertAdminLoggedIn();
        
        $previewPostData = $this->readPreviewCache($fileName);
        $id = intval($previewPostData['Post']['id']);
        $post = $this->Post->findById($id);
        if (empty($post)) {
            exit("Post with id $id does not exist!");
        }
        
        if (is_array($previewPostData) && !empty($previewPostData)) {
            unset($previewPostData['Post']['created']);
            $post['Post'] = am($post['Post'], $previewPostData['Post']);
        }
        
        $this->set(array(
            'post' => $post,
            'descriptionMetaTag' => $post['Post']['description_meta_tag']
        ));
        
        // Parameters
        $this->pageTitle = $post['Post']['title'];
        $this->params['breadcrumb'][] = array('title' => 'News', 'url' => '/' . WILDFLOWER_POSTS_INDEX);
        $this->params['breadcrumb'][] = array('title' => $post['Post']['title']);
        
        $this->render('view');
    }
    
    /**
     * View a post
     * 
     * @param string $slug
     */
    function view() {
		if (Configure::read('AppSettings.cache') == 'on') {
            $this->cacheAction = 60 * 60 * 24 * 3; // Cache for 3 days
        }

        $slug = WildflowerHelper::slug($this->params['slug']);
        $post = $this->Post->findBySlugAndDraft($slug, 0);

		if (empty($post)) {
			return $this->do404();
		}
        
        // Post title
        $this->pageTitle = $post['Post']['title'];
        
        if (isset($this->params['requested'])) {
            return $post;
        }
        
        $this->set(array(
            'post' => $post,
            'descriptionMetaTag' => $post['Post']['description_meta_tag']
        ));
    }
    
    private function populateView(&$data) {
        $hasUser = $data['User']['id'] ? true : false;
        $isDraft = ($data['Post']['draft'] == 1) ? true : false;
        $isRevision = isset($this->params['named']['rev']);
        
        // Checkboxes for categories @TODO data logic to model
        $selectedCategories = $this->data['Category'];
        if (!empty($selectedCategories)) {
            $selectedCategories = array_combine(
                Set::extract($selectedCategories, '{n}.id'),
                Set::extract($selectedCategories, '{n}.title'));
        }
        $categories = $this->Post->Category->getSelectBoxData();
        
        $this->set(compact('isRevision', 'hasUser', 'isDraft', 'categories', 'selectedCategories'));
        $this->pageTitle = $data['Post']['title'];
    }
     
    /**
     * Read post data from preview cache
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
        $post['Post'] = json_decode($json, true);
        
        return $post;
    }
    
}
