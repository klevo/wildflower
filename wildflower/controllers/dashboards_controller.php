<?php
class DashboardsController extends AppController {
	
	public $helpers = array('List', 'Time', 'Text');
	public $pageTitle = 'Dashboard';
	
	function admin_index() {
        $items = $this->Dashboard->findRecentHappening();
        $users = ClassRegistry::init('User')->find('all', array('order' => 'last_login ASC'));
		$this->set(compact('items', 'users'));
	}
	
    /**
     * Admin page and post search
     *
     * @param string $query Search term, encoded by Javascript's encodeURI()
     */
    function admin_search($query = '') {
        $query = urldecode($query);
        $postResults = ClassRegistry::init('Post')->search($query);
        $pageResults = ClassRegistry::init('Page')->search($query);
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
