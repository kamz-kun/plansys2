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
        $model = new SysChangePass();
        if(isset($_POST['SysChangePass'])){
            $post = $_POST['SysChangePass'];
            if($post['NewPassword'] != $post['RetypePassword']){
                $model->addErrors(['RetypePassword' => 'Password Does Not Match']);
            } else {
                $user = User::model()->findByPk(Yii::app()->user->info['id']);
                $user->password = Helper::hash($_POST['SysChangePass']['NewPassword']);
                $user->save();
                $this->flash('Successfully Saved');    
            }
            
        }
        $this->renderForm('SysChangePass', $model);
    }

}