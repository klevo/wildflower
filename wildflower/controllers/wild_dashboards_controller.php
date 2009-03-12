<?php
class WildDashboardsController extends AppController {
	
	public $helpers = array('Wildflower.List', 'Time', 'Text');
	public $uses = array('Wildflower.WildComment', 'Wildflower.WildMessage', 'WildFlower.WildPage');
	public $pageTitle = 'Dashboard';
	
	function wf_index() {
        // $comments = $this->WildComment->find('all', array('limit' => 5, 'conditions' => 'spam = 0'));
        // $messages = $this->WildMessage->find('all', array('limit' => 5));
        $pages = $this->WildPage->find('all', array('limit' => 10, 'order' => 'WildPage.updated DESC'));
		$this->set(compact('pages'));
	}
	
    /**
     * Admin page and post search
     *
     * @param string $query Search term, encoded by Javascript's encodeURI()
     */
    function wf_search($query = '') {
        $query = urldecode($query);
        $postResults = ClassRegistry::init('WildPost')->search($query);
        $pageResults = ClassRegistry::init('WildPage')->search($query);
        $results = am($postResults, $pageResults);
        $this->set('results', $results);
    }
    
    /**
     * Public search @TODO
     *
     */
    function search() {
        if (!empty($this->data)) {
            $query = '';
            if (isset($this->data['Search']['query'])) {
                $query = $this->data['Search']['query'];
            } else if (isset($this->data['Dashboard']['query'])) {
                $query = $this->data['Dashboard']['query'];
            } else {
                return;
            }
            
            $postResults = $this->Post->search($query);
	        $pageResults = $this->Page->search($query);
	        if (!is_array($postResults)) {
	        	$postResults = array();
	        }
	        if (!is_array($pageResults)) {
	        	$pageResults = array();
	        }
	        $results = array_merge($postResults, $pageResults);
            $this->set('results', $results);

            if ($this->RequestHandler->isAjax()) {
                $this->render('/elements/search_results');
            }
        }
    }
	
}
