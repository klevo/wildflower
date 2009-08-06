<?php
class CommentsController extends AppController {
    public $helpers = array('Time', 'List');
    public $paginate = array(
        'limit' => 20,
        'order' => array(
            'Comment.created' => 'desc'
        )
    );
    public $pageTitle = 'Comments';

    function admin_delete() {
        $this->Comment->create($this->data);
        if (!$this->Comment->exists()) {
            return;
        }
        $this->Comment->delete();
    }
    
    function admin_edit($id = null) {
        if (!empty($this->data)) {
            $this->Comment->create($this->data);
            if ($this->Comment->save()) {
                return $this->redirect(array('action' => 'admin_edit', $this->Comment->id));
            }
        }
        $this->Comment->contain('Post.slug');
        $this->data = $this->Comment->findById($id);
    }
    
    function admin_get_content($id) {
        $comment = $this->Comment->findById($id, array('content'));
        $data = array('content' => $comment['Comment']['content']);
        $this->set(compact('data'));
        $this->render('/elements/json');
    }

    function admin_index() {
        $comments = $this->paginate('Comment', 'Comment.spam = 0');
        $this->set('comments', $comments);
    }
    
    function admin_spam() {
        $this->Comment->contain('Post.title', 'Post.id');
        $comments = $this->paginate('Comment', 'Comment.spam = 1');
        $this->set('comments', $comments);
    }
    
    function admin_mark_spam() {
        $this->Comment->create($this->data);
        if (!$this->Comment->exists()) {
            return;
        }
        var_dump($this->Comment->spam());
        exit();
    }
    
    function admin_mass_edit() {
    	$this->paginate['conditions'] = 'Comment.spam = 0';
    	$this->paginate['limit'] = 30;
        $this->Comment->recursive = -1;
        $comments = $this->paginate('Comment');
        $this->set('comments', $comments);
    }
    
    function admin_not_spam() {
        $this->Comment->create($this->data);
        if (!$this->Comment->exists()) {
            return;
        }
        $this->Comment->unspam();
    }
    
    /**
     * @deprecated 
     *
     * @param unknown_type $id
     */
    function admin_not_spam_confirmation($id = null) {
        $this->Comment->contain();
        $this->data = $this->Comment->findById($id);
    }
    
    /**
     * AJAX only comment update
     *
     */
    function admin_update() {
        $this->Comment->create($this->data);
        if (!$this->Comment->exists()) {
            return;
        }
        
        $comment = $this->Comment->save();
        $this->set(compact('comment'));
    }

    /**
     * Post a new comment
     *
     */
    function create() {
        $this->Comment->spamCheck = true;
        if ($this->Comment->save($this->data)) {
            $this->Session->setFlash('Comment succesfuly added.');
            $postId = intval($this->data['Comment']['post_id']);
            $postSlug = $this->Comment->Post->field('slug', "Post.id = $postId");
            $postLink = '/' . Configure::read('Wildflower.blogIndex') . "/$postSlug";
			
			// Clear post cache
			$cacheName = str_replace('-', '_', $postSlug);
			clearCache($cacheName, 'views', '.php');

            $this->redirect($postLink);
        } else {
            $post = $this->Comment->Post->findById(intval($this->data['Comment']['post_id']));
            $this->set('post', $post);
            $this->render('/posts/view');
        }
    }

}
