<?php
class Comment extends AppModel {

    public $actsAs = array('Containable');
	public $belongsTo = array('Post');
	/** @var bool Do a spam check before save? **/
	public $spamCheck = false;
	public $validate = array(
		'name' => VALID_NOT_EMPTY,
		'email' => array('rule' => 'email', 'message' => 'Please enter a valid email address'),
		'url' => array('rule' => 'url', 'message' => 'Please enter a valid URL', 'allowEmpty' => true),
		'content' => VALID_NOT_EMPTY
	);
	
	function beforeSave() {
	    if ($this->spamCheck) {
	        // Reset spamCheck for another save
            $this->spamCheck = false;
            
	        if ($this->isSpam($this->data)) {
	            $this->data[$this->name]['spam'] = 1;
	        }
	    }
	    
	    if (!isset($this->data[$this->name]['spam'])) {
            $this->data[$this->name]['spam'] = 0;
	    }
	    
	    return true;
	}

    function beforeValidate() {
        // Some tiny name and content fields sanitization
        if (isset($this->data[$this->name]['name'])) {
            $this->data[$this->name]['name'] = trim(strip_tags($this->data[$this->name]['name']));
        }
        if (isset($this->data[$this->name]['content'])) {
            $this->data[$this->name]['content'] = trim($this->data[$this->name]['content']);
        }
        
    	// Generate full url with http:// prefix
    	if (isset($this->data[$this->name]['url']) && !empty($this->data[$this->name]['url'])) {
    		$this->data[$this->name]['url'] = trim($this->data[$this->name]['url']);
    		$httpPrefix = 'http://';
    		if (strpos($this->data[$this->name]['url'], $httpPrefix) !== 0) {
    			$this->data[$this->name]['url'] = $httpPrefix . $this->data[$this->name]['url'];
    		}
    	}
    }

    /**
     * Use Akismet to check comment data for spam
     *
     * @param array $data
     * @return array Data with spam field set
     */
    function isSpam(&$data) {
        $apiKey = Configure::read('AppSettings.wordpress_api_key');
        if (empty($apiKey)) {
            return false;
        }
        
        try {
            App::import('Vendor', 'akismet');
            $siteUrl = 'http://' . getenv('SERVER_NAME');
            $akismet = new Akismet($siteUrl, $apiKey);
            $akismet->setCommentAuthor($data[$this->name]['name']);
            $akismet->setCommentAuthorEmail($data[$this->name]['email']);
            $akismet->setCommentAuthorURL($data[$this->name]['url']);
            $akismet->setCommentContent($data[$this->name]['content']);
            $akismet->setPermalink($data['Post']['permalink']);
            
            if ($akismet->isCommentSpam()) {
                return true;
            }
        } catch(Exception $e) {
            $this->log('Akismet not reachable!');
        }
        
        return false;
    }
    
    /**
     * Mark current comment as spam
     *
     */
    function spam() {
        // @TODO add Akismet interaction
        return $this->saveField('spam', 1);
    }
    
    /**
     * Mark current comment as not spam
     *
     */
    function unspam() {
        // @TODO add Akismet interaction
        return $this->saveField('spam', 0);
    }

}