<?php

class DefaultController extends Controller {

    public function beforeAction($action) {
        if (!in_array(Setting::$mode, ["install", "init"])) {
            $this->redirect(['/site/login']);
            die();
        }

        parent::beforeAction($action);

        $cs = Yii::app()->getClientScript();
        $root = Yii::app()->basePath;
        $path = str_replace([$root, "\\"], ["", "/"], $this->getViewPath());

        if (Setting::$mode != "init") {
            $path = "/plansys" . $path;
        }
        $cs->registerCssFile(Yii::app()->baseUrl . $path . '/install.css');

        return true;
    }

    public function getModuleUrl() {
        return str_replace("static", "modules/install/views/default/", $this->staticUrl(""));
    }

    public function actionIndex($msg = null) { 
        $base = explode("/", Yii::app()->baseUrl);
        if (array_pop($base) == "plansys") {
            echo "<a style='font-size: 20px;marign:0px auto;' href='" . implode("/", $base) . "'>Continue installation</a>";
            die();
        } else {
            $content = $this->renderPartial('index', ['msg' => $msg], true);
            $html = $this->renderPartial('_layout', ['content' => $content], true);
            echo $html;
        }
    }

    public function actionFinish() {
        if (isset($_GET['s'])) {
            echo Setting::$mode;
            die();
        }

        $this->renderForm('InstallFinishForm');
    }

    public function actionUser() {
        
        $model = new InstallUserForm;
        $error = false;
        $success = 'n';
        
        if (isset($_POST['InstallUserForm'])) {
            $model->attributes = $_POST['InstallUserForm'];            
            if ($model->validate()) {
                $user = User::model()->find();
                if (!is_null($user)) {
                    $user->username = $model->username;
                    $user->email = $model->email;
                    $user->password = Helper::hash($model->password);
                    $user->is_deleted = 0;
                    $status = $user->update(['username', 'password', 'is_deleted', 'email']);
                } else {
                    vdump($user);
                    die();
                }                
                Installer::createIndexFile("running");
                $this->redirect(['/install/default/finish']);
            }
        }
        
        $this->renderForm('InstallUserForm', $model, ['error' => $error, 'success' => $success]);
    }

    public function actionResetdb() {
        Installer::resetDB();
        $this->redirect(['/install/default/user']);
    }

    public function actionDb() {
        $model = new InstallDbForm();
        $model->driver = Setting::get('db.driver');

        $error = false;
        $mode = "init";

        if (isset($_POST['InstallDbForm'])) {
            $model->attributes = $_POST['InstallDbForm'];

            if ($model->validate()) {
                $error = false;
                        
                try {
                    switch ($model->driver) {
                        case "mysql":
                            $dbh = new pdo("mysql:host={$model->host};dbname={$model->dbname}", 
                                $model->username, $model->password, array(
                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            ));
                        break;
                        case "oci":
                            $dbh = new pdo("oci:dbname={$model->host}/{$model->dbname}", 
                                $model->username, $model->password, array(
                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            ));
                        break;
                    }
                } catch (PDOException $ex) {
                    $error = $ex->getMessage();
                }

                if (!$error) {
                    Setting::set('db.driver', $model->driver, false);
                    Setting::set('db.host', $model->host, false);
                    Setting::set('db.username', $model->username, false);
                    Setting::set('db.password', $model->password, false);
                    Setting::set('db.dbname', $model->dbname, false);
                    Setting::write();

                    if ($model->resetdb == "yes") {
                        Installer::createIndexFile("install");
                        $this->redirect(['/install/default/resetdb']);
                    } else {
                        Installer::createIndexFile("running");
                        $this->redirect(['/install/default/finish']);
                    }
                }
            }
        }


        $this->renderForm('InstallDbForm', $model, ['error' => $error, 'mode' => $mode]);
    }

}
