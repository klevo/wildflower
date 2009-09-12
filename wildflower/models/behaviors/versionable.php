<?php
App::import('Model', 'Revision');

/**
 * Versionable behavior
 * 
 * Saves sets of changes like a version control.
 */
class VersionableBehavior extends ModelBehavior {
	
	public $Revision;
	/** Reference to the model calling the behavior */
	private $_model;
	/** Lower case model name in plural */
	private $_type;
	private $_versionedFields = array();
	private $_noFieldsError = 'Specify fields to version as behavior settings. \'Versionable\' => array(\'field1\', \'field2\')'; 
	private $_doVersion = true;
	
	/**
	 * When object is initialized
	 *
	 * @param $model Model object reference
	 * @param $versionedFields Array of field names to keep under version control
	 * @return void
	 */
	function setup($model, $versionedFields = array()) {
		if (empty($versionedFields)) {
			trigger_error($this->_noFieldsError);
			// @TODO add all model fields automaticaly
		}
		
		$this->Revision = ClassRegistry::init('Revision');
		$this->_model = $model;
		$this->_type = low($this->_model->name);
        $this->_versionedFields = $versionedFields;
	}
    
    /**
     * After save callback. Save a new revision here.
     *
     */
    function afterSave() {
    	if ($this->_doVersion) {
    		$this->saveRevision();
    	}
    	return true;
    }
    
    function getRevision($model, $nodeId, $versionNumber) {
        $revision = $this->getRevisionData($model, $nodeId, $versionNumber);
        //var_dump($revision);
        $revContent = $revision['Revision']['content'];
        $revData = json_decode($revContent, true);
        
        $data = $model->findById($nodeId);
        
        // Unset the versioned fields from data
        foreach ($this->_versionedFields as $field) {
            unset($data[$model->name][$field]);
        }
        
        $data['Revision']['created'] = $revision['Revision']['created'];
        
        $data[$model->name] = am($data[$model->name], $revData[$model->name]);
        return $data;
    }
    
    /**
     * Get revisions for an item
     *
     * @param Model $model
     * @param int $id
     * @param int $limit
     * @return array
     */
    function getRevisions($model, $id, $limit = null) {
        $id = intval($id);
        $conditions = "{$this->Revision->name}.node_id = $id AND {$this->Revision->name}.type = '{$this->_type}'";
        $order = "{$this->Revision->name}.revision_number DESC";
    	$revisions = $this->Revision->find('all', compact('conditions', 'order', 'limit'));
    	return $revisions;
    }
    
    /**
     * Turn off versioning for current model
     *
     * @return void
     */
    function noVersion() {
    	$this->_doVersion = false;
    }
    
    /**
     * Stores a copy of choosen fields from the current model $data property in
     * the revisions table. (Only if something has changed from the last revision.)
     *
     * @return mixed boolen 'false' or saved data on success
     */
    function saveRevision() {
        $nodeId = intval($this->_model->id);
        
        $latestRev = $this->Revision->find("{$this->Revision->name}.node_id = $nodeId AND {$this->Revision->name}.type = '{$this->_type}'", null, "{$this->Revision->name}.revision_number DESC");
        $revNo = 1;
        if (!empty($latestRev)) {
            $revNo = intval($latestRev['Revision']['revision_number']) + 1;
        }

        // Archive only the choosed fields
        $archive[$this->_model->name] = array();
        foreach ($this->_versionedFields as $field) {
        	if (isset($this->_model->data[$this->_model->name][$field])) {
                $archive[$this->_model->name][$field] = $this->_model->data[$this->_model->name][$field];        		
        	}
        }
        
        // Encode content field
        $archive = json_encode($archive);
        $revData['Revision'] = array(
            'type' => $this->_type,
            'node_id' => $nodeId, 
            'content' => $archive, 
            'revision_number' => $revNo
        );
        
        // Save only if the content has changed
        if (!isset($latestRev['Revision']['content']) 
            or $latestRev['Revision']['content'] !== $revData['Revision']['content']) {
            return $this->Revision->save($revData);
        }
        
        return false;
    }

    /**
     * Find a specific revision
     *
     * @param Model $model
     * @param int $id
     */
    private function getRevisionData($model, $node_id, $revision_number) {
        $revision = $this->Revision->find("node_id = $node_id AND revision_number = $revision_number AND {$this->Revision->name}.type = '{$this->_type}'");
        return $revision;
    }
}
