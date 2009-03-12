<?php
class ListHelper extends AppHelper {
	
	public $helpers = array('Html');
	private $_defaultSettings = array(
	   'model' => '',
	   'class' => 'list',
	   'id' => '',
	   'alias' => 'title',
	   'primaryKey' => 'id',
	   'emptyMessage' => 'There are no records to display.'
	);
	private $_emptyMessage = "There are no records to display.";
	private $_treeSettings = array(
        'model' => '',
        'alias' => 'title',
        'left' => 'lft',
        'right' => 'rght',
        'primaryKey' => 'id',
	    'class' => 'list',
        'emptyMessage' => 'There are no records to display.'
    );
    static $isOdd = true;
	
    /**
     * Generate CRUD list from Cake data
     * 
     * @param array $data MPTT data from model
     * @param array $settings Settings
     * @param string $cssClass CSS class for the <ul> element
     * @return string List HTML or message that there are no records to display
     */
    function create($data, $settings = array()) {
        $settings = array_merge($this->_defaultSettings, $settings);
        if (empty($data)) {
            return "<p class=\"{$settings['class']}\">{$settings['emptyMessage']}</p>";
        }
        
        if (empty($settings['model'])) {
            trigger_error('Model class name can not be empty!');
        }
        
        // Start the list
        $html = "\n<ul";
        if (!empty($settings['id'])) {
            $html .= " id=\"{$settings['id']}\"";
        }
        if (!empty($settings['class'])) {
            $html .= " class=\"{$settings['class']}\"";
        }
        $html .= ">\n";
        
        $model = low($settings['model']);
        
        // Rows
        foreach ($data as $key => $node) {
            $liClass = array();
            if ($key % 2 == 0) {
                $liClass[] = 'odd';
            }
            $liClassAttr = '';
            if (!empty($liClass)) {
                $liClassAttr = ' class="' . implode(' ', $liClass) . '"';
            }
            
            $html .= "<li id=\"$model-{$node[$settings['model']][$settings['primaryKey']]}\"$liClassAttr>";
        
            // Item content
            if (isset($settings['element'])) {
                $view = ClassRegistry::getObject('view');
                $html .= $view->element($settings['element'], array('node' => $node));
            } else if (function_exists('treeItemCallback')) {
                $html .= call_user_func('treeItemCallback', $node, $this->Html);
            } else if (function_exists('listItemCallback')) {
                $html .= call_user_func('listItemCallback', $node, $this->Html);
            } else {
                $html .= $this->Html->link(
                    $node[$settings['model']][$settings['alias']], 
                    array('action' => 'edit', 'id' => $node[$settings['model']][$settings['primaryKey']]));
            }
            
            $html .= "</li>\n";
        }
        
        $html .= "</ul>\n";
        return $html;
    }
    
    /**
     * Create an unordered nested list from tree data. You can define a function called 
     * <em>treeItemCallback($nodeData, $htmlHelper)</em> to programaticaly fill 
     * each list node.
     *
     * @param array $data
     * @param array $settings
     * @return string
     */
    function createNestedTree($data, $settings = array()) {
    	extract($this->_treeSettings);
        extract($settings);
        
        // Determin limits to know when the last top node is found.
        if (isset($data[0][$model][$left])) {
            $floor = $data[0][$model][$left];
            $ceil = $data[0][$model][$right];
            foreach ($data as $node) {
                if ($node[$model][$right] > $ceil) {
                    $ceil = $node[$model][$right];
                }
            }
        } else {
            $floor = $ceil = 0;
        }
        $this->_limits = array(array($floor - 1, $ceil + 1));
        $this->_childCount = 1;
        
        // Start the list
        $return = "\n<ul";
        if ($class) {
            $return .= " class=\"$class\"";
        }
        $return .= ">";
        
        foreach ($data as $i => $node) {
            $return .= "\n" . str_repeat("\t", $this->_childCount);
            
            $hasChildren = false;
            $last = false;
            $liClass = array();
            if ($node[$model][$right] <> $node[$model][$left] + 1) { // Has some children
                $hasChildren = true;
                list ($parentLeft, $parentRight) = $this->_get_parent_indexes($node[$model][$left]);
                $last= ife($parentRight == ($node[$model][$right] + 1), true, false);
                $this->_limits[] = array($node[$model][$left], $node[$model][$right]);
            }
            if (!$hasChildren && (!isset ($data[$i +1]) || ($node[$model][$right] + 1 <> $data[$i +1][$model][$left]))) {
                // it's the last item or the last in the current series
                $last = true;
                $liClass[] = 'last';
            }
            
            if ($i % 2 == 0) {
                $liClass[] = 'odd';
            }
            
            // Start the list item
            $lModel = low($model);
            
            $classAttr = '';
            if (!empty($liClass)) {
                $_liClass = implode(' ', $liClass);
                $classAttr = ' class="' . $_liClass . '"';
            }
            
            $return .= "<li id=\"$lModel-{$node[$model][$primaryKey]}\"$classAttr>";

            // Item content
            if (function_exists('treeItemCallback')) {
            	$return .= call_user_func('treeItemCallback', $node, $this->Html);
            } else {
            	$return .= $this->Html->link($node[$model][$alias], array('action' => 'edit', 'id' => $node[$model][$primaryKey]));
            }
            
            // Close the item
            if (isset ($data[$i +1])) {
                // If it's not the absolute last item
                if ($node[$model][$right] < $data[$i +1][$model][$left]) { // Close uls
                    for ($j= 1; $j <= ($data[$i +1][$model][$left] - $node[$model][$right] - 1); $j++) {
                        $this->_childCount--;
                        if ($this->_childCount < 0) {
                            trigger_error(__('child count less than 0 in ' . __METHOD__, E_USER_WARNING));
                        }
                        $return .= '</li>';
                        $return .= "\n" . str_repeat("\t", $this->_childCount) . '</ul>';
                    }
                    $return .= '</li>';
                }
                elseif ($node[$model][$right] <> $node[$model][$left] + 1) { // Has some children
                    $return .= "\n" . str_repeat("\t", $this->_childCount) . '<ul>';
                    $this->_childCount++;
                } else {
                    $return .= '</li>';
                }
            } else {
                // Last item in data list, close ul items
                $return .= '</li>';
                for (; $this->_childCount > 1; $this->_childCount--) {
                    $return .= "\n" . str_repeat("\t", $this->_childCount - 1) . '</ul>';
                    $return .= "\n" . str_repeat("\t", $this->_childCount - 1) . '</li>';
                }
            }
        }
        
        // Close list
        $return .= "</ul>\n";
        
        return $return;
    }
    
    /**
     * Create an unordered list from tree data. You can define a function called 
     * <em>listItemCallback($nodeData, $htmlHelper)</em> to programaticaly fill 
     * each list node.
     *
     * @param array $data
     * @param array $settings
     * @return string
     */
    function createTree($data, $settings = array()) {
        $settings = array_merge($this->_treeSettings, $settings);
        
        if (empty($data)) {
            return "<p class=\"{$settings['class']}\">{$settings['emptyMessage']}</p>";
        }
        
        // Start the list
        $return = "\n<ul";
        if ($settings['class']) {
            $return .= " class=\"{$settings['class']}\"";
        }
        $return .= ">";
        
        $rightNodes = array();
        
        foreach ($data as $i => $node) {
            $level = 0;
            
            // Tree mathematics part I.
            // Check if we should remove a node from the stack
            while (!empty($rightNodes) && ($rightNodes[count($rightNodes) - 1] < $node[$settings['model']][$settings['right']])) {
               array_pop($rightNodes);
            }
            $level = count($rightNodes);
            
            $liClass = array();
            $liClass[] = "level-$level";
            
            // Odd rows CSS class
            if ($i % 2 == 0) {
                $liClass[] = 'odd';
            }
            
            $lModel = low($settings['model']);
            
            $classAttr = '';
            if (!empty($liClass)) {
                $_liClass = implode(' ', $liClass);
                $classAttr = ' class="' . $_liClass . '"';
            }
            
            // Start the list item
            $return .= "<li id=\"$lModel-{$node[$settings['model']][$settings['primaryKey']]}\"$classAttr>";

            // Item content
            if (function_exists('listItemCallback')) {
                $return .= call_user_func('listItemCallback', $node, $this->Html, $level);
            } else {
                $return .= $this->Html->link($node[$settings['model']][$alias], array('action' => 'edit', 'id' => $node[$settings['model']][$settings['primaryKey']]));
            }
            
            $return .= '</li>';
        
            // Tree mathematics part II.
            $rightNodes[] = $node[$settings['model']][$settings['right']];
        }
        
        // Close list
        $return .= "</ul>\n";
        
        return $return;
    }
    
    static function isOdd() {
        if (self::$isOdd) {
            self::$isOdd = false;
            return true;
        }
        
        self::$isOdd = true;
        return false;
    }
    
    static function resetOddCounter() {
        self::$isOdd = true;
    }
    
    private function _get_parent_indexes($thisLeft) {
        if (!$this->_limits) {
            return array (
                0,
                0
            );
        }
        $parentLeft= $this->_limits[count($this->_limits) - 1][0];
        $parentRight= $this->_limits[count($this->_limits) - 1][1];
        if ($parentRight < $thisLeft) {
            unset ($this->_limits[count($this->_limits) - 1]);
            $this->_limits= array_values($this->_limits);
            return $this->_get_parent_indexes($thisLeft);
        }
        return array (
            $parentLeft,
            $parentRight
        );
    }
    
}