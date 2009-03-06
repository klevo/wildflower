<?php
class WildWidgetsController extends WildflowerAppController {
    
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
        
    }
    
    function wf_slider_config($id) {
        $widget = $this->WildWidget->findById($id);
        $this->data = json_decode($widget['WildWidget']['config'], true);
        $this->data['WildWidget']['id'] = intval($id);
    }
    
    function wf_update() {
        if (!empty($this->data)) {
            $config = json_encode($this->data);
            $this->WildWidget->id = intval($this->data['WildWidget']['id']);
            $this->WildWidget->saveField('config', $config);
        }
    }
    
}