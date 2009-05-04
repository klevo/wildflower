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
        $this->pageTitle = 'Contact form messages';
        $messages = $this->paginate('WildMessage');
        $this->set(compact('messages'));
    }   
    
    function wf_spam() {
        $this->pageTitle = 'Spam contact form messages';
        $messages = $this->paginate('WildMessage', 'spam = 1');
        $this->set(compact('messages'));
        $this->render('wf_index');
    }
	
	function wf_view($id = null) {
	    $message = $this->WildMessage->findById($id);
	    $this->pageTitle = $message['WildMessage']['subject'];
	    $this->set(compact('message'));
	}

    function index() {
        if (!empty($this->data)) {
            $this->WildMessage->spamCheck = true;
            if ($message = $this->WildMessage->save($this->data)) {
                if ($message['WildMessage']['spam'] == 1) {
                    return $this->redirect('/contact');
                }
                
                // Send email to site owner
        		$this->Email->to = Configure::read('Wildflower.settings.contact_email');
        		$this->Email->from = $this->data[$this->modelClass]['email'];
        		$this->Email->replyTo = $this->data[$this->modelClass]['email'];
        		$this->Email->subject = Configure::read('Wildflower.settings.site_name') . ' contact form';
                if (isset($this->data['WildMessage']['idea'])) {
                    $this->Email->subject = Configure::read('Wildflower.settings.site_name') . ' IDEAS form';
        		}
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
    
    function wf_recheck_inbox_for_spam() {
        $msgs = $this->WildMessage->find('all');
        $movedToSpam = 0;
        foreach ($msgs as $msg) {
            if ($this->WildMessage->isSpam($msg)) {
                $this->WildMessage->create($msg);
                $this->WildMessage->saveField('spam', 1);
                $movedToSpam++;
            }
        }
        $messagesWord = ($movedToSpam == 1) ? 'message' : 'messages';
        $this->Session->setFlash("Moved $movedToSpam $messagesWord to spam.");
        $this->redirect(array('action' => 'index'));
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
