<?php
class Setting extends AppModel {
	
    /**
     * @depracated This is not used right now
     *
     * Write setting key-value pairs to setting cache file
     *
     * @return bool
     */
    function createCache() {
        $settings = $this->findAll();
        $names = Set::extract($settings, "{n}.{$this->name}.name");
        $values = Set::extract($settings, "{n}.{$this->name}.value");
        $cachedSettings = array_combine($names, $values);
        $fileContent = json_encode($cachedSettings);
        return file_put_contents(SETTINGS_CACHE_FILE, $fileContent);
    }
    
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
