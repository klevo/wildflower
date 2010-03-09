<?php
/**
 * LayoutHelper
 * This Helper provides a few functions that can be used to assist the layout.
 * 
 * @author Robert Conner <rtconner>
 * @link http://bakery.cakephp.org/articles/view/anything_for_layout-making-html-from-the-view-available-to-the-layout
 */
class PartialLayoutHelper extends AppHelper {
    
    protected $__blockName = null;
    
    /**
     * Start a block of output to display in layout
     *
     * @param  string $name Will be prepended to form {$name}_for_layout variable
     */
    function blockStart($name) {

        if(empty($name))
            trigger_error('LayoutHelper::blockStart - name is a required parameter');
            
        if(!is_null($this->__blockName))
            trigger_error('LayoutHelper::blockStart - Blocks cannot overlap');

        $this->__blockName = $name;
        ob_start();
        return null;
    }
    
    /**
     * Ends a block of output to display in layout
     */
    function blockEnd() {
        $buffer = @ob_get_contents();
        @ob_end_clean();

        $out = $buffer; 
            
        $view = ClassRegistry::getObject('view');
        $view->viewVars[$this->__blockName.'_for_layout'] = $out;
        
        $this->__blockName = null;
    }
    
    function setLayoutVar($name, $value) {
        $view = ClassRegistry::getObject('view');
        $view->viewVars[$name] = $value;
    }
    
    function switchToEditorMode() {
        $view = ClassRegistry::getObject('view');
        $view->viewVars['editorMode'] = true;
    }
    
    /**
	 * @todo ensure that this manually outputs content
     * Output a variable only if it exists. If it does not exist you may optionally pass
     * in a second parameter to use as a default value.
     * 
     * @param mixed $variable Data to ourput
     * @param mixed $defaul Value to output if first paramter does not exist
     */
    function output(&$var, $default=null) {
        if(!isset($var) or $var==null) {
            if(!is_null($default)) 
                echo $default;
        } else
            echo $var;    
    }
    
}
