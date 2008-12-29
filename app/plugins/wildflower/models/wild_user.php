<?php
App::import('Sanitize');
/**
 * WildUser model
 * 
 * Users are Wildflower`s administrator accounts.
 *
 * @todo Allow login to have chars like _.
 * @package wildflower
 */
class WildUser extends WildflowerAppModel {
	
	public $hasMany = array(
	    'Wildflower.WildPage',
	    'Wildflower.WildPost',
	);

    public $validate = array(
        'name' => array(
            'rule' => array('between', 1, 255),
            'allowEmpty' => false,
            'required' => true
        ),
		'login' => array(
			'rule' => array('alphaNumeric', array('between', 5, 50)),
			'required' => true,
			'message' => 'Login must be between 5 to 50 alphanumeric characters long'
		),
		'password' => array(
            'between' => array(
                'rule' => array('between', 5, 50),
                'required' => true,
                'message' => 'Password must be between 5 to 50 characters long'
            ),
            'confirmPassword' => array(
                'rule' => array('confirmPassword'),
                'message' => 'Please enter the same value for both password fields'
            )
        ),
		'email' => array(
			'rule' => 'email',
			'required' => true,
			'message' => 'Please enter a valid email address'
		)
    );

    /**
     * Check if user`s login/password matches our records
     *
     * @param string $login
     * @param string $password
     * @return array
     */
    function authenticate($login, $password) {
        $login = Sanitize::escape($login);
        $password = sha1($password);
        $this->recursive = -1;
        return $this->findByLoginAndPassword($login, $password);
    }

    /**
     * Before data validation callback
     *
     * @return bool true
     */
    function confirmPassword() {
        if ($this->data[$this->name]['confirm_password'] !== $this->data[$this->name]['password']) {
            return false;
        }

        return true;
    }

    /**
     * Hash password before save
     *
     * @return bool true
     */
    function beforeSave() {
        if (isset($this->data[$this->name]['password'])) {
            $this->data[$this->name]['password'] = sha1($this->data[$this->name]['password']);
        }
        return true;
    }

}
