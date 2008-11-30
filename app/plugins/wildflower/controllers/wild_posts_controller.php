<?php
class WildPostsController extends WildflowerAppController {
	public $helpers = array('Cache', 'Wildflower.List', 'Rss', 'Wildflower.Textile', 'Wildflower.Category', 'Time');
	/** Pagination options for the wf_index action **/
    public $paginate = array(
        'limit' => 10,
        'order' => array('WildPost.created' => 'desc'),
    );
    
    function wf_add() {
        if (!empty($this->data)) {
            $this->data[$this->modelClass]['draft'] = 1;
            $this->data[$this->modelClass]['uuid'] = sha1(String::uuid());
            if ($this->WildPost->save($this->data)) {
                $this->Session->setFlash('You\'ve written a new post.');
                $this->redirect(array('action' => 'wf_edit', $this->WildPost->id));
            }
        }
    }

    /**
     * Create an empty post and redirect to it's edit screen
     */
    function wf_create() {
        $uuid = sha1(String::uuid()); // @TODO check if unique
        $this->data = array($this->modelClass => array(
            'draft' => 1,
            'uuid' => $uuid,
            'slug' => $uuid,
        ));
        $this->{$this->modelClass}->save($this->data, false);
        $this->redirect(array('action' => 'wf_edit', $this->{$this->modelClass}->id));
    }
    
    /**
     * Posts overview
     * 
     */
    function wf_index() {
        if (isset($this->data['__action'])) {
            foreach ($this->data['id'] as $id => $checked) {
                if (intval($checked) === 1) {
                    switch ($this->data['__action']) {
                        case 'delete':
                            // Delete with comments
                            $this->{$this->modelClass}->delete($id);
                            break;
                        case 'publish':
                            $this->{$this->modelClass}->publish($id);
                            break;
                        case 'draft':
                            $this->{$this->modelClass}->draft($id);
                            break;
                    }
                }
            }
            
            $link = am($this->params['named'], array('action' => 'wf_index'));
            return $this->redirect($link);
        }
        
    	$posts = $this->paginate($this->modelClass);
        $this->set('posts', $posts);
    }

    function wf_create_preview() {
        $cacheDir = TMP . 'preview' . DS;
        
        // Create a unique file name
        $fileName = time();
        $path = $cacheDir . $fileName . '.json';
        while (file_exists($path)) {
            $fileName++;
            $path = $cacheDir . $fileName . '.json';
        }
        
        // Write data to preview file
        $data = json_encode($this->data[$this->modelClass]);
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
    function wf_edit($id = null, $revisionNumber = null) {
        if (empty($this->data)) {
            $this->WildPost->contain(array('WildUser', 'WildCategory'));
            $this->data = $this->WildPost->findById($id);
            if (empty($this->data)) return $this->cakeError('object_not_found');
        } else {
            if ($this->WildPost->save($this->data)) {
                return $this->redirect(array('action' => 'wf_edit', $this->WildPost->id));
            }
        }
        
        // If viewing a revision, merge with revision content
        if ($revisionNumber) {
            $this->data = $this->WildPost->getRevision($id, $revisionNumber);
            
            $this->set(array('revisionId' => $revisionNumber, 'revisionCreated' => $this->data['WildRevision']['created']));
        }
        
        // View
        $hasUser = $this->data['WildUser']['id'] ? true : false;
        $isDraft = ($this->data[$this->modelClass]['draft'] == 1) ? true : false;
        $isRevision = !is_null($revisionNumber);
        
        // Categories
        $categories = $this->WildPost->WildCategory->find('list', array('fields' => array('id', 'title')));
        $inCategories = Set::extract($this->data['WildCategory'], '{n}.id');
        
        // Revisions
        $revisions = $this->WildPost->getRevisions($id);
        
        $this->set(compact('isRevision', 'hasUser', 'isDraft', 'categories', 'inCategories', 'revisions'));
        $this->pageTitle = $this->data[$this->modelClass]['title'];
    }
    
    function wf_edit_categories($id = null) {
        $this->WildPost->contain(array('WildUser', 'WildCategory'));
        $this->data = $this->WildPost->findById($id);
        
        if (empty($this->data)) return $this->cakeError('object_not_found');
   
        $categories = $this->WildPost->WildCategory->find('list', array('fields' => array('id', 'title')));
        
        $this->set(compact('categories'));
        
        $this->pageTitle = $this->data[$this->modelClass]['title'];
    }
    
    function wf_update() {
        var_dump($this->data);
        $this->data[$this->modelClass]['user_id'] = $this->getLoggedInUserId();
        
        $this->WildPost->create($this->data);
        if (!$this->WildPost->exists()) return $this->cakeError('object_not_found');
        
        // Publish?
        if (isset($this->data[$this->modelClass]['publish'])) {
            $this->data[$this->modelClass]['draft'] = 0;
        }

        if (!$this->WildPost->save()) return $this->cakeError('save_error');

		$cacheName = str_replace('-', '_', $this->data[$this->modelClass]['slug']); // @TODO check cache for proper naming method
		clearCache($cacheName, 'views', '.php');
		
        if ($this->RequestHandler->isAjax()) {
            $revisions = $this->WildPost->getRevisions($this->WildPost->id, 1);
            
            $this->WildPost->contain('WildUser');
            $post = $this->WildPost->findById($this->WildPost->id);
            
            $this->set(compact('revisions', 'post'));
            return $this->render('wf_update');
        } else {
            $time = date('h:i A');
            $this->Session->setFlash("$time: Post saved.");
        }

        $this->redirect(array('action' => 'edit', $this->WildPost->id));
    }
    
    function beforeFilter() {
    	parent::beforeFilter();
    	
    	$this->pageTitle = 'Blog';
    	
    	$this->params['current']['type'] = 'post';
    	$this->params['current']['slug'] = Configure::read('Wildflower.blogIndex');
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
    	
    	$this->WildPost->Category->Behaviors->attach('Containable');
        $this->WildPost->Category->contain("Post.id");
        $category = $this->WildPost->Category->findBySlug($slug);

        $ids = array();
        foreach ($category[$this->modelClass] as $post) {
            $ids[] = $post['id'];
        }
        
        $in = implode(', ', $ids);
        $scope = "Post.id IN ($in)";
    	$posts = $this->paginate($this->modelClass, $scope);
    	
    	$this->set(array('posts' => $posts, 'postsCategory' => $category));
    }
    
    /**
     * RSS feed for posts
     *
     * Also echoes the XML definition. (when in view there is a whitespace before it <- bad)
     */
    function feed() {
        $this->layout = 'rss/default';
        $posts = $this->WildPost->findAll(null, null, 'WildPost.created desc');
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
    	
    	$this->pageTitle = 'Blog';
    	
        $this->paginate = array(
	        'limit' => 5,
	        'order' => array('WildPost.created' => 'desc'),
			'conditions' => 'WildPost.draft = 0'
	    );
	    $posts = $this->paginate($this->modelClass);
	    
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
    function wf_preview($fileName) {
    	$this->layout = 'default';
    	
        $previewPostData = $this->readPreviewCache($fileName);
        $id = intval($previewPostData[$this->modelClass]['id']);
        $post = $this->WildPost->findById($id);
        if (empty($post)) $this->cakeError('object_not_found');
        
        if (is_array($previewPostData) && !empty($previewPostData)) {
            unset($previewPostData[$this->modelClass]['created']);
            $post[$this->modelClass] = am($post[$this->modelClass], $previewPostData[$this->modelClass]);
        }
        
        $this->set(array(
            'post' => $post,
            'descriptionMetaTag' => $post[$this->modelClass]['description_meta_tag']
        ));
        
        // Parameters
        $this->pageTitle = $post[$this->modelClass]['title'];
        
        $this->render('view');
    }
    
    /**
     * View a post
     * 
     * @param string $slug
     */
    function view() {
        $this->acceptComment();
        
		if (Configure::read('AppSettings.cache') == 'on') {
            $this->cacheAction = 60 * 60 * 24 * 3; // Cache for 3 days
        }

        $uuid = Sanitize::paranoid($this->params['uuid']);
        $post = $this->WildPost->findByUuidAndDraft($uuid, 0);

		if (empty($post)) {
			return $this->do404();
		}
        
        // Post title
        $this->pageTitle = $post[$this->modelClass]['title'];
        
        if (isset($this->params['requested'])) {
            return $post;
        }
        
        $this->set(array(
            'post' => $post,
            'descriptionMetaTag' => $post[$this->modelClass]['description_meta_tag']
        ));
    }
    
    /**
     * Allow an action to accept a comment submit
     *
     * @return void
     */
    function acceptComment() {
        if (empty($this->data)) return;

        $this->WildPost->WildComment->spamCheck = true;
        if ($this->WildPost->WildComment->save($this->data)) {
            $this->Session->setFlash('Comment succesfuly added.');
            $postId = intval($this->data['WildComment']['post_id']);
            $postSlug = $this->WildPost->field('slug', "id = $postId");
            $postLink = '/' . Configure::read('Wildflower.blogIndex') . "/$postSlug";

            // Clear post cache
            $cacheName = str_replace('-', '_', $postSlug);
            clearCache($cacheName, 'views', '.php');

            $this->redirect($this->data['WildPost']['permalink'] . '#comment-' . $this->WildPost->WildComment->id);
        } else {
            $post = $this->WildPost->findById(intval($this->data['WildComment']['post_id']));
            $this->set('post', $post);
            $this->render('view');
        }  
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
        $post[$this->modelClass] = json_decode($json, true);
        
        return $post;
    }
    
}
