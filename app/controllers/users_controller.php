<?php
class UsersController extends AppController {

    public $helpers = array('Wildflower.List', 'Time');
    public $pageTitle = 'User Accounts';

    /**
     * @TODO shit code, refactor
     *
     * Delete an user
     *
     * @param int $id
     */
    function admin_delete($id) {
        $id = intval($id);
        if ($this->RequestHandler->isAjax()) {
            return $this->User->del($id);
        }

        if (empty($this->data)) {
            $this->data = $this->User->findById($id);
            if (empty($this->data)) {
                $this->indexRedirect();
            }
        } else {
            $this->User->del($this->data[$this->modelClass]['id']);
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
        $User = ClassRegistry::init('User');

        // Try to authorize user with POSTed data
        if ($user = $this->Auth->user()) {
            if (!empty($this->data) && $this->data['User']['remember']) {
                // Generate unique cookie token
                $cookieToken = Security::hash(String::uuid(), null, true);
                
                while ($User->findByCookieToken($cookieToken)) {
                    $cookieToken = Security::hash(String::uuid(), null, true);
                }

                // Save token to DB
                $User->create($user);
                $User->saveField('cookie_token', $cookieToken);

                // Save login cookie
                $cookie = array();
                $cookie['login'] = $this->data['User']['login'];
                $cookie['cookie_token'] = $cookieToken;
                $this->Cookie->write('Auth.User', $cookie, true, '+2 weeks');
                unset($this->data['User']['remember']);
            }
            
            // Save last login time
            $User->create($user);
            $User->saveField('last_login', time());
            
            $this->redirect($this->Auth->redirect());
        }

        // Try to authorize user with data from a cookie
        if (empty($this->data)) {
            $cookie = $this->Cookie->read('Auth.User');
            if (!is_null($cookie)) {
                $this->Auth->fields = array(
                    'username' => 'login', 
                    'password' => 'cookie_token'
                );
                if ($this->Auth->login($cookie)) {
                    //  Clear auth message, just in case we use it.
                    $this->Session->del('Message.auth');
                    
                    // Save last login time
                    $User->create($user);
                    $User->saveField('last_login', time());
                    
                    return $this->redirect($this->Auth->redirect());
                } else { 
                    // Delete invalid Cookie
                    $this->Cookie->del('Auth.User');
                }
            }
        }
    }

    /**
     * Logout
     * 
     * Delete User info from Session, Cookie and reset cookie token.
     */
    function admin_logout() {
        $this->User->create($this->Auth->user());
        $this->User->saveField('cookie_token', '');
        $this->Cookie->del('Auth.User');
        $this->redirect($this->Auth->logout());
    }

    function admin_view($id) {
        $this->User->recursive = -1;
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
     * Create new user
     *
     */
    function admin_create() {
        if ($this->User->save($this->data)) {
            return $this->redirect(array('action' => 'index'));
        }

        $users = $this->User->find('all');
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
        if (empty($this->data)) $this->cakeError('object_not_found');
    }
    
    function admin_update() {
        unset($this->User->validate['password']);
        $this->User->create($this->data);
        if ($this->User->save()) {
            return $this->redirect(array('action' => 'edit', $this->User->id));
        }
        $this->render('admin_edit');
    }
    
    function admin_update_password() {
        unset($this->User->validate['name'], $this->User->validate['email'], $this->User->validate['login']);
        App::import('Security');
        $this->data['User']['password'] = Security::hash($this->data['User']['password'], null, true);
        $this->User->create($this->data);
        if (!$this->User->exists()) $this->cakeError('object_not_found');
        if ($this->User->save()) {
            return $this->redirect(array('action' => 'edit', $this->data[$this->modelClass]['id']));
        }
        $this->render('admin_change_password');
    }

}
