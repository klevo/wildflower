<?php
class AssetsController extends AppController {
	
	public $components = array('JlmPackager');
	public $autoRender = false;
    
    function admin_jlm() {
        $this->JlmPackager->output();
    }
    
}