<?php
class WildUsersController extends WildflowerAppController {

	public $components = array('Cookie');
    public $helpers = array('Wildflower.List');
	/** Cookie settings */
	public $cookieDomain;
	public $cookieKey = 'ajjH21=JAso214=ajj93jasjdAJKLhd=AL';
	public $cookieName;
	public $cookieTime;

    /**
     * Delete an user
     *
     * @param int $id
     */
    function wf_delete($id) {
        $id = intval($id);
        if ($this->RequestHandler->isAjax()) {
            $this->WildUser->del($id);
            exit();
        }

        if (empty($this->data)) {
            $this->data = $this->WildUser->findById($id);
            if (empty($this->data)) {
                $this->indexRedirect();
            }
        } else {
            $this->WildUser->del($this->data[$this->modelClass]['id']);
            $this->indexRedirect();
        }
    }

    /**
     * Login screen
     *
     */
    function login() {
        $this->layout = 'login';   
        $this->pageTitle = 'Login';
        
        if ($this->Auth->user()) {
            if (!empty($this->data)) {
                if (empty($this->data['WildUser']['remember'])) {
                    $this->Cookie->del('WildUser');
                } else {
                    $cookie = array();
                    $cookie['login'] = $this->data['WildUser']['login'];
                    $cookie['password'] = $this->data['WildUser']['password'];
                    $this->Cookie->write('WildUser', $cookie, true, '+2 weeks');
                }
                unset($this->data['WildUser']['remember']);
            }
            $this->redirect($this->Auth->redirect());
        }
        
        // After succesful login redirect to desired location in admin
        // $afterLoginRedirectTo = '/' . Configure::read('Wildflower.prefix');
        //         if ($this->Session->check('afterLoginRedirectTo')) {
        //             $afterLoginRedirectTo = $this->Session->read('afterLoginRedirectTo');
        //         }
        // 
        //         if (!empty($this->data)) {
        //             $user = $this->WildUser->authenticate($this->data[$this->modelClass]['login'], $this->data[$this->modelClass]['password']);
        //             if (empty($user)) {
        //                 $this->WildUser->invalidate('password', 'Login and password do not match.');
        //             } else {
        //                 $this->saveSession($user);
        //                 // Cleanup the referer
        //                 $this->Session->del('afterLoginRedirectTo');
        //                 return $this->redirect($afterLoginRedirectTo);
        //             }
        //         }
        // 
        //         $this->layout = 'login';
        //         $this->pageTitle = 'Login';
    }

    /**
     * Logout
     * 
     * Delete User info from Session, Cookie and reset cookie UUID.
     */
    function wf_logout() {
        $cookieName = Configure::read('Wildflower.cookie.name');
        if ($this->Session->check($this->modelClass)) {
            // Generate an unique UUID
            $uuid = String::uuid();
            $user = $this->WildUser->findByCookie($uuid);
            while (!empty($user)) {
                $uuid = String::uuid();
                $user = $this->WildUser->findByCookie($uuid);
            }
            
            $user = $this->Session->read($this->modelClass);
            $this->WildUser->create($user);
            $this->WildUser->saveField('cookie', $uuid);
            $this->Session->del($this->modelClass);
        }

		// Destroy the keep-logged-in cookie
        $this->Cookie->destroy();
       
        $this->redirect($this->Auth->loginAction);
    }

    function wf_view($id) {
        $this->WildUser->recursive = -1;
        $this->set('user', $this->WildUser->findById($id));
    }

    /**
     * Users overview
     * 
     */
    function wf_index() {
        $users = $this->WildUser->findAll();
        $this->set(compact('users'));
    }
    
    function wf_change_password($id = null) {
        $this->data = $this->WildUser->findById($id);
    }

    /**
     * Add new user
     */
    function wf_create() {
        if ($this->WildUser->save($this->data)) {
            return $this->redirect(array('action' => 'index'));
        }

        $users = $this->WildUser->find('all');
        $this->set(compact('users'));
        $this->render('wf_index');
    }

    /**
     * Edit user account
     *
     * @param int $id
     */
    function wf_edit($id = null) {
        $this->data = $this->WildUser->findById($id);
        if (empty($this->data)) $this->cakeError('object_not_found');
    }
    
    function wf_update() {
        unset($this->WildUser->validate['password']);
        $this->WildUser->create($this->data);
        if ($this->WildUser->save()) {
            return $this->redirect(array('action' => 'edit', $this->WildUser->id));
        }
        $this->render('admin_edit');
    }
    
    function wf_update_password() {
        unset($this->WildUser->validate['name'], $this->WildUser->validate['email'], $this->WildUser->validate['login']);
        $this->WildUser->create($this->data);
        if (!$this->WildUser->exists()) $this->cakeError('object_not_found');
        if ($this->WildUser->save()) {
            return $this->redirect(array('action' => 'edit', $this->data[$this->modelClass]['id']));
        }
        $this->render('wf_change_password');
    }

    /**
     * Save user info in Session and Cookie
     *
     * @param array $user Cake data array
     */
    private function saveSession($user) {
        $this->Session->write($user);

        // Remember this user?
        if (isset($this->data[$this->modelClass]['remember']) 
            and $this->data[$this->modelClass]['remember'] == 1) {
            $this->Cookie->write(array(
				'login' => $user[$this->modelClass]['login'],
				'cookie' => $user[$this->modelClass]['cookie']
			));
        }
    }

}
