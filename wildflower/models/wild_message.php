<?php
class WildMessage extends AppModel {

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
            $siteUrl = FULL_BASE_URL . $this->base;
            $akismet = new Akismet($siteUrl, $apiKey);
            $akismet->setCommentAuthor($data[$this->name]['name']);
            $akismet->setCommentAuthorEmail($data[$this->name]['email']);
            $akismet->setCommentAuthorURL($data[$this->name]['url']);
            $akismet->setCommentContent($data[$this->name]['content']);
            $akismet->setPermalink($data['WildPost']['permalink']);
            
            if ($akismet->isCommentSpam()) {
                return true;
            }
        } catch(Exception $e) {
            trigger_error('Akismet not reachable: ' . $e->message);
        }
        
        return false;
    }
	
}
