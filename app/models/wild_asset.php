<?php
class WildAsset extends AppModel {
    
    public $validate = array(
        // 'file' => array(
        //     'rule' => array('isFileArray'),
        //     'required' => true,
        //     'message' => 'Select a file to upload'
        // )
	);
	
	function delete($id) {
	    $upload = $this->findById($id);
	    if (!$upload) return $this->cakeError('object_not_found');

	    // Delete DB record first
	    if (parent::delete($upload[$this->name]['id'])) {
	        $path = Configure::read('Wildflower.uploadDirectory') . DS . $upload[$this->name]['name'];
            $this->_deleteFiles($path);
            return true;
	    }
	    
	    return false;
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
	
	static function getThumbUrl($name, $size = 120) {
	    return "/img/thumb/$name/$size/$size/1";
	}
	
	static function getUploadUrl($name) {
	    return '/' . Configure::read('Wildflower.uploadsDirectoryName') . '/' . $name;
	}
	
	/**
	 * Upload a file into the uploads dir
	 *
	 * @param array $fileArray POSTed file array
	 */
	static function upload($fileArray) {
        // @TODO better array check
	    if (!is_array($fileArray) or empty($fileArray['name'])) {
	        return '';
	    }
	    
	    $fileName = trim($fileArray['name']);
        $uploadPath = Configure::read('Wildflower.uploadDirectory') . DS . $fileName;
        
        // Rename file if already exists
        $i = 1;
        while (file_exists($uploadPath)) {
            // Append a number to the end of the file,
            // if it alredy has one increase it
            $newFileName = explode('.', $fileName);
            $lastChar = mb_strlen($newFileName[0], Configure::read('App.encoding')) - 1;
            if (is_numeric($newFileName[0][$lastChar]) and $newFileName[0][$lastChar - 1] == '-') {
                $i = intval($newFileName[0][$lastChar]) + 1;
                $newFileName[0][$lastChar] = $i;
            } else {
                $newFileName[0] = $newFileName[0] . "-$i";
            }
            $newFileName = implode('.', $newFileName);
            $uploadPath = Configure::read('Wildflower.uploadDirectory') . DS . $newFileName;
            $fileName = $newFileName;
        }
   
        // Upload file
        $isUploaded = @move_uploaded_file($fileArray['tmp_name'], $uploadPath);
        
        // Make this file writable and readable
        chmod($uploadPath, 0777);
        
        return $fileName;
	}
	
    /**
     * Delete one or more files
     *
     * @param mixed $paths
     * @return void
     */
    private function _deleteFiles($paths = array()) {
        if (!is_array($paths)) {
            $paths = array($paths);
        }
        
        foreach ($paths as $path) {
            if (is_file($path)) {
                unlink($path);
            }
        }
    }
    
}