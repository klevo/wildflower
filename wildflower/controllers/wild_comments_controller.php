<?php
class WildCommentsController extends AppController {
    public $helpers = array('Time', 'List');
    public $paginate = array(
        'limit' => 20,
        'order' => array(
            'WildComment.created' => 'desc'
        )
    );
    public $pageTitle = 'Comments';

    function wf_delete() {
        $this->WildComment->create($this->data);
        if (!$this->WildComment->exists()) {
            return;
        }
        $this->WildComment->delete();
    }
    
    function wf_edit($id = null) {
        if (!empty($this->data)) {
            $this->WildComment->create($this->data);
            if ($this->WildComment->save()) {
                return $this->redirect(array('action' => 'wf_edit', $this->WildComment->id));
            }
        }
        $this->WildComment->contain('WildPost.slug');
        $this->data = $this->WildComment->findById($id);
    }
    
    function wf_get_content($id) {
        $comment = $this->WildComment->findById($id, array('content'));
        $data = array('content' => $comment['WildComment']['content']);
        $this->set(compact('data'));
        $this->render('/elements/json');
    }

    function wf_index() {
        $comments = $this->paginate('WildComment', 'WildComment.spam = 0');
        $this->set('comments', $comments);
    }
    
    function wf_spam() {
        $this->WildComment->contain('WildPost.title', 'WildPost.id');
        $comments = $this->paginate('WildComment', 'WildComment.spam = 1');
        $this->set('comments', $comments);
    }
    
    function wf_mark_spam() {
        $this->WildComment->create($this->data);
        if (!$this->WildComment->exists()) {
            return;
        }
        var_dump($this->WildComment->spam());
        exit();
    }
    
    function wf_mass_edit() {
    	$this->paginate['conditions'] = 'WildComment.spam = 0';
    	$this->paginate['limit'] = 30;
        $this->WildComment->recursive = -1;
        $comments = $this->paginate('WildComment');
        $this->set('comments', $comments);
    }
    
    function wf_not_spam() {
        $this->WildComment->create($this->data);
        if (!$this->WildComment->exists()) {
            return;
        }
        $this->WildComment->unspam();
    }
    
    /**
     * @deprecated 
     *
     * @param unknown_type $id
     */
    function wf_not_spam_confirmation($id = null) {
        $this->WildComment->contain();
        $this->data = $this->WildComment->findById($id);
    }
    
    /**
     * AJAX only comment update
     *
     */
    function wf_update() {
        $this->WildComment->create($this->data);
        if (!$this->WildComment->exists()) {
            return;
        }
        
        $comment = $this->WildComment->save();
        $this->set(compact('comment'));
    }

    /**
     * Post a new comment
     *
     */
    function create() {
        $this->WildComment->spamCheck = true;
        if ($this->WildComment->save($this->data)) {
            $this->Session->setFlash('Comment succesfuly added.');
            $postId = intval($this->data['WildComment']['post_id']);
            $postSlug = $this->WildComment->Post->field('slug', "WildPost.id = $postId");
            $postLink = '/' . Configure::read('Wildflower.blogIndex') . "/$postSlug";
			
			// Clear post cache
			$cacheName = str_replace('-', '_', $postSlug);
			clearCache($cacheName, 'views', '.php');

            $this->redirect($postLink);
        } else {
            $post = $this->WildComment->Post->findById(intval($this->data['WildComment']['post_id']));
            $this->set('post', $post);
            $this->render('/posts/view');
        }
    }

}
