<?php
class WildflowerModel extends Model {
	
	function __construct($id = false, $table = null, $ds = null) {
		static $utf8Enabled = array();
		if (!isset($utf8Enabled[$this->useDbConfig])) {
			$db =& ConnectionManager::getDataSource($this->useDbConfig);
			if (low(get_class($db)) == 'dbomysql') {
				$db->execute('SET NAMES utf8');
			}
			$utf8Enabled[$this->useDbConfig] = true;
		}
		parent::__construct($id, $table, $ds);
	}

    /**
     * Get data for select box
     * 
     * @param int $skipId id to skip
     */
    function getSelectBoxData($skipId = null, $alias = 'title') {
        $condition = null;
        if (is_numeric($skipId)) {
            // Ignore the page for which we're looking for parents
            $condition = "{$this->name}.{$this->primaryKey} != $skipId";
        }
        $parentPages = $this->findAll($condition, null, "$alias ASC", null, 1, 0);
        // Array for form::select
        if (!empty($parentPages)) {
            $parentPages = array_combine(
                Set::extract($parentPages, '{n}.' . $this->name . ".{$this->primaryKey}"),
                Set::extract($parentPages, '{n}.' . $this->name . ".$alias"));
        }
        return $parentPages;
    }
    
    /**
     * Find a path to an item in MPTT tree
     *
     * @param int $tree_left Left tree value
     * @param int $tree_right Right tree value
     * @return array Ancestors ordered from top to bottom
     */
    function findPath($tree_left, $tree_right, $fields = null) {
        $ancestors = $this->findAll(
                "{$this->name}.lft < $tree_left AND {$this->name}.rght > $tree_right",
                $fields, 
                "{$this->name}.lft ASC");
        return $ancestors;
    }
    
}
