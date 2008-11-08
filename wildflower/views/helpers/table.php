<?php
/**
 * Table Helper
 * Helper for generating data tables with CRUD links
 * 
 * @author Robert Starsi <klevo@klevo.sk>
 */
class TableHelper extends AppHelper {
    
    public $helpers = array('Html', 'Time');
    private $_defaultSettings = array(
        'model' => '',
        'editField' => 'title',
        'left' => 'lft',
        'right' => 'rght',
        'class' => 'table',
        'tree' => false,
        'dateFields' => array('created', 'updated'));
    private $_emptyMessage = __('There are no records to display', true) . '.';
    
    /**
     * Generate mass edit table from Cake data
     * 
     * @param array $data MPTT data from model
     * @param array $settings Settings
     */
    function create($data, $settings = array()) {
        if (empty($data)) {
            return "<p>{$this->_emptyMessage}</p>";
        }
        
        $settings = array_merge($this->_defaultSettings, $settings);
        $isTree = $settings['tree'];
        
        if (empty($settings['model'])) {
            trigger_error('Model class name can`t be empty.');
        }
        
        $modelPlural = Inflector::pluralize($settings['model']);
        $lModelPlural = low($modelPlural);
        $controller = $lModelPlural;
        $modelLow = low($settings['model']);
        
        $summary = " summary=\"$modelPlural table lists all site $lModelPlural and provides view, edit and delete actions.\"";
        $tableHtml = "\n<table cellspacing=\"0\"$summary";
        
        // Set CSS classes
        if (!empty($settings['class'])) {
            $tableHtml .= " class=\"{$settings['class']}\"";
        }
            
        $tableHtml .= ">\n";
        
        // Find out table column names
        $tableHeaderItems = array();
        if (isset($settings['fields']) && is_array($settings['fields'])) {
        	$tableHeaderItems = $settings['fields'];
        } else {
	        foreach ($data[0][$settings['model']] as $fieldName => $field) {
                $tableHeaderItems[] = $fieldName;
	        }
        }
        $colsCount = count($tableHeaderItems);
        
        // Table head
        $tableHtml .= "<thead>\n";
        $tableHtml .= "<tr>";
        
        // Checkbox dummy cell
        $tableHtml .= "<th></th>";
        
        for ($i = 0; $i < $colsCount; $i++) {
            $field = Inflector::humanize($tableHeaderItems[$i]);
            $tableHtml .= "<th scope=\"col\">$field</th>";
        }
        
        $tableHtml .= "<th scope=\"col\" colspan=\"2\">Actions</th>";
        $tableHtml .= "</tr></thead>\n<tbody>";
        
        // Rows
        $rightNodes = array();
        foreach ($data as $key => $node) {
            $level = 0;
            if ($isTree) {
                // Check if we should remove a node from the stack
                while (!empty($rightNodes) && ($rightNodes[count($rightNodes) - 1] < $node[$settings['model']][$settings['right']])) {
                   array_pop($rightNodes);
                }
                $level = count($rightNodes);
            }
            
            $cssClasses = low($settings['model']) . '-' . $node[$settings['model']]['id'] . " level-$level";
            
            // Alt CSS class for odd rows
            if ($key % 2 == 0) {
                $cssClasses .= " odd";
            }
            
            $id = $node[$settings['model']]['id'];
            
            $trId = "$modelLow-$id";
            
            $tableHtml .= "<tr id=\"$trId\" class=\"$cssClasses\">";
            
            // Add checkboxes
            $tableHtml .= "<td><input type=\"checkbox\" name=\"data[Page][$id]\" /></td>";
            
            // Add table cells
            for ($i = 0; $i < $colsCount; $i++) {
            	$field = $tableHeaderItems[$i];
            	$cell = $node[$settings['model']][$field];
            	$tdClasses = array();
            	// Nice short dates
            	if (in_array($field, $settings['dateFields'])) {
            		$cell = $this->Time->niceShort($cell);
            		$tdClasses[] = 'date';
            	}
                if ($i > 0 && strlen($cell) > Configure::read('Wildflower.table.maxlength')) {
                    $cell = mb_substr($cell, 0, Configure::read('Wildflower.table.maxlength'), Configure::read('App.encoding')) . "...";
                }
                
                // Edit field
                if ($field == $settings['editField']) {
	                $dashes = $spaces = '';
	                if ($level > 0) {
	                    $dashes = '&ndash;';
	                    $spaces = str_repeat('&nbsp;&nbsp;', $level - 1);
	                }
                	$cell = $this->Html->link($cell, "/" . Configure::read('Routing.admin') . "/$controller/edit/$id", array(
                	   'title' => "Edit this $modelLow"), null, false);
                	$cell = "$spaces$dashes $cell";
                	$tdClasses[] = 'primary-field';
                }
                
                $tdAttr = '';
                if (!empty($tdClasses)) {
                	$tdAttr = ' class="' . join(' ', $tdClasses) . '"';
                }
                $tableHtml .= "<td$tdAttr>$cell</td>";
            }
            
            // Append actions
            switch ($controller) {
                // Decide the correct view method
                case  'posts':
                    $slug = $node[$settings['model']]['slug'];
                    $viewPath =  '/' + WILDFLOWER_POSTS_INDEX + "/$slug";
                    break;
                case 'pages':
                    $viewPath = $node[$settings['model']]['url'];
                    break;
                default:
                    $viewPath = "/" . Configure::read('Routing.admin') . "/$controller/view/$id";
            }
            
            // Actions
            $actions = '';
            if ($isTree) {
	            $moveUpImg = $this->Html->image('moveup.gif');
	            $moveDownImg = $this->Html->image('movedown.gif');
	            $moveActions = "<td class=\"action\">" . $this->Html->link($moveUpImg, array('action' => 'moveup', $id),
		               array('class' => 'page-moveup', 'title' => 'Move this page up', 'escape' => false)) . "</td>"
		            . "<td class=\"action\">" . $this->Html->link($moveDownImg, array('action' => 'movedown', $id),
	                   array('class' => 'page-movedown', 'title' => 'Move this page down', 'escape' => false)) . "</td>";
	            $actions .= $moveActions;
            }
            
            $actions .= "<td class=\"action\">" . $this->Html->link('View', $viewPath) . "</td>";
            
            $tableHtml .= "$actions</tr>\n";
            
            // Add this node to the stack
            if ($isTree) {
                $rightNodes[] = $node[$settings['model']][$settings['right']];
            }
        }
        
        $tableHtml .= "</tbody>\n</table>\n";
        
        // Encapsulate into a form
        $formAction = $this->Html->url(array('action' => 'mass_update'));
        $formControls = "<p>With selected: <input type=\"submit\" value=\"Delete\" name=\"data[Delete]\" /> <input type=\"submit\" value=\"Mark as draft\" name=\"data[Draft]\" /> <input type=\"submit\" value=\"Publish\" name=\"data[Publish]\" /></p>";
        $html = "<form method=\"post\" action=\"$formAction\">$formControls\n$tableHtml</form>";
        
        return $html;
    }
    
}
