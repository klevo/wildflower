<?php
class WildWidgetsController extends AppController {
    
    public $signatures = array(
        array('html' => '<div id="slider" class="wf_widget"></div>', 'action' => 'slider'),
    );
    
    // Return an ID of the newly created instance
    function wf_insert_widget() {
        $data['WildWidget'] = '';
        $data = array('config' => json_encode($data));
        $this->WildWidget->create($data);
        $this->WildWidget->save();
        $this->set('data', array('id' => $this->WildWidget->id));
        $this->render('/elements/json');
    }
    
    function wf_list_widgets() {
        // Scan plugin and theme element dirs for widgets
        $widgetsPath = APP . 'views' . DS . 'elements' . DS . 'widgets';
        if (isset($this->theme) and is_dir(APP . 'views' . DS . 'themed' . DS . $this->theme . DS . 'elements' . DS . 'widgets')) {
            $widgetsPath = APP . 'views' . DS . 'themed' . DS . $this->theme . DS . 'elements' . DS . 'widgets';
        }
        $themeWidgets = $this->_parseWidgetFileList(scandir($widgetsPath));
        $wfWidgets = $this->_parseWidgetFileList(scandir(WILDFLOWER_DIR . DS . 'views' . DS . 'elements' . DS . 'widgets'));
		$widgets = am($wfWidgets, $themeWidgets);
		$this->set(compact('widgets'));
    }
    
    private function _parseWidgetFileList($list) {
        $result = array();
        foreach ($list as $fileName) {
		    if ($fileName[0] == '.' or strpos($fileName, '.ctp') < 1 or strpos($fileName, '_config') !== false) {
		        continue;
		    }
		    
	        $fileName = r('.ctp', '', $fileName);
	        $humanized = Inflector::humanize(r('wf_widget_', '', $fileName));
	        $result[] = array(
	            'id' => $fileName,
	            'name' => $humanized,
	            'href' => r(' ', '', $humanized)
	        );
		}
		return $result;
    }
    
    function wf_config($name, $id) {
        $widget = $this->WildWidget->findById($id);
        $this->data = json_decode($widget['WildWidget']['config'], true);
        $this->data['WildWidget']['id'] = intval($id);
        $this->render("/elements/widgets/{$name}_config");
    }
    
    function wf_update() {
        if (!empty($this->data)) {
            $config = json_encode($this->data);
            $this->WildWidget->id = intval($this->data['WildWidget']['id']);
            $this->WildWidget->saveField('config', $config);
        }
    }
    
}