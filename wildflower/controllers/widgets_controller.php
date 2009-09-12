<?php
class WidgetsController extends AppController {
    
    public $signatures = array(
        array('html' => '<div id="slider" class="admin_widget"></div>', 'action' => 'slider'),
    );
    
    // Return an ID of the newly created instance
    function admin_insert_widget() {
        $data['Widget'] = '';
        $data = array('config' => json_encode($data));
        $this->Widget->create($data);
        $this->Widget->save();
        $this->set('data', array('id' => $this->Widget->id));
        $this->render('/elements/json');
    }
    
    function admin_list_widgets() {
        // Scan plugin and theme element dirs for widgets
        $widgetsPath = APP . 'views' . DS . 'elements' . DS . 'widgets';
        if (isset($this->theme) and is_dir(APP . 'views' . DS . 'themed' . DS . $this->theme . DS . 'elements' . DS . 'widgets')) {
            $widgetsPath = APP . 'views' . DS . 'themed' . DS . $this->theme . DS . 'elements' . DS . 'widgets';
        }
		$widgets = $this->_parseWidgetFileList(scandir($widgetsPath));
		$this->set(compact('widgets'));
    }
    
    private function _parseWidgetFileList($list) {
        $result = array();
        foreach ($list as $fileName) {
		    if ($fileName[0] == '.' or strpos($fileName, '.ctp') < 1 or strpos($fileName, '_config') !== false) {
		        continue;
		    }
		    
	        $fileName = r('.ctp', '', $fileName);
	        $humanized = Inflector::humanize(r('admin_widget_', '', $fileName));
	        $result[] = array(
	            'id' => $fileName,
	            'name' => $humanized,
	            'href' => r(' ', '', $humanized)
	        );
		}
		return $result;
    }
    
    function admin_config($name, $id) {
        $widget = $this->Widget->findById($id);
        $this->data = json_decode($widget['Widget']['config'], true);
        $this->data['Widget']['id'] = intval($id);
        $this->render("/elements/widgets/{$name}_config");
    }
    
    function admin_update() {
        if (!empty($this->data)) {
            $config = json_encode($this->data);
            $this->Widget->id = intval($this->data['Widget']['id']);
            $this->Widget->saveField('config', $config);
        }
    }
    
}