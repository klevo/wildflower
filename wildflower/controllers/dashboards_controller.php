<?php
class DashboardsController extends AppController {
	
	public $helpers = array('List', 'Time', 'Text');
	public $pageTitle = 'Dashboard';
	public $uses = array('Dashboard','Post', 'Page');
	
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
        if ($this->RequestHandler->isAjax()) {
            $this->render('../dashboards/wf_search');
        }
    }
    
    /**
     * Public search @TODO
     *
     */
    function search() {
		$query = Sanitize::escape($_GET['q']);
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
