<?php
class PostsController extends AppController {
	public $helpers = array(
	    'Cache', 
	    'List', 
	    'Rss', 
	    'Textile', 
	    'Category', 
	    'Tree', 
	    'Time',
	    'Paginator',
	);
	public $components = array('Email');
	
	/** Pagination options for the admin_index action **/
    public $paginate = array(
        'limit' => 12,
        'order' => array('Post.created' => 'desc'),
    );

    /**
     * Create a post and redirect to it's edit screen
     *
     */
    function admin_create() {
        // Generate UUID
        $uuid = sha1(String::uuid()); 
        // Check if unique
        while ($this->{$this->modelClass}->findByUuid($uuid)) {
            $uuid = sha1(String::uuid()); 
        }
        
        $defaultParams = array(
            'draft' => 1,
            'uuid' => $uuid
        );
        $this->data[$this->modelClass] = am($this->data[$this->modelClass], $defaultParams);
        $this->{$this->modelClass}->create($this->data);
        $this->{$this->modelClass}->save();
        $this->redirect(array('action' => 'admin_edit', $this->{$this->modelClass}->id));
    }
    
    /**
     * Manage post's comments
     * 
     */
    function admin_comments($id = null, $status = null) {
        $spam = ($status == 'spam') ? 1 : 0;
        $approved = ($status == 'unapproved') ? 0 : 1;
        if ($spam) {
            // Spam comments should show no matter of approval status
            $approved = array(0, 1);
        }
        
        $this->data = $this->{$this->modelClass}->find('first', array(
            'conditions' => array('Post.id' => $id),
            'contain' => array(
                'Comment' => array(
                    'order' => 'Comment.created DESC',
                    'conditions' => array(compact('spam', 'approved'))
                ),
                'User'
            )
        ));
        
        $goBackAction = $this->referer(array('action' => 'edit', $this->data['Post']['id']));
        $this->set('goBackAction', $goBackAction);
    }
    
    /**
     * Posts overview
     * 
     */
    function admin_index() {
    	$posts = $this->paginate($this->modelClass);
        $this->set('posts', $posts);
    }

    /**
     * Edit a post
     * 
     * @param int $id
     */
    function admin_edit($id = null, $revisionNumber = null) {
        $this->Post->contain(array('User', 'Category'));
        $this->data = $this->Post->findById($id);
        //var_dump($this->data);
        
        // If viewing a revision, merge with revision content
        if ($revisionNumber) {
            $this->data = $this->Post->getRevision($id, $revisionNumber);
            
            $this->set(array('revisionId' => $revisionNumber, 'revisionCreated' => $this->data['Revision']['created']));
        }
        
        // View
        $hasUser = $this->data['User']['id'] ? true : false;
        $isDraft = ($this->data[$this->modelClass]['draft'] == 1) ? true : false;
        $isRevision = !is_null($revisionNumber);
        
        // Categories for select box
        $categories = array();
        if (is_integer(Configure::read('App.blogCategoryId'))) { // @TODO document blogCategoryId
            $_categories = $this->Post->Category->children(Configure::read('App.blogCategoryId'));
            // Transform to list
            foreach ($_categories as $cat) {
                $categories[$cat['Category']['id']] = $cat['Category']['title'];
            }
        } else {
            $categories = $this->Post->Category->find('list', array('fields' => array('id', 'title')));
        }
        
        $inCategories = Set::extract($this->data['Category'], '{n}.id');
        
        $categoryId = isset($inCategories[0]) ? $inCategories[0] : null;
        
        $this->set(compact('isRevision', 'hasUser', 'isDraft', 'categories', 'inCategories', 'categoryId'));
        $this->pageTitle = $this->data[$this->modelClass]['title'];
    }
    
    function admin_categorize($id = null) {
        $this->Post->contain(array('User', 'Category'));
        $this->data = $this->Post->findById($id);
        
        if (empty($this->data)) return $this->cakeError('object_not_found');
   
        $categories = $this->Post->Category->find('list', array('fields' => array('id', 'title')));
        $inCategories = Set::extract($this->data['Category'], '{n}.id');
        $isDraft = ($this->data[$this->modelClass]['draft'] == 1) ? true : false;
        $categoriesForTree = $this->Post->Category->find('all', array('order' => 'lft ASC', 'recursive' => -1));
        $this->set(compact('categories', 'inCategories', 'isDraft', 'categoriesForTree'));
        
        $this->pageTitle = $this->data[$this->modelClass]['title'];
    }
    
    function admin_options($id = null) {
        $this->Post->contain(array('User', 'Category'));
        $this->data = $this->Post->findById($id);
        
        if (empty($this->data)) return $this->cakeError('object_not_found');
   
        $isDraft = ($this->data[$this->modelClass]['draft'] == 1) ? true : false;
        $this->set(compact('isDraft'));
        
        $this->pageTitle = $this->data[$this->modelClass]['title'];
    }
    
    function admin_update() {
        //fb($this->data);
        $this->data[$this->modelClass]['user_id'] = $this->getLoggedInUserId();

        // Publish?
        if (isset($this->data['__save']['publish'])) {
            $this->data[$this->modelClass]['draft'] = 0;
        }
        unset($this->data['__save']);
        
        if (isset($this->data[$this->modelClass]['slug'])) {
            $this->data[$this->modelClass]['slug'] = AppHelper::slug($this->data[$this->modelClass]['slug']);
        }
        
        $this->Post->create($this->data);
        
        if (!$this->Post->exists()) return $this->cakeError('object_not_found');
        
        if (isset($this->data[$this->modelClass]['categories_can_be_empty']) && !isset($this->data['Category'])) {
             // Delete all post categories
             $this->Post->query("DELETE FROM categories_posts WHERE post_id = {$this->Post->id}");
        }

        if (!$this->Post->save()) return $this->cakeError('save_error'); // @TODO Rendering the exact save errors would be better

        // $cacheName = str_replace('-', '_', $this->data[$this->modelClass]['slug']); // @TODO check cache for proper naming method
        // clearCache($cacheName, 'views', '.php');
		
        if ($this->RequestHandler->isAjax()) {
            $this->Post->contain('User');
            $this->data = $post = $this->Post->findById($this->Post->id); // @TODO clean up
            $this->set(compact('post'));
            return $this->render('admin_update');
        }

        $this->redirect(array('action' => 'edit', $this->Post->id));
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
    
    // /**
    //  * Display posts from a certain category
    //  *
    //  */
    // function category() {
    //  $slug = WildflowerHelper::slug($this->params['slug']);
    //  
    //  $this->Post->Category->Behaviors->attach('Containable');
    //     $this->Post->Category->contain("Post.id");
    //     $category = $this->Post->Category->findBySlug($slug);
    // 
    //     $ids = array();
    //     foreach ($category[$this->modelClass] as $post) {
    //         $ids[] = $post['id'];
    //     }
    //     
    //     $in = implode(', ', $ids);
    //     $scope = "Post.id IN ($in)";
    //  $posts = $this->paginate($this->modelClass, $scope);
    //  
    //  $this->set(array('posts' => $posts, 'postsCategory' => $category));
    // }
    // 
    /**
     * RSS feed for posts
     *
     */
    function rss() {
        $this->layout = 'rss/default';
        $posts = $this->Post->find('all', array(
             'order' => 'Post.created DESC',
             'contain' => 'User',
        ));
        $this->set(compact('posts'));
        $this->RequestHandler->respondAs('text/xml');
    }
     
    /**
     * Posts index
     * 
     */
    function index() {
        $this->cacheAction = true;
        
        $this->pageTitle = 'Blog';
        
        $this->paginate = array(
            'limit' => 4,
            'order' => array('Post.created' => 'desc'),
            'conditions' => 'Post.draft = 0'
        );
        $posts = $this->paginate($this->modelClass);
        
        if (isset($this->params['requested'])) {
            return $posts;
        }
        
        $sidebarCategories = $this->Post->Category->find('all', array(
            'order' => 'lft ASC', 
            'recursive' => -1, 
            'conditions' => array('parent_id' => Configure::read('App.blogCategoryId')),
        ));
        
        // Sidebar for blog
        $wfPostsSidebar = ClassRegistry::init('Sidebar')->findBlogSidebar();
        
        $this->set(compact('posts', 'sidebarCategories', 'wfPostsSidebar'));
    }
    
    /**
     * View posts from one category
     * 
     */
    function category() {
        //$this->cacheAction = true;
        
        $this->pageTitle = 'Blog';
        
        $this->Post->Category->recursive = -1;
        $category = $this->Post->Category->findBySlug($this->params['slug']);
        $posts = $this->Post->CategoriesPost->find('all', array(
            'conditions' => array(
                'category_id' => $category['Category']['id'],
            )
        ));
        $postsIds = Set::extract($posts, '{n}.CategoriesPost.post_id');
        
        $this->paginate = array(
            'limit' => 10,
            'order' => array(
                'Post.created' => 'desc'
            ),
            'conditions' => array(
                'Post.draft' => 0,
                'Post.id' => $postsIds,
            ),
        );
        $posts = $this->paginate($this->modelClass);
        
        if (isset($this->params['requested'])) {
            return $posts;
        }
        
        $sidebarCategories = $this->Post->Category->find('all', array(
            'order' => 'lft ASC', 
            'recursive' => -1, 
            'conditions' => array('parent_id' => Configure::read('App.blogCategoryId')),
        ));
        
        // Sidebar for blog
        $wfPostsSidebar = ClassRegistry::init('Sidebar')->findBlogSidebar();
        
        $this->set(compact('posts', 'sidebarCategories', 'wfPostsSidebar'));
        $this->render('index');
    }
    
    /**
     * View a post
     * 
     * @param string $slug
     */
    function view() {
        $this->_acceptComment();
        
		if (Configure::read('AppSettings.cache') == 'on') {
            $this->cacheAction = 60 * 60 * 24 * 3; // Cache for 3 days
        }

        $slug = $this->params['slug'];
        $this->Post->contain(array(
            'User', 
            'Category',
            'Comment' => array(
                'conditions' => array('spam' => 0, 'approved' => 1),
            ),
        ));
        $post = $this->Post->findBySlugAndDraft($slug, 0);

		if (empty($post)) {
			return $this->do404();
		}
        
        // Post title
        $this->pageTitle = $post[$this->modelClass]['title'];
        
        if (isset($this->params['requested'])) {
            return $post;
        }
        
        $sidebarCategories = $this->Post->Category->find('all', array(
            'order' => 'lft ASC', 
            'recursive' => -1, 
            'conditions' => array('parent_id' => Configure::read('App.blogCategoryId')),
        ));
        
        // Sidebar for blog
        $wfPostsSidebar = ClassRegistry::init('Sidebar')->findBlogSidebar();
        
        $this->set(array(
            'post' => $post,
            'descriptionMetaTag' => $post[$this->modelClass]['description_meta_tag'],
            'sidebarCategories' => $sidebarCategories,
            'wfPostsSidebar' => $wfPostsSidebar
        ));
    }
    
    /**
     * Allow an action to accept a comment submit
     *
     * @return void
     */
    private function _acceptComment() {
        if (empty($this->data)) return; // Else we would have a redirect loop
        
        if (Configure::read('Wildflower.settings.approve_comments')) {
            $this->data['Comment']['approved'] = 0;
        }
        
        $this->Post->Comment->spamCheck = true;
        if ($this->Post->Comment->save($this->data)) {
            $postId = intval($this->data['Comment']['post_id']);

            // Clear post cache
            // @TODO find out better method
            // $cacheName = str_replace('-', '_', $postSlug);
            // clearCache($cacheName, 'views', '.php');
            
            // Email alert
            // @TODO create a function in app_controller to be used in messages too
            $this->Email->to = Configure::read('Wildflower.settings.contact_email');
    		$this->Email->from = $this->data['Comment']['email'];
    		$this->Email->replyTo = $this->data['Comment']['email'];
    		$this->Email->subject = Configure::read('Wildflower.settings.site_name') . ' - new comment from ' . $this->data['Comment']['name'];
    		$this->Email->sendAs = 'text';
    		$this->Email->template = 'new_comment_notification';

    		$this->set($this->data['Comment']);
    		$message = $this->data['Comment']['content']; // @TODO remove Textile syntax - to plain text
    		$this->set(compact('postId', 'message'));

    		$this->Email->delivery = Configure::read('Wildflower.settings.email_delivery');
    		if ($this->Email->delivery == 'smtp') {
        		$this->Email->smtpOptions = array(
                    'username' => Configure::read('Wildflower.settings.smtp_username'),
                    'password' => Configure::read('Wildflower.settings.smtp_password'),
                    'host' => Configure::read('Wildflower.settings.smtp_server'),
        		    'port' => 25, // @TODO add port to settings
        		    'timeout' => 30
        		);
    		}
    		
    		$this->Email->send();
            
            $message = __('Comment succesfuly posted.', true);
            if (Configure::read('Wildflower.settings.approve_comments')) {
                $message = __('Your comment will be posted after it\'s approved by the administrator.', true);
            }
            $this->Session->setFlash($message);

            $this->redirect($this->data['Post']['permalink'] . '#comment-' . $this->Post->Comment->id);
        }
    }
    
}
