<?php
class UsersController extends AppController {

	public $components = array('Cookie');
    public $helpers = array('Html', 'Form', 'List');
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
    function admin_delete($id) {
        $id = intval($id);
        if ($this->RequestHandler->isAjax()) {
            $this->User->del($id);
            exit();
        }

        if (empty($this->data)) {
            $this->data = $this->User->findById($id);
            if (empty($this->data)) {
                $this->indexRedirect();
            }
        } else {
            $this->User->del($this->data['User']['id']);
            $this->indexRedirect();
        }
    }

    function index() {
        return $this->redirect('/users/login');
    }

    /**
     * Login screen
     */
    function login() {
        // After succesful login redirect to desired location in admin
        $afterLoginRedirectTo = '/' . Configure::read('Routing.admin');
        if ($this->Session->check('afterLoginRedirectTo')) {
            $afterLoginRedirectTo = $this->Session->read('afterLoginRedirectTo');
        }

        if (!empty($this->data)) {
            $user = $this->User->authenticate($this->data['User']['login'], $this->data['User']['password']);
            if (empty($user)) {
                $this->User->invalidate('password', 'Login and password do not match.');
            } else {
                $this->saveSession($user);
                // Cleanup the referer
                $this->Session->del('afterLoginRedirectTo');
                return $this->redirect($afterLoginRedirectTo);
            }
        }

        $this->layout = 'login';
        $this->pageTitle = 'Login';
    }

    /**
     * Logout
     * 
     * Delete User info from Session, Cookie and reset cookie UUID.
     */
    function logout() {
        $cookieName = Configure::read('Wildflower.cookie.name');
        if ($this->Session->check('User')) {
            // Generate an unique UUID
            $uuid = String::uuid();
            $user = $this->User->findByCookie($uuid);
            while (!empty($user)) {
                $uuid = String::uuid();
                $user = $this->User->findByCookie($uuid);
            }
            
            $user = $this->Session->read('User');
            $this->User->create($user);
            $this->User->saveField('cookie', $uuid);
            $this->Session->del('User');
        }

		// Destroy the keep-logged-in cookie
        $this->Cookie->destroy();
       
        $this->redirect('/login');
    }

    function admin_view($id) {
        $this->set('user', $this->User->findById($id));
    }

    /**
     * Users overview
     * 
     */
    function admin_index() {
        $users = $this->User->findAll();
        $this->set(compact('users'));
    }
    
    function admin_change_password($id = null) {
        $this->data = $this->User->findById($id);
    }

    /**
     * Add new user
     */
    function admin_create() {
        if ($this->User->save($this->data)) {
            return $this->redirect(array('action' => 'index'));
        }

        $users = $this->User->findAll();
        $this->set(compact('users'));
        $this->render('admin_index');
    }

    /**
     * Edit user account
     *
     * @param int $id
     */
    function admin_edit($id = null) {
        $this->data = $this->User->findById($id);
    }
    
    function admin_update() {
        unset($this->User->validate['password']);
        if ($this->User->save($this->data)) {
            return $this->redirect(array('action' => 'index'));
        }
        $this->render('admin_edit');
    }
    
    function admin_update_password() {
        unset($this->User->validate['name'], $this->User->validate['email'], $this->User->validate['login']);
        if ($this->User->save($this->data)) {
            return $this->redirect(array('action' => 'edit', $this->data['User']['id']));
        }
        $this->render('admin_change_password');
    }

    /**
     * Save user info in Session and Cookie
     *
     * @param array $user Cake data array
     */
    private function saveSession($user) {
        $this->Session->write('User', $user);

        // Remember this user?
        if (isset($this->data['User']['remember']) 
            and $this->data['User']['remember'] == 1) {
            $this->Cookie->write(array(
				'login' => $user['User']['login'],
				'cookie' => $user['User']['cookie']
			));
        }
    }

}
