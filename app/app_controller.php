<?php
App::import('Sanitize');
App::import('Core', 'l10n');

class AppController extends Controller {

	public $components = array('Auth', 'Cookie', 'RequestHandler', 'Seo');
	public $currentUserId;
	public $helpers = array('Html', 'Wildflower.Htmla', 'Form', 'Javascript', 'Wild', 'Navigation', 'PartialLayout', 'Textile');
	public $homePageId;
	public $isAuthorized = false;
    public $isHome = false;
    public $view = 'Theme';
    public $theme = 'wildflower';
	
	private $_isDatabaseConnected = true;
	
	function __construct() {
		// Autoload APP helpers
		$appHelpers = scandir(APP . 'views' . DS . 'helpers');
		foreach ($appHelpers as $i => $fileName) {
		    if ($fileName[0] == '.' or strpos($fileName, '.php') < 1) {
		        unset($appHelpers[$i]);
		        continue;
		    }
		    
	        $fileName = str_replace('.php', '', $fileName);
	        $appHelpers[$i] = Inflector::camelize($fileName);
		}
		$this->helpers = am($this->helpers, $appHelpers);
		parent::__construct();
	}
	
	/**
     * Called before any controller action
     * 
     * Do 3 things:
     *   1. protect admin area
     *   2. check for user sessions
     *   3. set site parameters
     */
    function beforeFilter() {
        parent::beforeFilter();

        // Wilflower callbacks from app/controllers/wildflower_callbacks
        $this->wildflowerCallback('before');
        
        // AuthComponent settings
        $this->Auth->userModel = 'WildUser';
        $this->Auth->fields = array('username' => 'login', 'password' => 'password');
        $prefix = Configure::read('Wildflower.prefix');
        $this->Auth->loginAction = "/$prefix/login";
        $this->Auth->logoutAction = array('plugin' => 'wildflower', 'prefix' => $prefix, 'controller' => 'wild_users', 'action' => 'logout');
        $this->Auth->autoRedirect = false;
        $this->Auth->allow('update_root_cache'); // requestAction() actions need to be allowed
        $this->Auth->loginRedirect = "/$prefix";
        
		//$this->_assertDatabaseConnection();

		$this->_configureSite();
        
		// Admin area requires authentification
		if ($this->isAdminAction()) {
			// Set admin layout and admin specific view vars
			$this->layout = 'admin_default';
		} else {
			$this->layout = 'default';
			$this->Auth->allow('*');
		}
		$this->isAuthorized = $this->Auth->isAuthorized();
		
		// Internationalization
		$this->L10n = new L10n();
        $this->L10n->get('eng');
        Configure::write('Config.language', 'en');

		// Site settings
		$this->_siteSettings = Configure::read('AppSettings');
		// Home page ID
		$this->homePageId = intval(Configure::read('AppSettings.home_page_id'));

		// Set cookie defaults
		$this->cookieName = Configure::read('Wildflower.cookie.name');
		$this->cookieTime = Configure::read('Wildflower.cookie.expire');
		$this->cookieDomain = '.' . getenv('SERVER_NAME');

		// Compress output to save bandwith / speed site up
		if (!isset($this->params['requested']) && Configure::read('Wildflower.gzipOutput')) {
		    $this->gzipOutput();
		}
    }

    /**
     * @TODO legacy code, refacor
     *
     * Delete an item
     *
     * @param int $id
     */
    function wf_delete($id = null) {
    	$id = intval($id);
    	$model = $this->modelClass;
    	
        if ($this->RequestHandler->isAjax()) {
            $success = $this->{$model}->del($id);
            
            $responce = json_encode(array('success' => $success, 'id' => $id));
            header('Content-type: text/plain');
            exit($responce);
        }
        
        if (empty($this->data)) {
            $this->data = $this->{$model}->findById($id);
            if (empty($this->data)) {
                $this->indexRedirect();
            }
        } else {
            if ($this->{$model}->del($this->data[$model][$this->{$model}->primaryKey])) {
                $this->Session->setFlash("{$model} #$id was deleted.");
                $this->redirect(array('action' => 'index'));
            } else {
            	$this->Session->setFlash("Error while deleting {$model} #$id.");
            }
        }
    }
    
    /**
     * Update more records at once
     *
     * @TODO Could be much faster using custom UPDATE or DELETE queries
     */
    function wf_mass_update() {
        if (isset($this->data['__action'])) {
            foreach ($this->data['id'] as $id => $checked) {
                if (intval($checked) === 1) {
                    switch ($this->data['__action']) {
                        case 'delete':
                            // Delete with comments
                            $this->{$this->modelClass}->delete($id);
                            break;
                        case 'publish':
                            $this->{$this->modelClass}->publish($id);
                            break;
                        case 'draft':
                            $this->{$this->modelClass}->draft($id);
                            break;
                    }
                }
            }
        }
        
    	$link = am($this->params['named'], array('action' => 'wf_index'));
        return $this->redirect($link);
    }
    
    /**
     * Admin search
     *
     * @param string $query Search term, encoded by Javascript's encodeURI()
     */
    function wf_search($query = '') {
        $query = urldecode($query);
        $results = $this->{$this->modelClass}->search($query);
        $this->set('results', $results);
        $this->render('/wild_dashboards/wf_search');
    }
    
    /**
     * Preview a post or a page
     *
     * @param string $fileName Cached page/post content file name
     */
    function wf_preview($fileName = null) {
        if (is_null($fileName)) return $this->cakeError('object_not_found');
        
    	$this->layout = 'default';
    	
        $previewData = $this->__readPreviewCache($fileName);
        $id = intval($previewData[$this->modelClass]['id']);
        $item = $this->{$this->modelClass}->findById($id);
        if (empty($item)) $this->cakeError('object_not_found');
        
        if (is_array($previewData) && !empty($previewData)) {
            unset($previewData[$this->modelClass]['created']);
            $item[$this->modelClass] = am($item[$this->modelClass], $previewData[$this->modelClass]);
        }
        
        $itemName = 'item';
        switch ($this->modelClass) {
            case 'WildPost':
                $itemName = 'post';
                break;
            case 'WildPage':
                $itemName = 'page';
                break;
        }
        
        $params = array($itemName => $item);
        if (isset($item[$this->modelClass]['description_meta_tag'])) {
            $params['descriptionMetaTag'] = $item[$this->modelClass]['description_meta_tag'];
        }
        $this->set($params);
        
        $this->pageTitle = $item[$this->modelClass]['title'];
        
        if ($this->modelClass = 'WildPost') {
            return $this->render('view');
        } else if ($this->modelClass = 'WildPage') {
            return $this->_chooseTemplate($item[$this->modelClass]['slug']);
        }
    }
	
	/**
	 * Make sure the application returns 404 if it's not a requested action
	 *
	 */
	function assertInternalRequest() {
	    $this->autoRender = false;
	    
	    if ($this->params['requested']) {
	        return true;
	    }
	    
	    $this->do404();
	    return false;
	}
	
	function xssBlackHole() {
	    $this->cakeError('xss');
	}
    
    function afterFilter() {
        parent::afterFilter();
        $this->wildflowerCallback();
    }
    
    /**
     * Launch callbacks if they exist for current controller/method
     *
     * Callback for controllers are stored in <code>app/controllers/wildflower-callbacks/</code>.
     * The name convencions is unserscored class that you want to plug into with "_callback"
     * suffix. Examples:
     *    
     *    - wild_pages_controller_callback.php
     *    - wild_comments_controller_callback.php
     *    - wildflower_app_controller_callback.php
     *
     * @param string $when Launch <code>before</code> or <code>after</code> current action
     */
    function wildflowerCallback($when = 'after') {
        // app_controller
        if (class_exists('AppControllerCallback')) {
            $plugin = new AppControllerCallback;
            foreach (array('beforeFilter', 'afterFilter') as $filter) {
                $method = $filter;
                if (method_exists($plugin, $method)) {
                    $plugin->{$method}();
                    return;
                } 
            }
        }
        
        $className = Inflector::camelize($this->params['controller']) . 'ControllerCallback';
        if (class_exists($className)) {
            $plugin = new $className;
            $method = $when . '_' . $this->params['action'];
            if (method_exists($plugin, $method)) {
                $plugin->{$method}();
                return;
            }
        }
    }
    
    /**
     * Before rendering
     * 
     * Set nice SEO titles.
     */
    function beforeRender() {
        parent::beforeRender();
        
        // // @TODO: Hmmmm?
        // if (!$this->_isDatabaseConnected) {
        //     return;
        // }

        $this->Seo->title();
        
    	/** @var $refeter string Convenient $referer var in all views **/
    	$this->set('referer', $this->referer());
    	
    	// Set view parameters (CmsHelper uses some of these for example)
        $params = array(
            'siteName' => Configure::read('AppSettings.site_name'),
            'siteDescription' => Configure::read('AppSettings.description'),
            'isLogged' => $this->isAuthorized,
            'isAuthorized' => $this->isAuthorized,
            'isPage' => false,
            'isPosts' => false,
            'isHome' => $this->isHome,
            'homePageId' => $this->homePageId,
            'here' => substr($this->here, strlen($this->base) - strlen($this->here)),
        );
        $this->params['Wildflower']['view'] = $params;
    	$this->set($params);
    	
    	// User ID for views
		$this->set('loggedUserId', $this->Auth->user('id'));
    }

	function do404() {
		$this->pageTitle = 'Page not found';
        
        $this->cakeError('error404', array(array(
                'message' => 'Requested page was not found.',
                'base' => $this->base)));
	}
	
    function getLoggedInUserId() {
        return $this->Auth->user('id');
    }
	
	function wf_create_preview() {
        $cacheDir = Configure::read('Wildflower.previewCache') . DS;
        
        // Create a unique file name
        $fileName = time();
        $path = $cacheDir . $fileName . '.json';
        while (file_exists($path)) {
            $fileName++;
            $path = $cacheDir . $fileName . '.json';
        }
        
        // Write data to preview file
        $data = json_encode($this->data[$this->modelClass]);
        file_put_contents($path, $data);
        
        // Garbage collector
        $this->__previewCacheGC($cacheDir);
        
        $responce = array('previewFileName' => $fileName);
        $this->set('data', $responce);
        $this->render('/elements/json');
    }

    /**
     * Tell wheather the current action should be protected
     *
     * @return bool
     */
    function isAdminAction() {
        $adminRoute = Configure::read('Routing.admin');
        $wfPrefix = Configure::read('Wildflower.prefix');
        if (isset($this->params[$adminRoute]) && $this->params[$adminRoute] === $wfPrefix) return true;
        return (isset($this->params['prefix']) && $this->params['prefix'] === $wfPrefix);
    }

	/**
	 * Write all site settings to Configure class as key => value pairs.
	 * Access them anywhere in the application with Configure::read().
	 *
	 */
	private function _configureSite() {
		$settings = ClassRegistry::init('WildSetting')->getKeyValuePairs();
        Configure::write('AppSettings', $settings); // @TODO add under Wildlfower. configure namespace
        Configure::write('Wildflower.settings', $settings); // The new namespace for WF settings
	}

    /**
     * Delete old files from preview cache
     * 
     * @link http://www.jonasjohn.de/snippets/php/delete-temporary-files.htm
     *
     * @param string $path
     */
    protected function __previewCacheGC($path) {
        // Filetypes to check (you can also use *.*)
        $fileTypes = '*.json';
         
        // Here you can define after how many
        // minutes the files should get deleted
        $expire_time = 120;
         
        // Find all files of the given file type
        foreach (glob($path . $fileTypes) as $Filename) {
            // Read file creation time
            $FileCreationTime = filectime($Filename);
            // Calculate file age in seconds
            $FileAge = time() - $FileCreationTime; 
            // Is the file older than the given time span?
            if ($FileAge > ($expire_time * 60)) {
                unlink($Filename);
            }
        }
    }
    
     /**
      * Read and decode data from preview cache
      * 
      * @param string $fileName
      * @return array
      */
     protected function __readPreviewCache($fileName) {
         $previewCachePath = Configure::read('Wildflower.previewCache') . DS . $fileName . '.json';
         if (!file_exists($previewCachePath)) {
             return trigger_error("Cache file $previewCachePath does not exist!");
         }

         $json = file_get_contents($previewCachePath);
         $item[$this->modelClass] = json_decode($json, true);

         return $item;
     }
	
	/**
	 * Gzip output
	 * 
	 * Cuts the bandwith cost down to half.
	 * Helps the responce time.
	 */
	function gzipOutput() {
		if (@ob_start('ob_gzhandler')) {
			header('Content-type: text/html; charset: UTF-8');
			header('Cache-Control: must-revalidate');
			$offset = -1;
			$expireTime = gmdate('D, d M Y H:i:s', time() + $offset);
			$expireHeader = "Expires: $expireTime GMT";
			header($expireHeader);
		}
	}
	
	/**
	 * Test if we have the connection to the database
	 *
	 * @return bool
	 */
	private function _assertDatabaseConnection() {
	    if (Configure::read('debug') < 1) {
	        return true;
	    }
	    
	    $db = @ConnectionManager::getDataSource('default');
	    if ($db->connected) {
	        return true;
	    }
	    
	    $this->_isDatabaseConnected = false;
	    $this->set('database_config', $db->config);
        $this->render('/errors/no_database', 'no_database');
	    exit();
	}
	
	/**
	 * @TODO duplicate in AppHelper
	 * Returns a string with all spaces converted to $replacement and non word characters removed.
	 *
	 * @param string $string
	 * @param string $replacement
	 * @return string
	 * @static
	 */
    static function slug($string, $replacement = '-') {
    	$string = trim($string);
        $map = array(
            '/à|á|å|â|ä/' => 'a',
            '/è|é|ê|ẽ|ë/' => 'e',
            '/ì|í|î/' => 'i',
            '/ò|ó|ô|ø/' => 'o',
            '/ù|ú|ů|û/' => 'u',
            '/ç|č/' => 'c',
            '/ñ|ň/' => 'n',
            '/ľ/' => 'l',
            '/ý/' => 'y',
            '/ť/' => 't',
            '/ž/' => 'z',
            '/š/' => 's',
            '/æ/' => 'ae',
            '/ö/' => 'oe',
            '/ü/' => 'ue',
            '/Ä/' => 'Ae',
            '/Ü/' => 'Ue',
            '/Ö/' => 'Oe',
            '/ß/' => 'ss',
            '/[^\w\s]/' => ' ',
            '/\\s+/' => $replacement,
            String::insert('/^[:replacement]+|[:replacement]+$/', 
            array('replacement' => preg_quote($replacement, '/'))) => '',
        );
        $string = preg_replace(array_keys($map), array_values($map), $string);
        return low($string);
    }
    
    function wf_get_fields() {
        if (Configure::read('debug') < 1) {
            return;
        }

        $output = '';
        foreach ($this->{$this->modelClass}->schema() as $name => $column) {
            $output .= "'$name' => array(";
            
            // Fields
            foreach ($column as $field => $value) {
                if (is_null($value) or $value === '') {
                    continue;
                }
                $output .= "'$field' => ";
                $value = str_replace("'", "\'", $value);
                if (!is_numeric($value)) {
                    $value = "'$value'";
                }
                $output .= $value . ', ';
            }
            
            $output .= "),\n";
        }
        
        pr($output);
        die();
    }
	
}
