<?php
class Upload extends AppModel {

    public $validate = array(
        'file' => array(
            'rule' => array('isFileArray'),
            'required' => true,
            'message' => 'Select a file to upload'
        )
	);
	public $hasAndBelongsToMany = array(
        'Tag' => array(
            'className' => 'Tag',
            'joinTable' => 'tags_uploads',
            'foreignKey' => 'tag_id',
            'associationForeignKey' => 'upload_id',
            'uniq' => true)
	);
	
	function delete($id = null) {
	    $upload = $this->findById($id);
	    if (!$upload) {
	        return false;
	    }

	    // Delete DB record first
	    $deleted = parent::delete($id);
	    
	    // Delete the actual file
	    $path = Configure::read('Wildflower.uploadDirectory') . DS . $upload[$this->name]['name'];
        $this->deleteFiles($path);
        
        return $deleted;
	}
	
	/**
	 * Uploaded file array validation
	 *
	 * @param array $data
	 * @return bool
	 */
	function isFileArray($data) {
	    if (!isset($data['file']) or !is_array($data['file'])) return false;
	    
	    // First check for all required keys
	    $requiredKeys = array('name', 'type', 'tmp_name', 'error', 'size');
	    foreach ($requiredKeys as $key) {
	        if (!array_key_exists($key, $data['file'])) return false;
	    }
        
        // Any errors?
        // @TODO add error if any to validation msg
        if ($data['file']['error'] !== 0) return false;
        
        if ($data['file']['size'] <= 0) return false;
        
        if (empty($data['file']['name']) or empty($data['file']['tmp_name']) or empty($data['file']['type'])) return false;

        return true;
	}
	
	/**
	 * Save tags
	 *
	 * @param array $data Controller data
	 * @return array
	 */
	function saveTags(&$data) {
		// Associate with tag/s
		if (isset($data['Tag']['Tag']) && !empty($data['Tag']['Tag'])) {
			$tags = explode(',', $data['Tag']['Tag']);
			$tagIds = array();

			foreach ($tags as $tagName) {
				$tagName = trim($tagName);

				if (empty($tagName)) {
					continue;
				}

				$this->Tag->recursive = 0;
				$tag = $this->Tag->findByName($tagName);

				// Save the tag if it does not exist
				if (empty($tag)) {
					$tag = array();
					$tag['Tag']['name'] = $tagName;
					$this->Tag->create($tag);
					$this->Tag->save();
					$tagIds[] = $this->Tag->getLastInsertId();
				} else {
					$tagIds[] = $tag['Tag']['id'];
				}
			}

			return $tagIds;
		}
		
		return null;
	}
	
    /**
     * Delete files
     *
     * @param mixed $paths
     * @return void
     */
    private function deleteFiles($paths = array()) {
        if (!is_array($paths)) {
            $paths = array($paths);
        }
        
        foreach ($paths as $path) {
            if (file_exists($path) && is_file($path)) {
                unlink($path);
            }
        }
    }
}
