<?php
class WildMessagesController extends AppController {

	public $components = array('Email', 'RequestHandler');
	public $helpers = array('Text', 'Time', 'List');
	public $uses = array('WildMessage', 'WildPage');
	public $paginate = array(
        'order' => 'created DESC',
        'limit' => 25,
        'conditions' => array(
            'spam' => 0
        )
	);
	
	function wf_index() {
	    $this->pageTitle = 'Messages from contact form';
	    $messages = $this->paginate('WildMessage');
	    $this->set(compact('messages'));
	}
	
	function wf_view($id = null) {
	    $message = $this->WildMessage->findById($id);
	    $this->pageTitle = $message['WildMessage']['subject'];
	    $this->set(compact('message'));
	}

    function index() {
        if (!empty($this->data)) {
            $this->WildMessage->spamCheck = true;
            if ($this->WildMessage->save($this->data)) {
                // Send email to site owner
        		$this->Email->to = Configure::read('Wildflower.settings.contact_email');
        		$this->Email->from = $this->data[$this->modelClass]['email'];
        		$this->Email->replyTo = $this->data[$this->modelClass]['email'];
        		$this->Email->subject = Configure::read('Wildflower.settings.site_name') . ' contact form';
        		$this->Email->sendAs = 'text';
        		$this->Email->template = 'contact_form';

        		$this->set('message', $this->data[$this->modelClass]['content']);
        		$this->set('phone', isset($this->data[$this->modelClass]['phone']) ? $this->data[$this->modelClass]['phone'] : '');

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

        		if ($this->Email->send()) {
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
    
    function populateContactPage() {
    	$page = $this->WildPage->findBySlug('contact');
        $this->set('page', $page);
        // @TODO: Unify parameters
        $this->params['Wildflower']['view']['isPage'] = true;
        $this->params['Wildflower']['page']['slug'] = $page[$this->WildPage->name]['slug'];
        $this->params['current']['slug'] = $page[$this->WildPage->name]['slug'];
        $this->pageTitle = 'Contact';
        $this->params['breadcrumb'][] = array('title' => 'Contact');
    }
    
}
