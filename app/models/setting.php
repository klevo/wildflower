<?php
class Setting extends AppModel {
    
    /**
     * Find all settings of given type and transform them to key => value array
     *
     * @param string $type
     * @return array
     * 
     * @TODO cache settings
     */
    function getKeyValuePairs() {
    	$settings = $this->find('all');
        $names = Set::extract($settings, "{n}.{$this->name}.name");
        $values = Set::extract($settings, "{n}.{$this->name}.value");
        $settings = array_combine($names, $values);
        return $settings;
    }
	
}
