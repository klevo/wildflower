<?php
class PureHtmlBehavior extends ModelBehavior {
	
	function setup(&$model, $settings = array()) {
        $default = array('field' => 'content', 'doctype' => 'XHTML 1.0 Strict');
        
        if (!isset($this->settings[$model->name])) {
            $this->settings[$model->name] = $default;
        }
        
        $this->settings[$model->name] = array_merge($this->settings[$model->name], ife(is_array($settings), $settings, array()));
    }
    
 	function beforeSave(&$model) {
 		$field = $this->settings[$model->name]['field'];
 		if ($model->hasField($field)) {
 			$model->data[$model->name][$field] = $this->purify($model->data[$model->name][$field]);
 		}
 	}
 	
 	private function purify($html) {
 		App::import('Vendor', 'htmlpurifier');
 		
 		$config = HTMLPurifier_Config::createDefault();
	    $config->set('HTML', 'Doctype', $this->settings[$model->name]['doctype']);
	    $purifier = new HTMLPurifier($config);
	    
	    return $purifier->purify($html);
 	}
 	
}
