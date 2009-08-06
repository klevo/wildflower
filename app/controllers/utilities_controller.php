<?php
class WildUtilitiesController extends AppController {
    
    public $components = array('Security');
    public $uses = array('Wildflower.Post', 'Wildflower.Utility');
    
    function beforeFilter() {
        parent::beforeFilter();
        
        // This stuff is only avaible in debug modes
        if (Configure::read('debug') < 1) {
            return $this->do404();
        }
        
        $this->Security->requireAuth('admin_index');
        $this->pageTitle = 'Developer Utilities';
    }
        
    function admin_index() {
        if (!empty($this->data)) {
            $addWhat = '_massAdd' . ucwords($this->data['Utility']['what']);
            $howMany = intval($this->data['Utility']['how_many']);
            $this->{$addWhat}($howMany);
        }
    }
    
    function _massAddPosts($howMany = 10) {
        @set_time_limit(60 * 60);
        App::import('Vendor', 'Randomizer', array('file' => 'randomizer.php'));
        $randomizer = new Randomizer;
        for ($i = 0; $i < $howMany; $i++) {
            $post = array(
                'title' => $randomizer->title(),
                'content' => $randomizer->text(),
                'user_id' => $this->getLoggedInUserId(),
                'parent_id' => $randomizer->parentId($this->Post->id),
            );
            $this->Post->create($post);
            $this->Post->save();
        }
    }
    
}