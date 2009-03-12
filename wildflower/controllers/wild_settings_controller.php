<?php
class WildSettingsController extends AppController {

	public $uses = array('Wildflower.WildPage', 'Wildflower.WildSetting');
	
	function beforeFilter() {
	    parent::beforeFilter();
	    $this->modelClass = $this->WildSetting->name;
	}
	
	function wf_add() {
		if (!empty($this->data)) {
			$this->data[$this->modelClass]['name'] = trim($this->data[$this->modelClass]['name']);
			if ($this->WildSetting->save($this->data)) {
				// Regenerate settings cache
				$this->WildSetting->createCache();
				
				$this->Session->setFlash('Setting added.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	/**
	 * Site Settings
	 * 
	 */
	function wf_index() {
	    $this->pageTitle = 'Site settings';
	    
	    $homePageIdOptions = $this->WildPage->getListThreaded();
	    
	    $settings = $this->WildSetting->find('all', array('order' => 'order ASC'));
	    
	    $this->set(compact('settings', 'homePageIdOptions'));
	}

	/**
	 * @TODO: Add some validation
	 *
	 */
	function wf_update() {
	    // If cache has been turned off clear it
	    $cacheSetting = $this->WildSetting->findByName('cache');
	    if ($cacheSetting[$this->modelClass]['value'] == 'on' and $this->data[$this->modelClass][$cacheSetting[$this->modelClass]['id']] == 'off') {
	        $this->clearViewCache();
	    }
	    
	    // Save settings
	    foreach ($this->data[$this->modelClass] as $id => $value) {
	        $this->WildSetting->create(array('id' => $id, 'value' => $value));
	        $this->WildSetting->save();
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
