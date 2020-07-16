<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class ErrorForm extends Form
{
	public $username;
	public $password;
    public $rememberMe = true;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
    public function getForm() {
        return array (
            'title' => 'LoginForm',
            'layout' => array (
                'name' => 'full-width',
                'data' => array (
                    'col1' => array (
                        'type' => 'mainform',
                        'size' => '100',
                    ),
                ),
            ),
            'options' => array (
                'class' => 'login-container',
            ),
        );
    }

    public function getFields() {
        return array (
            array (
                'type' => 'Text',
                'value' => '<link type=\"text/css\" rel=\"stylesheet\" href=\"plansys/themes/default/views/site/id/style.css\" />
<style>
    #notfound {
        position: relative;
        height: 90vh !important;
    }
</style>',
            ),
            array (
                'type' => 'Text',
                'value' => '<div id=\"notfound\">
		<div class=\"notfound\">
			<div class=\"notfound-404\">
				<h1>:(</h1>
			</div>
            <h2 style=\"color: red;\">Terjadi Kesalahan</h2>
            <h2><small>Hal ini Sudah di laporkan oleh sistem</small></h2>
			<a ng-url=\"/site\">home page</a>
			<br>
			<br>
            <i>Plansys 2.0</i>
		</div>
	</div>',
            ),
        );
    }

	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate()){
			 //   if(!$this->authenticateHris()){
			        $this->addError('password','Incorrect username or password.');
			 //   }
			}
        }
	}

    public function authenticateHris() {
        $record = HrisUser::model()->findByAttributes(['username' => $this->username]);
        
        $user = new User;
        $user->attributes = $record->attributes;
        $user->save();
        
        $user_role = new UserRole;
        $user_role->user_id = $record->id;
        $user_role->role_id = 4;
        $user_role->is_default_role = "Yes";
        $user_role->save();
        // $useLdap = false;
        // if (!is_null($record) && $record->password == '' && Yii::app()->user->useLdap) {
        //     $useLdap = true;
        //     $ldapSuccess = Yii::app()->ldap->authenticate($this->username, $this->password);
        //     if ($ldapSuccess) {
        //         $this->loggedIn($record);
        //         return true;
        //     }
        // }

        // if ($record === null) {
        //     $this->errorCode = self::ERROR_USERNAME_INVALID;
        // }
        // else if (!password_verify($this->password, $record->password)) {
        //     if ($useLdap) {
        //         $ldapSuccess = Yii::app()->ldap->authenticate($this->username, $this->password);
        //         if ($ldapSuccess) {
        //             $this->loggedIn($record);
        //             return true;
        //         } else {
        //             $this->errorCode = self::ERROR_PASSWORD_INVALID;
        //         }
        //     } else {
        //         $this->errorCode = self::ERROR_PASSWORD_INVALID;
        //     }
        // } else {
        //     $this->loggedIn($record);
        // }
        // return !$this->errorCode;
    }
	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
        }
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
            Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}