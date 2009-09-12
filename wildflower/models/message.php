<?php
class Message extends AppModel {

	public $validate = array(
		'name' => array(
			'rule' => array('between', 5, 100),
			'required' => true,
			'message' => 'Name must be between 5 to 50 alphanumeric characters long'
		),
		'email' => array(
			'rule' => 'email',
			'required' => true,
			'message' => 'Please enter a valid email address'
		),
		'content' => VALID_NOT_EMPTY
	);
	/** @var bool Do a spam check before each save? **/
	public $spamCheck = false;
	
	function beforeSave() {
	    parent::beforeSave();
	    
	    $this->data[$this->name]['spam'] = 0;

	    if ($this->spamCheck) {
	        // Reset spamCheck for another save
            $this->spamCheck = false;
            
	        if ($this->isSpam($this->data)) {
	            $this->data[$this->name]['spam'] = 1;
	        }
	    }
	    
	    return true;
	}
	
	/**
     * Use Akismet to check the message data for spam
     *
     * @param array $data
     * @return bool
     */
    function isSpam(&$data) {
        $apiKey = Configure::read('Wildflower.settings.wordpress_api_key');
        if (empty($apiKey)) {
            return false;
        }
        
        try {
            App::import('Vendor', 'akismet');
            $siteUrl = Configure::read('Wildflower.fullSiteUrl');
            $akismet = new Akismet($siteUrl, $apiKey);
            $akismet->setCommentAuthor($data[$this->name]['name']);
            $akismet->setCommentAuthorEmail($data[$this->name]['email']);
            $akismet->setCommentAuthorURL('');
            $akismet->setCommentContent($data[$this->name]['content']);
            $akismet->setPermalink(Configure::read('Wildflower.fullCurrentUrl'));
            
            if ($akismet->isCommentSpam()) {
                return true;
            }
        } catch(Exception $e) {
            trigger_error('Akismet not reachable: ' . $e);
        }
        
        return false;
    }
	
}
