<?php

class ChangePassController extends Controller {
    public function filters() {
        // Use access control filter
        return ['accessControl'];
    }
    
    public function accessRules() {
        // Only allow authenticated users
        return [['allow', 'users' => ['@']], ['deny']];
    }
    
    public function actionIndex(){
        if(@class_exists('AppChangePass')){
            $model = new AppChangePass();   
            $c = 'AppChangePass';
        } else {
            $model = new SysChangePass();
            $c = 'SysChangePass';
        }
        if(isset($_POST[$c])){
            $post = $_POST[$c];
            if($post['NewPassword'] != $post['RetypePassword']){
                $model->addErrors(['RetypePassword' => 'Password Does Not Match']);
            } else {
                $user = User::model()->findByPk(Yii::app()->user->info['id']);
                $user->password = Helper::hash($_POST[$c]['NewPassword']);
                $user->save();
                $this->flash('Successfully Saved');    
            }
            
        }
        $this->renderForm($c, $model);
    }

}