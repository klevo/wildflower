<?php
uses('Sanitize');
App::import('Vendor', 'phpThumb/phpthumb.class');

/**
 * Files management
 * 
 */
class WildUploadsController extends AppController {

	public $helpers = array('Html', 'Form');
	public $components = array('RequestHandler');
	public $paginate = array(
        'limit' => 12,
        'order' => array('Upload.created' => 'desc')
    );
	
	function wf_create() {
	    $this->Upload->create($this->data);
	    
	    if (!$this->Upload->validates()) {
	        $this->feedFileManager();
	        return $this->render('admin_index');
	    }
	    
	    // Check if file with the same name does not already exist
	    $fileName = trim($this->data['Upload']['file']['name']);
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
        $isUploaded = move_uploaded_file($this->data['Upload']['file']['tmp_name'], $uploadPath);
        
        if (!$isUploaded) {
            $this->Upload->ivalidate('file', 'File can`t be moved to the uploads directory. Check permissions.');
            $this->feedFileManager();
            return $this->render('admin_index');
        }
        
        // Make this file writable and readable
        chmod($uploadPath, 0777);
        
        $this->Upload->data['Upload']['name'] = $fileName;
        if (empty($this->Upload->data['Upload']['title'])) {
            $this->Upload->data['Upload']['title'] = $fileName;
        }
        $this->Upload->data['Upload']['mime'] = $this->Upload->data['Upload']['file']['type'];
        
        $this->Upload->data['Tag']['Tag'] = $this->Upload->saveTags($this->data);
        $this->Upload->save();
        
        $this->redirect(array('action' => 'index'));
	}

	/**
	 * Main Uploads screen
	 *
	 */
	function wf_index() {
        $this->feedFileManager();
	}
	
	/**
	 * Delete an upload
	 *
	 * @param int $id
	 */
	function wf_delete($id = null) {
		$file = $this->Upload->findById($id);
		if (empty($file)) {
			return $this->indexRedirect();
		}
		
        // Delete files		
		$paths = array();
	    array_push($paths, Configure::read('Wildflower.uploadDirectory') . DS . $file['Upload']['name']);
	    $this->deleteFiles($paths);
	    
	    $this->Upload->delete($id);
		$this->indexRedirect();
	}
	
	/**
	 * Edit a file
	 *
	 * @param int $id
	 */
	function wf_edit($id = null) {
		$this->data = $this->Upload->findById($id);
	}
	
	/**
	 * Insert image dialog
	 *
	 */
	function wf_insert_image($limit = 8) {
		$this->layout = '';
		$this->paginate['limit'] = intval($limit);
		$this->paginate['conditions'] = "Upload.mime LIKE 'image%'";
		$images = $this->paginate('Upload');
		$this->set('images', $images);
	}
	
	function wf_browse_images() {
		$this->paginate['limit'] = 6;
		$this->paginate['conditions'] = "Upload.mime LIKE 'image%'";
		$images = $this->paginate('Upload');
		$this->set('images', $images);
	}
	
	function wf_update() {
	    $this->Upload->create($this->data);
	    if (!$this->Upload->exists()) return;
	    $this->Upload->saveField('title', $this->data['Upload']['title']);
	    $this->redirect(array('action' => 'edit', $this->Upload->id));
	}
	
	function beforeFilter() {
		parent::beforeFilter();
		
		// Upload limit information
        $postMaxSize = ini_get('post_max_size');
        $uploadMaxSize = ini_get('upload_max_filesize');
        $size = $postMaxSize;
        if ($uploadMaxSize < $postMaxSize) {
            $size = $uploadMaxSize;
        }
        $size = str_replace('M', 'MB', $size);
        $limits = "Maximum allowed file size: $size";
        $this->set('uploadLimits', $limits);
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
	
	private function feedFileManager() {
	    $files = $this->paginate('Upload');
        $this->set(compact('files'));
	}
	
}
