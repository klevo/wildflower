<?php
class WildDashboardsController extends WildflowerAppController {
	
	public $helpers = array('Wildflower.List', 'Time', 'Text');
	public $uses = array('Wildflower.WildComment', 'Wildflower.WildMessage');
	public $pageTitle = 'Dashboard';
	
	function wf_index() {
		$comments = $this->WildComment->find('all', array('limit' => 5, 'conditions' => 'spam = 0'));
		$messages = $this->WildMessage->find('all', array('limit' => 5));
		$this->set(compact('comments', 'messages'));
	}
	
    function wf_search($query = '') {
        $query = urldecode($query);
        $postResults = ClassRegistry::init('WildPost')->search($query);
        $pageResults = ClassRegistry::init('WildPage')->search($query);
        $results = am($postResults, $pageResults);
        $this->set('results', $results);
    }
    
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
