<?php
class WildPost extends WildflowerAppModel {
    
    public $useTable = 'posts';

	public $actsAs = array(
	   'Containable',
	   'Wildflower.Slug' => array('separator' => '-', 'overwrite' => false, 'label' => 'title'),
	   'Wildflower.Versionable' => array('title', 'content', 'description_meta_tag', 'keywords_meta_tag')
	);
	public $belongsTo = array(
	   'WildUser' => array(
	       'className' => 'Wildflower.WildUser',
	       'foreignKey' => 'user_id'
	   )
	);
	public $hasAndBelongsToMany = array(
	   'WildCategory' => array(
	       'className' => 'Wildflower.WildCategory',
	       'joinTable' => 'categories_posts',
	       'foreignKey' => 'post_id',
	       'associationForeignKey' => 'category_id'
	   )
	);
	public $hasMany = array(
	   'WildComment' => array(
	       'className' => 'Wildflower.WildComment',
	       'conditions' => 'WildComment.spam = 0',
	       'order' => 'WildComment.created asc',
	       //'foreignKey' => 'post_id'
	   )
	);
	public $validate = array(
		'title' => array('rule' => array('maxLength', 255), 'allowEmpty' => false, 'required' => true)
	);
	public static $statusOptions = array(
       '0' => 'Published',
       '1' => 'Draft'
    );
    
    function beforeSave() {
        if (isset($this->data[$this->name]['publish'])) {
            $this->data[$this->name]['draft'] = 0;
            unset($this->data[$this->name]['publish']);
        }
        
        return true;
    }
    
    /**
     * Get URL to a post, suitable for $html->url() and likes
     *
     * @param string $slug
     * @return string
     */
    static function getUrl($slug) {
        $url = '/' . WILDFLOWER_POSTS_INDEX . '/' . $slug;
        return $url;
    }

    /**
     * Mark a post as a draft
     *
     * @param int $id
     */
    function draft($id) {
        $id = intval($id);
        return $this->query("UPDATE {$this->useTable} SET draft = 1 WHERE id = $id");
    }
    
    /**
     * Find all posts but without some fields not necessary for list view
     *
     * @param int $limit
     * @return array
     */
    function findAllForList($limit = null) {
    	$limitSql = '';
    	if ($limit) {
			$limitSql = " LIMIT $limit";
    	}
    	
    	$posts = $this->query("SELECT `Post`.`id`, `Post`.`slug`, `Post`.`title`, `Post`.`user_id`, `Post`.`created`, 
    						`User`.`id`, `User`.`name` 
    					FROM `posts` AS `Post` 
    					LEFT JOIN `users` AS `User` 
    					ON (`Post`.`user_id` = `User`.`id`) 
    					ORDER BY `Post`.`created` 
    					DESC$limitSql");
    	return $posts;
    }
    
    /**
     * 
     * @deprecated This logic better suits into controller
     */
    function getCategoryScope($slug) {
    	// Find all post IDs from this cat
    	$this->Category->Behaviors->attach('Containable');
    	$this->Category->contain("{$this->name}.id");
    	$category = $this->Category->findBySlug($slug);

    	// Retrive these posts with associations
    	$ids = array();
    	foreach ($category['Post'] as $post) {
    		$ids[] = $post['id'];
    	}
    	
    	$in = implode(', ', $ids);
    	$scope = "{$this->name}.id IN ($in)";
    	//$this->Behaviors->attach('Containable');
    	//$this->contain(array('User' => array('id', 'name'), 'Comment' => array('id'), 'Category' => array('id', 'title', 'slug')));
    	
    	return $scope;
    }

    function getStatusOptions() {
        return self::$statusOptions;
    }

    /**
     * Publish a post (unmark draft status)
     *
     * @param int $id
     */
    function publish($id) {
        $id = intval($id);
        return $this->query("UPDATE {$this->useTable} SET draft = 0 WHERE id = $id");
    }
    
	/**
     * Search title and content fields
     *
     * @param string $query
     * @return array
     */
    function search($query) {
    	$fields = array('id', 'title', 'slug');
    	$titleResults = $this->findAll("Post.title LIKE '%$query%'", $fields, null, null, 1);
    	$contentResults = array();
    	if (empty($titleResults)) {
    		$titleResults = array();
			$contentResults = $this->findAll("MATCH (Post.content) AGAINST ('$query')", $fields, null, null, 1);
    	} else {
    		$alredyFoundIds = join(', ', Set::extract($titleResults, '{n}.Post.id'));
    	    $notInQueryPart = '';
            if (!empty($alredyFoundIds)) {
                $notInQueryPart = " AND {$this->name}.id NOT IN ($alredyFoundIds)";
            }
    		$contentResults = $this->findAll("MATCH (Post.content) AGAINST ('$query')$notInQueryPart", $fields, null, null, 1);
    	}
    	
    	if (!is_array(($contentResults))) {
    		$contentResults = array();
    	}
    	
    	$results = array_merge($titleResults, $contentResults);
    	return $results;
    }
    
}
