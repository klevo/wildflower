<?php
class SettingsController extends AppController {

	public $uses = array('Page', 'Setting');
	
	function beforeFilter() {
	    parent::beforeFilter();
	    $this->modelClass = $this->Setting->name;
	}
	
	function admin_add() {
		if (!empty($this->data)) {
			$this->data[$this->modelClass]['name'] = trim($this->data[$this->modelClass]['name']);
			if ($this->Setting->save($this->data)) {
				// Regenerate settings cache
				$this->Setting->createCache();
				
				$this->Session->setFlash('Setting added.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	/**
	 * Site Settings
	 * 
	 */
	function admin_index() {
	    $this->pageTitle = 'Site settings';
	    
	    $homePageIdOptions = $this->Page->getListThreaded();
	    
	    $settings = $this->Setting->find('all', array('order' => 'order ASC'));
	    
	    $this->set(compact('settings', 'homePageIdOptions'));
	}

	/**
	 * @TODO: Add some validation
	 *
	 */
	function admin_update() {
	    // If cache has been turned off clear it
	    $cacheSetting = $this->Setting->findByName('cache');
	    if ($cacheSetting[$this->modelClass]['value'] == 'on' and $this->data[$this->modelClass][$cacheSetting[$this->modelClass]['id']] == 'off') {
	        $this->clearViewCache();
	    }
	    
	    // Save settings
	    foreach ($this->data[$this->modelClass] as $id => $value) {
	        $this->Setting->create(array('id' => $id, 'value' => $value));
	        $this->Setting->save();
	    }
	    
        $this->Session->setFlash('Setings updated.');
        $this->redirect(array('action' => 'index'));
	}
	
	function clearViewCache() {
	    $path = CACHE . 'views';
	    $files = scandir($path);
	    foreach ($files as $file) {
	        if (substr($file, -4) === '.php') {
	            unlink($path . DS . $file);
	        }
	    }
	}

}
