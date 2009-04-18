<?php
class WildPostsController extends AppController {
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
	
	/** Pagination options for the wf_index action **/
    public $paginate = array(
        'limit' => 12,
        'order' => array('WildPost.created' => 'desc'),
    );

    /**
     * Create a post and redirect to it's edit screen
     *
     */
    function wf_create() {
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
        $this->redirect(array('action' => 'wf_edit', $this->{$this->modelClass}->id));
    }
    
    /**
     * Manage post's comments
     * 
     */
    function wf_comments($id = null, $status = null) {
        $spam = ($status == 'spam') ? 1 : 0;
        $approved = ($status == 'unapproved') ? 0 : 1;
        if ($spam) {
            // Spam comments should show no matter of approval status
            $approved = array(0, 1);
        }
        
        $this->data = $this->{$this->modelClass}->find('first', array(
            'conditions' => array('WildPost.id' => $id),
            'contain' => array(
                'WildComment' => array(
                    'order' => 'WildComment.created DESC',
                    'conditions' => array(compact('spam', 'approved'))
                ),
                'WildUser'
            )
        ));
        
        $goBackAction = $this->referer(array('action' => 'edit', $this->data['WildPost']['id']));
        $this->set('goBackAction', $goBackAction);
    }
    
    /**
     * Posts overview
     * 
     */
    function wf_index() {
    	$posts = $this->paginate($this->modelClass);
        $this->set('posts', $posts);
    }

    /**
     * Edit a post
     * 
     * @param int $id
     */
    function wf_edit($id = null, $revisionNumber = null) {
        $this->WildPost->contain(array('WildUser', 'WildCategory'));
        $this->data = $this->WildPost->findById($id);
        //var_dump($this->data);
        
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
        
        $categoryId = isset($inCategories[0]) ? $inCategories[0] : null;
        
        $this->set(compact('isRevision', 'hasUser', 'isDraft', 'categories', 'inCategories', 'categoryId'));
        $this->pageTitle = $this->data[$this->modelClass]['title'];
    }
    
    function wf_categorize($id = null) {
        $this->WildPost->contain(array('WildUser', 'WildCategory'));
        $this->data = $this->WildPost->findById($id);
        
        if (empty($this->data)) return $this->cakeError('object_not_found');
   
        $categories = $this->WildPost->WildCategory->find('list', array('fields' => array('id', 'title')));
        $inCategories = Set::extract($this->data['WildCategory'], '{n}.id');
        $isDraft = ($this->data[$this->modelClass]['draft'] == 1) ? true : false;
        $categoriesForTree = $this->WildPost->WildCategory->find('all', array('order' => 'lft ASC', 'recursive' => -1));
        $this->set(compact('categories', 'inCategories', 'isDraft', 'categoriesForTree'));
        
        $this->pageTitle = $this->data[$this->modelClass]['title'];
    }
    
    function wf_options($id = null) {
        $this->WildPost->contain(array('WildUser', 'WildCategory'));
        $this->data = $this->WildPost->findById($id);
        
        if (empty($this->data)) return $this->cakeError('object_not_found');
   
        $isDraft = ($this->data[$this->modelClass]['draft'] == 1) ? true : false;
        $this->set(compact('isDraft'));
        
        $this->pageTitle = $this->data[$this->modelClass]['title'];
    }
    
    function wf_update() {
        //fb($this->data);
        $this->data[$this->modelClass]['wild_user_id'] = $this->getLoggedInUserId();

        // Publish?
        if (isset($this->data['__save']['publish'])) {
            $this->data[$this->modelClass]['draft'] = 0;
        }
        unset($this->data['__save']);
        
        if (isset($this->data[$this->modelClass]['slug'])) {
            $this->data[$this->modelClass]['slug'] = AppHelper::slug($this->data[$this->modelClass]['slug']);
        }
        
        $this->WildPost->create($this->data);
        
        if (!$this->WildPost->exists()) return $this->cakeError('object_not_found');
        
        if (isset($this->data[$this->modelClass]['categories_can_be_empty']) && !isset($this->data['WildCategory'])) {
             // Delete all post categories
             $this->WildPost->query("DELETE FROM categories_posts WHERE post_id = {$this->WildPost->id}");
        }

        if (!$this->WildPost->save()) return $this->cakeError('save_error'); // @TODO Rendering the exact save errors would be better

        // $cacheName = str_replace('-', '_', $this->data[$this->modelClass]['slug']); // @TODO check cache for proper naming method
        // clearCache($cacheName, 'views', '.php');
		
        if ($this->RequestHandler->isAjax()) {
            $this->WildPost->contain('WildUser');
            $this->data = $post = $this->WildPost->findById($this->WildPost->id); // @TODO clean up
            $this->set(compact('post'));
            return $this->render('wf_update');
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
    
    // /**
    //  * Display posts from a certain category
    //  *
    //  */
    // function category() {
    //  $slug = WildflowerHelper::slug($this->params['slug']);
    //  
    //  $this->WildPost->Category->Behaviors->attach('Containable');
    //     $this->WildPost->Category->contain("Post.id");
    //     $category = $this->WildPost->Category->findBySlug($slug);
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
        $posts = $this->WildPost->find('all', array(
             'order' => 'WildPost.created DESC',
             'contain' => 'WildUser',
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
            'order' => array('WildPost.created' => 'desc'),
            'conditions' => 'WildPost.draft = 0'
        );
        $posts = $this->paginate($this->modelClass);
        
        if (isset($this->params['requested'])) {
            return $posts;
        }
        
        $sidebarCategories = $this->WildPost->WildCategory->find('all', array(
            'order' => 'lft ASC', 
            'recursive' => -1, 
            'conditions' => array('parent_id' => Configure::read('App.blogCategoryId')),
        ));
        
        $this->set(compact('posts', 'sidebarCategories'));
    }
    
    /**
     * View posts from one category
     * 
     */
    function category() {
        //$this->cacheAction = true;
        
        $this->pageTitle = 'Blog';
        
        $this->WildPost->WildCategory->recursive = -1;
        $category = $this->WildPost->WildCategory->findBySlug($this->params['slug']);
        $posts = $this->WildPost->WildCategoriesWildPost->find('all', array(
            'conditions' => array(
                'wild_category_id' => $category['WildCategory']['id'],
            )
        ));
        $postsIds = Set::extract($posts, '{n}.WildCategoriesWildPost.wild_post_id');
        
        $this->paginate = array(
            'limit' => 10,
            'order' => array(
                'WildPost.created' => 'desc'
            ),
            'conditions' => array(
                'WildPost.draft' => 0,
                'WildPost.id' => $postsIds,
            ),
        );
        $posts = $this->paginate($this->modelClass);
        
        if (isset($this->params['requested'])) {
            return $posts;
        }
        
        $sidebarCategories = $this->WildPost->WildCategory->find('all', array(
            'order' => 'lft ASC', 
            'recursive' => -1, 
            'conditions' => array('parent_id' => Configure::read('App.blogCategoryId')),
        ));
        
        $this->set(compact('posts', 'sidebarCategories'));
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
        $this->WildPost->contain(array(
            'WildUser', 
            'WildCategory',
            'WildComment' => array(
                'conditions' => array('spam' => 0, 'approved' => 1),
            ),
        ));
        $post = $this->WildPost->findBySlugAndDraft($slug, 0);

		if (empty($post)) {
			return $this->do404();
		}
        
        // Post title
        $this->pageTitle = $post[$this->modelClass]['title'];
        
        if (isset($this->params['requested'])) {
            return $post;
        }
        
        $sidebarCategories = $this->WildPost->WildCategory->find('all', array(
            'order' => 'lft ASC', 
            'recursive' => -1, 
            'conditions' => array('parent_id' => Configure::read('App.blogCategoryId')),
        ));
        
        $this->set(array(
            'post' => $post,
            'descriptionMetaTag' => $post[$this->modelClass]['description_meta_tag'],
            'sidebarCategories' => $sidebarCategories
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
            $this->data['WildComment']['approved'] = 0;
        }
        
        $this->WildPost->WildComment->spamCheck = true;
        if ($this->WildPost->WildComment->save($this->data)) {
            $postId = intval($this->data['WildComment']['wild_post_id']);

            // Clear post cache
            // @TODO find out better method
            // $cacheName = str_replace('-', '_', $postSlug);
            // clearCache($cacheName, 'views', '.php');
            
            // Email alert
            // @TODO create a function in app_controller to be used in wild_messages too
            $this->Email->to = Configure::read('Wildflower.settings.contact_email');
    		$this->Email->from = $this->data['WildComment']['email'];
    		$this->Email->replyTo = $this->data['WildComment']['email'];
    		$this->Email->subject = Configure::read('Wildflower.settings.site_name') . ' - new comment from ' . $this->data['WildComment']['name'];
    		$this->Email->sendAs = 'text';
    		$this->Email->template = 'new_comment_notification';

    		$this->set($this->data['WildComment']);
    		$message = $this->data['WildComment']['content']; // @TODO remove Textile syntax - to plain text
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

            $this->redirect($this->data['WildPost']['permalink'] . '#comment-' . $this->WildPost->WildComment->id);
        }
    }
    
}
