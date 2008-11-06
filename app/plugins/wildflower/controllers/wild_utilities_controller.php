<?php
class WildUtilitiesController extends WildflowerAppController {
    
    public $components = array('Security');
    public $uses = array('Wildflower.WildPost', 'Wildflower.WildUtility');
    
    function beforeFilter() {
        parent::beforeFilter();
        
        // This stuff is only avaible in debug modes
        if (Configure::read('debug') < 1) {
            return $this->do404();
        }
        
        $this->Security->requireAuth('wf_index');
        $this->pageTitle = 'Developer Utilities';
    }
        
    function wf_index() {
        if (!empty($this->data)) {
            $addWhat = '_massAdd' . ucwords($this->data['WildUtility']['what']);
            $howMany = intval($this->data['WildUtility']['how_many']);
            $this->{$addWhat}($howMany);
        }
    }
    
    function _massAddPosts($howMany = 10) {
        App::import('Vendor', 'Randomizer', array('file' => 'randomizer.php'));
        $randomizer = new Randomizer;
        for ($i = 0; $i < $howMany; $i++) {
            $post = array(
                'title' => $randomizer->title(),
                'content' => $randomizer->text(),
                'user_id' => $this->getLoggedInUserId(),
                'parent_id' => $randomizer->parentId($this->WildPost->id),
            );
            $this->WildPost->create($post);
            $this->WildPost->save();
        }
    }
    
}