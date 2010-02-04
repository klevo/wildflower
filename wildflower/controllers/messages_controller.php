<?php
class MessagesController extends AppController {

	public $components = array('Email', 'RequestHandler');
	public $helpers = array('Text', 'Time', 'List');
	public $uses = array('Message', 'Page');
	public $paginate = array(
        'order' => 'created DESC',
        'limit' => 25,
        'conditions' => array(
            'spam' => 0
        )
	);
	
    function admin_index() {
        $this->pageTitle = 'Contact form messages';
        $messages = $this->paginate('Message');
        $this->set(compact('messages'));
    }   
    
    function admin_spam() {
        $this->pageTitle = 'Spam contact form messages';
        $messages = $this->paginate('Message', 'spam = 1');
        $this->set(compact('messages'));
        $this->render('admin_index');
    }
	
	function admin_view($id = null) {
	    $message = $this->Message->findById($id);
	    $this->pageTitle = $message['Message']['subject'];
	    $this->set(compact('message'));
	}

    function index() {
        if (!empty($this->data)) {
            $this->Message->spamCheck = true;
            if ($message = $this->Message->save($this->data)) {
                if ($message['Message']['spam'] == 1) {
                    return $this->redirect('/contact');
                }
                
                // Send email to site owner
        		$subject = Configure::read('Wildflower.settings.site_name') . ' contact form';
                if (isset($this->data['Message']['idea'])) {
                    $subject = Configure::read('Wildflower.settings.site_name') . ' IDEAS form';
        		}
			
        		$this->set('message', $this->data[$this->modelClass]['content']);
        		$this->set('phone', isset($this->data[$this->modelClass]['phone']) ? $this->data[$this->modelClass]['phone'] : '');

			$sent = $this->_sendEmail('contact_form',
					  $subject,
					  array('to' => Configure::read('Wildflower.settings.contact_email')),
					  $this->data[$this->modelClass]['email'],
					  'text');


        		if ($sent) {
        			$message = 'Your message has been succesfuly sent. Thanks!';
        		} else {
        			$message = "A problem occured. Your message has not been send. Sorry!";
        		}

        		if ($this->RequestHandler->isAjax()) {
        			$this->set('message', $message);
        			return $this->render('message');
        		} else {
        			$this->Session->setFlash($message);
        			return $this->redirect('/contact');
        		}
        	}
        }
        
        $this->populateContactPage();
        $this->render('contact');
    }
    
    function admin_recheck_inbox_for_spam() {
        $msgs = $this->Message->find('all');
        $movedToSpam = 0;
        foreach ($msgs as $msg) {
            if ($this->Message->isSpam($msg)) {
                $this->Message->create($msg);
                $this->Message->saveField('spam', 1);
                $movedToSpam++;
            }
        }
        $messagesWord = ($movedToSpam == 1) ? 'message' : 'messages';
        $this->Session->setFlash("Moved $movedToSpam $messagesWord to spam.");
        $this->redirect(array('action' => 'index'));
    }
    
    function populateContactPage() {
    	$page = $this->Page->findBySlug('contact');
        $this->set('page', $page);
        // @TODO: Unify parameters
        $this->params['Wildflower']['view']['isPage'] = true;
        $this->params['Wildflower']['page']['slug'] = $page[$this->Page->name]['slug'];
        $this->params['current']['slug'] = $page[$this->Page->name]['slug'];
        $this->pageTitle = 'Contact';
        $this->params['breadcrumb'][] = array('title' => 'Contact');
    }
    
}
