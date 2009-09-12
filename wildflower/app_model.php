<?php
class AppModel extends Model {

    /**
     * Get data for select box
     * 
     * @param int $skipId id to skip
     */
    function getListThreaded($skipId = null, $alias = 'title') {
        $condition = null;
        if (is_numeric($skipId)) {
            // Ignore the page for which we're looking for parents
            $condition = "{$this->name}.{$this->primaryKey} != $skipId";
        }
        $parentPages = $this->findAll($condition, null, "$alias ASC", null, 1, 0); // @TODO findAll depracated
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
    
    /**
     * Overloading AppModel invalidate to include l18n
     *
     * @param string $field
     * @param bool $value
     */
    function invalidate($field, $value = true) {
        return parent::invalidate($field, __($value, true));
    }
    
    /**
     * Delete record(s)
     *
     * @param mixed $ids
     * @return void
     */
    function mass_delete($ids) {
        if (!is_array($ids)) {
            $ids = array(intval($ids));
        }
        $ids = join(', ', $ids);
        $this->query("DELETE FROM {$this->useTable} WHERE id IN ($ids)");
    }

}
