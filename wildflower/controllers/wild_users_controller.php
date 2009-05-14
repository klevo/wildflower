<?php
class WildUsersController extends AppController {

    public $helpers = array('Wildflower.List', 'Time');
    public $pageTitle = 'User Accounts';

    /**
     * @TODO shit code, refactor
     *
     * Delete an user
     *
     * @param int $id
     */
    function wf_delete($id) {
        $id = intval($id);
        if ($this->RequestHandler->isAjax()) {
            return $this->WildUser->del($id);
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
        $WildUser = ClassRegistry::init('WildUser');

        // Try to authorize user with POSTed data
        if ($user = $this->Auth->user()) {
            if (!empty($this->data) && $this->data['WildUser']['remember']) {
                // Generate unique cookie token
                $cookieToken = Security::hash(String::uuid(), null, true);
                
                while ($WildUser->findByCookieToken($cookieToken)) {
                    $cookieToken = Security::hash(String::uuid(), null, true);
                }

                // Save token to DB
                $WildUser->create($user);
                $WildUser->saveField('cookie_token', $cookieToken);

                // Save login cookie
                $cookie = array();
                $cookie['login'] = $this->data['WildUser']['login'];
                $cookie['cookie_token'] = $cookieToken;
                $this->Cookie->write('Auth.WildUser', $cookie, true, '+2 weeks');
                unset($this->data['WildUser']['remember']);
            }
            
            // Save last login time
            $WildUser->create($user);
            $WildUser->saveField('last_login', time());
            
            $this->redirect($this->Auth->redirect());
        }

        // Try to authorize user with data from a cookie
        if (empty($this->data)) {
            $cookie = $this->Cookie->read('Auth.WildUser');
            if (!is_null($cookie)) {
                $this->Auth->fields = array(
                    'username' => 'login', 
                    'password' => 'cookie_token'
                );
                if ($this->Auth->login($cookie)) {
                    //  Clear auth message, just in case we use it.
                    $this->Session->del('Message.auth');
                    
                    // Save last login time
                    $WildUser->create($user);
                    $WildUser->saveField('last_login', time());
                    
                    return $this->redirect($this->Auth->redirect());
                } else { 
                    // Delete invalid Cookie
                    $this->Cookie->del('Auth.WildUser');
                }
            }
        }
    }

    /**
     * Logout
     * 
     * Delete User info from Session, Cookie and reset cookie token.
     */
    function wf_logout() {
        $this->WildUser->create($this->Auth->user());
        $this->WildUser->saveField('cookie_token', '');
        $this->Cookie->del('Auth.WildUser');
        $this->redirect($this->Auth->logout());
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
     * Create new user
     *
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
        App::import('Security');
        $this->data['WildUser']['password'] = Security::hash($this->data['WildUser']['password'], null, true);
        $this->WildUser->create($this->data);
        if (!$this->WildUser->exists()) $this->cakeError('object_not_found');
        if ($this->WildUser->save()) {
            return $this->redirect(array('action' => 'edit', $this->data[$this->modelClass]['id']));
        }
        $this->render('wf_change_password');
    }

}
