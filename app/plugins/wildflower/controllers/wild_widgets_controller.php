<?php
class WildWidgetsController extends WildflowerAppController {
    
    function wf_insert_widget() {
        
    }
    
    function slider_config($id) {
        if (!empty($this->data)) {
            $config = json_encode($this->data);
            $this->WildWidget->id = intval($id);
            $this->WildWidget->saveField('config', $config);
        }
        
        $widget = $this->WildWidget->findById($id);
        $this->data = json_decode($widget['WildWidget']['config'], true);
    }
    
}