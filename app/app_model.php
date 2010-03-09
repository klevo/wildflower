<?php
class AppModel extends Model {


    
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
    
    /**
     * Format a timestamp to MySQL date format
     *
     * @param int $time
     * @return string
     */
    function timeToDate($time) {
        return date("Y-m-d", $time);
    }
    
    /**
     * Format a timestamp to MySQL datetime format
     *
     * @param int $time
     * @return string
     */
    function timeToDatetime($time) {
        return date("Y-m-d H:i:s", $time);
    }

}
