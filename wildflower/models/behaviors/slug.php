<?php 
/**
 * Slug behavior to a model
 * 
 * @author Mariano Iglesias, Róbert Starší
 * @package wildflower
 * @subpackage  models.behaviors
 */
class SlugBehavior extends ModelBehavior {
    /**
     * Initiate behaviour for the model using specified settings.
     *
     * @param object $model Model using the behaviour
     * @param array $settings Settings to override for model
     */
    function setup(&$model, $settings = array()) {
        $default = array( 'label' => array('title'), 'slug' => 'slug', 'separator' => '-', 'length' => 100, 'overwrite' => false );
        
        if (!isset($this->settings[$model->name])) {
            $this->settings[$model->name] = $default;
        }
        
        $this->settings[$model->name] = array_merge($this->settings[$model->name], ife(is_array($settings), $settings, array()));
    }
    
    /**
     * Run before a model is saved, used to set up slug for model.
     *
     * @param object $model Model about to be saved.
     *
     * @access public
     * @since 1.0
     */
    function beforeSave(&$model) {
        if (!is_array($this->settings[$model->name]['label'])) {
            $this->settings[$model->name]['label'] = array( $this->settings[$model->name]['label'] );
        }
        
        foreach($this->settings[$model->name]['label'] as $field) {
            if (!$model->hasField($field)) {
                return;
            }
        }
        
        if ($model->hasField($this->settings[$model->name]['slug']) && ($this->settings[$model->name]['overwrite'] || empty($model->{$model->primaryKey}))) {
            $label = '';
            
            foreach($this->settings[$model->name]['label'] as $field) {
                $label .= ife(!empty($label), ' ', '');
                $label .= $model->data[$model->name][$field];
            }
            
            if (empty($label)) {
                $label = 'slug';
            }
            
            // If the user submits a non-empty slug use that one, else generate a new one from the label field
            $slug = '';
            if (isset($model->data[$model->name][$this->settings[$model->name]['slug']]) && !empty($model->data[$model->name][$this->settings[$model->name]['slug']])) {
            	$slug = $this->_getSlug($model->data[$model->name][$this->settings[$model->name]['slug']], $this->settings[$model->name]);
            } else {
                $slug = $this->_getSlug($label, $this->settings[$model->name]);
            }
            
            // Find out if such a slug exists
            $conditions = "{$model->name}.{$this->settings[$model->name]['slug']} = '$slug'";
            
            if (!empty($model->{$model->primaryKey})) {
                $conditions[$model->name . '.' . $model->primaryKey] = '!= ' . $model->{$model->primaryKey};
            }
            
            $result = $model->findAll($conditions, array($model->primaryKey, $this->settings[$model->name]['slug']), null, null, 1, 0);
            $sameUrls = null;
            
            if ($result !== false && !empty($result)) {
                $sameUrls = Set::extract($result, '{n}.' . $model->name . '.' . $this->settings[$model->name]['slug']);
            }

            if (!empty($sameUrls)) {
                $begginingSlug = $slug;
                $index = 1;
        
                while($index > 0) {
                    if (!in_array($begginingSlug . $this->settings[$model->name]['separator'] . $index, $sameUrls)) {
                        $slug = $begginingSlug . $this->settings[$model->name]['separator'] . $index;
                        $index = -1;
                    }
                    $index++;
                }
            }
            
            $model->data[$model->name][$this->settings[$model->name]['slug']] = $slug;
        }
    }
    
    /**
     * Generate a slug for the given string using specified settings.
     *
     * @param string $string    String.
     * @param array $settings   Settings to use (looks for 'separator' and 'length')
     *
     * @return string   Slug for given string.
     */
    private function _getSlug($string, $settings) {
		/*
		might pertain to cakephp 1.2.4
		if (!class_exists('AppHelper')) {
		  App::import('Core', 'AppHelper', false); 
		} 
		*/
		require_once APP . 'app_helper.php';
    	return AppHelper::slug($string, $settings['separator']);
    }

}
