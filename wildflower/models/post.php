<?php
class Post extends AppModel {
    
	public $actsAs = array(
	   'Containable',
	   'Slug' => array('separator' => '-', 'overwrite' => false, 'label' => 'title'),
	   'Versionable' => array('title', 'content', 'description_meta_tag', 'keywords_meta_tag')
	);
	public $belongsTo = array('User');
	public $hasAndBelongsToMany = array(
	    'Category' => array(
	        'with' => 'CategoriesPost'
	    )
	);
	public $hasMany = array(
	   'Comment' => array(
	       'className' => 'Comment',
	       'conditions' => 'Comment.spam = 0',
	       'order' => 'Comment.created ASC'
	   )
	);
	public static $statusOptions = array(
       '0' => 'Published',
       '1' => 'Draft'
    );

	private $findAllFromCategoryDefaults = array(
		'limit' => null, 
		'contain' => array('User'), 
		'draft' => 0,
		// Find posts from children categories too?
		'children' => false,
		'order' => 'Post.created DESC',
		'fields' => null,
	);
    
    /**
     * Get URL to a post, suitable for $html->url() and likes
     *
     * @param string $uuid
     * @return string
     */
    static function getUrl($slug) {
        $url = '/' . Configure::read('Wildflower.postsParent') . '/' . $slug;
        return $url;
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
     * Mark a post as a draft
     *
     * @param int $id
     */
    function unpublish($id) {
        $id = intval($id);
		// savefield?
        return $this->query("UPDATE {$this->useTable} SET draft = 1 WHERE id = $id");
    }
    
	/**
     * Search title and content fields
     *
     * @param string $query
     * @return array
     */
    function search($query, $contain = array('Category')) {
    	$fields = array('id', 'title', 'slug');
		$this->contain($contain);
    	$titleResults = $this->find(
			'all',
			array(
				'conditions' => "{$this->name}.title LIKE '%$query%' and {$this->name}.draft=0",
				'fields' => $fields
			)
		);
    	$contentResults = array();
    	if (empty($titleResults)) {
    		$titleResults = array();
			$contentResults = $this->find(
				'all',
				array(
					'conditions' => "MATCH ({$this->name}.content) AGAINST ('$query')",
					'fields' => $fields
				)
			);
    	} else {
    		$alredyFoundIds = join(', ', Set::extract($titleResults, '{n}.Post.id'));
    	    $notInQueryPart = '';
            if (!empty($alredyFoundIds)) {
                $notInQueryPart = " AND {$this->name}.id NOT IN ($alredyFoundIds)";
            }
    		$contentResults = $this->find(
				'all',
				array(
					'conditions' => "MATCH ({$this->name}.content) AGAINST ('$query')$notInQueryPart",
					'fields' => $fields
				)
			);
    	}
    	
    	if (!is_array(($contentResults))) {
    		$contentResults = array();
    	}
    	
    	$results = array_merge($titleResults, $contentResults);
    	return $results;
    }
    
    /**
     * Find posts from a specified category
     * 
     * @param mixed $idOrSlug Category ID or slug
	 * @param array $options Check $this->findAllFromCategoryDefaults for available options
     * @return mixed
     */
    function findAllFromCategory($idOrSlug, $options = array()) {
		$options = am($this->findAllFromCategoryDefaults, $options);
		extract($options);
		$postFields = $fields;
	
		$conditions = array(
            'id' => $idOrSlug,
        );
        if (is_string($idOrSlug)) {
			$conditions = array(
	            'slug' => $idOrSlug,
	        );
		}
        $recursive = -1;
		$fields = array('id', 'lft', 'rght');
		
        $parentCategory = $this->Category->find('first', compact('conditions', 'recursive', 'fields'));
		
		$categories = array();
		$categories[] = $parentCategory;
		
		if ($children) {
			$categories = $this->Category->find('all', array(
				'conditions' => array(
					'Category.lft >=' => $parentCategory['Category']['lft'], 
					'Category.rght <=' => $parentCategory['Category']['rght']
				),
				'recursive' => -1,
				'fields' => $fields
			));
		}

        $categoriesPosts = $this->CategoriesPost->find('all', array(
            'conditions' => array(
                'category_id' => Set::extract('{n}.Category.id', $categories),
            ),
			'recursive' => -1,
        ));
        $postsIds = Set::extract($categoriesPosts, '{n}.CategoriesPost.post_id');
        
		$posts = $this->find('all', array(
            'conditions' => array(
                'Post.id' => $postsIds,
				'Post.draft' => $draft
            ),
			'limit' => $limit,
			'contain' => $contain,
			'order' => $order,
			'fields' => $postFields
        ));
		
        return $posts;
    }

}
