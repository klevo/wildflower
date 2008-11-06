<?php
uses('Sanitize');

class WildflowerAppController extends AppController {

	public $components = array('Cookie', 'RequestHandler', 'Wildflower.Seo');
	public $currentUserId;
	public $helpers = array('Html', 'Form', 'Javascript', 'Wildflower.Wild', 'Wildflower.Navigation');
	public $homePageId;
	public $isAuthorized = false;
    public $isHome = false;
	
	private $_isDatabaseConnected = true;

    /**
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
     * Update more items at once
     *
     * @TODO methods like draftAll(ids) should be created and update should be outside the cycle
     */
    function wf_mass_update() {
        $ids = explode(',', $this->data['Items']);
        $availActions = array('draft', 'delete', 'publish');
        $action = low(trim($this->data['Action']));
        if (!in_array($action, $availActions)) return;
        
        $result = array();
        foreach ($ids as $id) {
            $id = intval($id);
            $this->{$this->modelClass}->create();
            $this->{$this->modelClass}->id = $id;
            $result[] = "working on $id";
            if (!$this->{$this->modelClass}->exists(true)) { $result[] = "$id does not exists"; continue; };
            
            $success = call_user_method($action, $this->{$this->modelClass}, $id);
            $result[] = array($id => ($success) ? true : false);
        }
        
        $this->set('data', $result);
        $this->render('/elements/json');
    }
    
    /**
     * Fulltext search
     *
     */
    function wf_search() {
    	$query = $this->data['Query'];
        $results = $this->{$this->modelClass}->search($query, array('title', 'content'));
        $this->set(compact('query', 'results'));
    }

	/**
	 * Redirect to admin_index action of current controller and exit
	 */
	function adminIndexRedirect() {
		$this->redirect(array('controller' => low($this->name), 'action' => 'admin_index'), null, true);
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
	    
	    $this->cakeError('error404');
	    return false;
	}
	
	/**
	 * If admin is not logged in redirect to login screen and exit
	 *
	 */
	function assertAdminLoggedIn() {
	    if ($this->isAuthorized) {
	        return;
	    }
	    
	    $currentUrl = 'http://' . getenv('SERVER_NAME') . $this->here;
        $this->Session->write('afterLoginRedirectTo', $currentUrl);
        $this->redirect('/login', null, true);
	}
	
    /**
     * Callback before any controller action
     * 
     * Do 3 things:
     * 1.   protect admin area
     * 2.   check for user sessions
     * 3.   set site parameters
     */
    function beforeFilter() {
        // Wilflower callbacks from app/controllers/wildflower_callbacks
        $this->wildflowerCallback('before');
        
		$this->_assertDatabaseConnection();

		$this->configureSite();

		$user = $this->findUserInSessions();

		// Admin area requires authentification
		if ($this->isAdminAction()) {
			$this->assertAdminLoggedIn();

			// Set admin layout and admin specific view vars
			$this->layout = 'admin_default';
			$this->set('loggedUserId', $user['WildUser']['id']);
			$this->currentUserId = $user['WildUser']['id'];
		} else {
			$this->layout = 'default';
			$this->params['breadcrumb'][] = array('title' => 'Home', 'url' => '/');
		}

		// Site settings
		$this->_siteSettings = Configure::read('AppSettings');
		// Home page ID
		$this->homePageId = intval(Configure::read('AppSettings.home_page_id'));

		// Set cookie stuff
		$this->cookieName = Configure::read('Wildflower.cookie.name');
		$this->cookieTime = Configure::read('Wildflower.cookie.expire');
		$this->cookieDomain = '.' . getenv('SERVER_NAME');

		// Speed things up
		$this->gzipOutput();
    }
    
    function afterFilter() {
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
        if (class_exists('WildflowerAppControllerCallback')) {
            $plugin = new WildflowerAppControllerCallback;
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
        
        // @TODO: Hmmmm?
        if (!$this->_isDatabaseConnected) {
            return;
        }

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
    }

	function do404() {
		$this->pageTitle = 'Page not found';
        
        $this->cakeError('error404', array(array(
                'message' => 'Requested page was not found.',
                'base' => $this->base)));
	}
	
    function getLoggedInUserId() {
        return intval($this->Session->read('WildUser.WildUser.id')); // @TODO weird, refactor session saving
    }

    /**
     * Tell wheather the current action should be protected
     *
     * @return bool
     */
    function isAdminAction() {
        return (isset($this->params['prefix']) && $this->params['prefix'] == Configure::read('Wildflower.prefix'));
    }
    
	/**
	 * Reloads (redirects to) current url
	 */
	function reload() {
		$this->redirect($this->here, null, true);
	}

	/**
	 * Write all site settings to Configure class as key => value pairs.
	 * Access them anywhere in the application with Configure::read().
	 *
	 */
	private function configureSite() {
		App::import('Model', 'Wildflower.WildSetting');
		$WildSetting = new WildSetting;
		$settings = $WildSetting->getKeyValuePairs();
        Configure::write('AppSettings', $settings);
	}
	
	/**
	 * Authorize (log in) user with valid session or cookie
	 *
	 * @return array User model array
	 */
	private function findUserInSessions() {
		$this->isAuthorized = $this->Session->check('WildUser');
		
		if ($this->isAuthorized) {
			$user = $this->Session->read('WildUser');
		} else {
			$user = $this->_readCookie();
		}
		
		// Not logged in
		if (!isset($user['WildUser']['id'])) {
			$user['WildUser']['id'] = 0;
		}
		
		Configure::write('Wildflower.user_id', $user['WildUser']['id']);
		
		return $user;
	}

    /**
     * Delete old files from preview cache
     * 
     * @link http://www.jonasjohn.de/snippets/php/delete-temporary-files.htm
     *
     * @param string $path
     */
    protected function previewCacheGC($path) {
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
	 * Read login data from cookie
	 *
	 * @return mixed User array or null
	 */
	private function _readCookie() {
        $cookieData = $this->Cookie->read();
        if (isset($cookieData['login']) && isset($cookieData['cookie'])) {
            $login = Sanitize::escape($cookieData['login']);
            $cookieUuid = Sanitize::escape($cookieData['cookie']);
            if ($login && $cookieUuid) {
				App::import('Model', 'Wildflower.WildUser');
				$WildUser = new WildUser();
				$WildUser->recursive = -1;
                $user = $WildUser->findByLoginAndCookie($login, $cookieUuid);
                if (!empty($user)) {
                    $this->Session->write('WildUser', $user);
                    $this->isAuthorized = true;
                    return $user;
                }
            }
        }
        return null;
	}
	
	/**
	 * Gzip output
	 * 
	 * Cuts the bandwith cost down to half.
	 * Helps the responce time.
	 */
	private function gzipOutput() {
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
	 * @TODO duplicate in WildflowerAppHelper
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
	
}
