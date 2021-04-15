<?php

class WebModule extends CWebModule {

    public function accessControl($controller, $action) {
        
    }

    public function beforeControllerAction($controller, $action) {
        $user = User::model()->findByPk(Yii::app()->user->id);
        if($user['is_deleted']){
            Yii::app()->user->logout();
        }
        parent::beforeControllerAction($controller, $action);
        $this->accessControl($controller, $action);
        return true;
    }

}
