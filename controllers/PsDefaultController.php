<?php

class PsDefaultController extends Controller {

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        if (Yii::app()->user->isGuest) {
            $redir = '';
            if (isset($_GET['redir'])) {
                $redir = '&redir=' . $_GET['redir'];
            }
            
            $this->redirect(array("login" . $redir));
        } else {
            if (is_array(Yii::app()->user->info)) {
                foreach(Yii::app()->user->info['roles'] as $role) {
                    if ($role['role_name'] == Yii::app()->user->info['full_role']) {
                        if (!@$role['home_url']) {
                            $this->redirect(['/docs/welcome']);
                        } else {
                            $this->redirect([$role['home_url']]);
                        }
                    }
                }
            } else {
                $this->redirect(['/site/logout']);
            }
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError($id = "") {      
        if ($error = Yii::app()->errorHandler->error) {                        
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else {
                $shouldRender = false;
                $code = '';
                switch ($error['code']) {
                    case 400:
                            $code = $error['code'];
                            if (strpos($error['message'], 'CSRF') >= 0) {
                                $error        = array(
                                    'code' => 'Peringatan: Mohon refresh halaman ini',
                                    'message' => 'Token CSRF Anda sudah kadaluarsa. Demi keamanan data <br/>
                                                  mohon muat ulang halaman ini ini.'
                                );
                                $shouldRender = true;
                            }
                        break;
                    case 403:
                        $code = $error['code'];
                        if (stripos('forbidden', $error['message']) !== false || $error['message'] === '') { 
                            if (Yii::app()->user->isGuest) {
                                if (!isset($_GET['redir'])) {
                                    $this->redirect(['/site/login',
                                        'redir' => "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
                                    ]);
                                } 
                            } else {
                                $msg = 'Anda tidak memiliki hak akses terhadap URL ini. <br/>'
                                    . 'Mohon segera alihkan tujuan anda ke halaman lain. <br/>';
                                if (isset($error['message']) && trim($error['message']) != '') {
                                    $msg = $error['message'];
                                }
    
                                $error        = array(
                                    'code' => 'Peringatan: Tidak ada akses',
                                    'message' => $msg
                                );
                                $shouldRender = true;
                            }
                        } 
                        break;
                    case 404:
                        $code = $error['code'];
                        $error        = array(
                            'code' => 'Peringatan: Data / halaman tidak ditemukan',
                            'message' => 'Data yang ingin Anda lihat tidak dapat ditemukan. <br/>'
                                . 'Mohon periksa kembali URL yang ingin Anda buka.<br/><br/>'
                                . 'Atau mungkin juga data yang ingin Anda akses sudah dihapus.'
                        );
                        $shouldRender = true;
                        break;
                    default:
                        $error        = array(
                            'code' => $error['code'] ,
                            'message' => $error['message'],
                            'file' => @$error['file'],
                            'line' => @$error['line'],
                            'trace' => @$error['trace'],
                            'traces' => @$error['traces'],
                        );
                        $shouldRender = true;
                        break;
                }
                if ($shouldRender) {
                    $this->pageTitle  = $error['code'];
                    $_GET['rendered'] = true;                    
                    $this->render('error'.$code, $error);
                }
            }
            
        } else {
            switch ($id) {
                case "integrity":
                    $error = array(
                        'code' => 'Peringatan: Integritas Data',
                        'message' => 'Anda tidak dapat menghapus data ini karena<br/> '
                            . 'data ini adalah referensi data lainnya. '
                    );
                    break;
                case "ldap_missing":
                    $error = array(
                        'code' => 'Peringatan: Login Tanpa Role',
                        'message' => 'Anda berhasil login ke sistem, '
                            . 'akan tetapi<br/>Anda belum memiliki Role pada sistem ini.'
                            . '<br/><br/>Mohon hubungi Administrator<br/> untuk mendapatkan Role pada sistem'
                    );
                    break;
            }
            if ($id != "") {
                $this->pageTitle  = $error['code'];
                $_GET['rendered'] = true;
                $this->render("error", $error);
            }
        }
        return false;
    }

    public function beforeLogin($model) {}

    public function afterLogin($model) {}

    public function beforeLogout($model) {}

    public function afterLogout($model) {}

    /**
     * Displays the login page
     */
    public function actionLogin() {
        if (isset($_GET['redir'])) {
            Yii::app()->user->returnUrl = $_GET['redir'];
        }

        if (!Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->returnUrl);
        }
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {            
            $post = $_POST['LoginForm'];
            $post['username'] = preg_replace('/[;\'"=]/', '', $post['username']);

            
            $model->attributes = $post;

            $this->beforeLogin($model);

            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $this->afterLogin($model);

                $lastLogin = DataFilter::toSQLDateTime("'" . date("Y-m-d H:i:s") . "'");
                Yii::app()->session->add('user_cache_time', $lastLogin);
                
                ## update last_login user
                Yii::app()->db->commandBuilder->createUpdateCommand("p_user", [
                    'last_login' => new CDbExpression($lastLogin)
                ], new CDbCriteria([
                    'condition' => '"id" = :p',
                    'params' => [
                        ':p' => Yii::app()->user->id
                    ]
                ]))->execute();
                
                $this->redirect(Yii::app()->user->returnUrl);
            }
        }

        // display the login form
        $this->renderForm('LoginForm', $model, [], [
            'pageTitle' => Setting::get("app.name") . " Login" , 'layout' => '/layouts/blank'
        ]);
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        if (!Yii::app()->user->isGuest) {
            $model = new LoginForm;
            $model->username = Yii::app()->user->name;
            $this->beforeLogout($model);

            $this->afterLogout($model);
        }

        $_SESSION = array();

        // unset cookies
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time()-1000);
                setcookie($name, '', time()-1000, '/');
            }
        }
        
        $this->redirect(Yii::app()->homeUrl);
    }
	
	
	public function actionShowError(){ 
		if(@class_exists('AppError')){
            $e = 'AppError';
        } else {
            $e = 'ErrorForm';
        }
		$this->renderForm($e);
	}

}
