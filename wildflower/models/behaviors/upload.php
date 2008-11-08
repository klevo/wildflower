<?php
/**
 * Upload behavior
 * 
 * Stores a file from specified field
 */
class UploadBehavior extends ModelBehavior {
    
    private $_model;
    private $_fileFields = array();
    private $_fileData;
    private $_uploadPath;
    
    function setup($model, $fileFields = array(), $uploadPath = null) {
        if (empty($fileFields)) {
            trigger_error('Specify fields to act as files as behavior settings.');
        }
        
        $this->_model = $model;
        $this->_fileFields = $fileFields;
        
        if ($uploadPath && file_exists($uploadPath)) {
        	$this->_uploadPath = $uploadPath;
        } else {
        	$this->_uploadPath = APP . WEBROOT_DIR . DS . 'uploads';
        }
    }
    
    /**
     * Before any save operation try to upload all the files
     *
     * @return unknown
     */
    function beforeSave() {
    	foreach ($this->_fileFields as $field) {
    		$this->_fileData[$field] = $this->_model->data[$this->_model->name][$field];
    		
    		// Upload this file
    		$fileName = $this->_fileData[$field]['name'];
    		$uploadPath =  $this->_uploadPath . DS . $fileName;
    		
    		// Filename must be unique
    		$i = 0;
    		while(file_exists($uploadPath)) {
    			$i++;
    			$fileName = explode('.', $fileName);
    			$extension = array_pop($fileName);
    			$fileName = implode('.', $fileName);
    			$this->_fileData[$field]['name'] = $fileName = $fileName . '-' . $i . '.' . $extension;
    			$uploadPath =  $this->_uploadPath . DS . $fileName;
    			
    			exit("Unique filename found, replacing with $fileName");
    		}
    		
    		$this->_fileData[$field]['uploaded'] = move_uploaded_file($this->_fileData[$field]['tmp_name'], $uploadPath);
    		
    		// Replace the file upload array with filename for this field
    		$this->_model->data[$this->_model->name][$field] = $fileName;
    	}

    	return true;
    }

    /**
     * Get MIME type for a specific file field
     *
     * @param Model $model
     * @param string $field
     * @return string
     */
    function getMimeType($model, $field) {
    	return $this->_fileData[$field]['type'];
    }
    
    /**
     * Check if the specified field file was uploaded
     *
     * @param Model $model
     * @param string $field
     * @return bool
     */
    function isUploaded($model, $field) {
    	return $this->_fileData[$field]['uploaded'];
    }
}
