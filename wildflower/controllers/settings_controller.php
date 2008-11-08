<?php
class SettingsController extends AppController {

	public $helpers = array('Html', 'Form');
	public $uses = array('Page', 'Setting');
	
	function admin_add() {
		if (!empty($this->data)) {
			$this->data['Setting']['name'] = trim($this->data['Setting']['name']);
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
	    $homePageIdOptions = $this->Page->getSelectBoxData();
	    $homePageIdOptions[0] = '(Custom)';
	    
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
	    if ($cacheSetting['Setting']['value'] == 'on' and $this->data['Setting'][$cacheSetting['Setting']['id']] == 'off') {
	        $this->clearViewCache();
	    }
	    
	    // Save settings
	    foreach ($this->data['Setting'] as $id => $value) {
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
