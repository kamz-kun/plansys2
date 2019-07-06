<?php

class SettingController extends Controller {
    
    public function actionIndex() {
        $this->redirect(["app"]);
    }
    
    public function actionApp() {
        $model = new DevSettingApp;
        $config_dir = Setting::$path;
        $model->config_dir = rtrim($config_dir, 'settings.json' );
        
        if (isset($_POST['DevSettingApp'])) {
            $model->attributes = $_POST['DevSettingApp'];
            $model->save();
            $this->flash('Application Setting Updated!');
        }
        $this->renderForm('DevSettingApp', $model);
    }
    
    public function actionTheme() {
        $model = new DevSettingTheme;
        
        if (isset($_GET['id'])) {
            $model->active = $_GET['id'] === '' ? null : $_GET['id'];
            $model->save();
            $this->flash('Theme Updated!');
            $this->redirect(['/dev/setting/theme']);
        }
        
        $cssPath = Setting::getRootPath() . '/app/static/custom.css';
        $staticPath = Setting::getRootPath() . '/app/static';
        if(file_exists($cssPath)){
            $content = file_get_contents($cssPath);
        } else {
            if (!is_dir($staticPath)) {
                mkdir($staticPath);
            }
            $defaultContent = "";
            file_put_contents($cssPath, $defaultContent);
            
        }
        $content = file_get_contents($cssPath);
        Asset::registerJS('application.static.js.lib.ace');
        $model->css_content = $content;
        $this->renderForm('DevSettingTheme', $model);
    }
    
    public function actionSaveCustomCss(){
        
        $postdata = file_get_contents("php://input");
        $post     = CJSON::decode($postdata);
        $cssPath = Setting::getRootPath() . '/app/static/custom.css';    

        if (is_file($cssPath)) {
            file_put_contents($cssPath, $post['content']);
        }
        
    }
    
    public function actionDatabase() {
        $model = new DevSettingDatabase;
        $posted = false;
        
        if (isset($_POST['DevSettingDatabase'])) {
            $model->attributes = $_POST['DevSettingDatabase'];
            if ($model->save()) {
                $posted = true;
                $this->flash('Database Setting Updated!');
            }
        }
        
        $this->renderForm('DevSettingDatabase', $model, [
            'posted' => $posted
        ]);
    }
    
    public function actionEmail() {
        $model = new DevSettingEmail;
        
        if (isset($_POST['DevSettingEmail'])) {
            $model->attributes = $_POST['DevSettingEmail'];
            $model->save();
            $this->flash('Email Setting Updated!');
        }
        
        $this->renderForm('DevSettingEmail', $model);
    }
    
    public function actionLdap() {
        $model = new DevSettingLdap;
        
        if (isset($_POST['DevSettingLdap'])) {
            $model->attributes = $_POST['DevSettingLdap'];
            $model->save();
            $this->flash('LDAP Setting Updated!');
        }
        
        $this->renderForm('DevSettingLdap', $model);
    }
    
}