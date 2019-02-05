<?php

class InstallUserForm extends Form {
    public $username;
    public $password;
    public $email;
    
    public function rules() {
        return [
            ['username, password', 'required']
        ];
    }
    
    public function getForm() {
        return array (
            'title' => 'Plansys Installer - Database Information',
            'layout' => array (
                'name' => 'full-width',
                'data' => array (
                    'col1' => array (
                        'type' => 'mainform',
                        'size' => '100',
                    ),
                ),
            ),
            'inlineJS' => '',
        );
    }

    public function getFields() {
                            
        return array (
            array (
                'type' => 'Text',
                'value' => '<style>
    .top-bar {
        display: none;
    }
</style>',
            ),
            array (
                'type' => 'Text',
                'value' => '<div class=\"install-pane\" style=\"width:100%;\">
    <div class=\"middle\">
        <div class=\"inner\">
    <div class=\"install-pane-head\">
        <img src=\"<?= Yii::app()->controller->staticUrl(\"/img/logo.png\"); ?>\" alt=\"Logo Plansys\" width=\"300px\">
    </div>
    ',
            ),
            array (
                'totalColumns' => '3',
                'column2' => array (
                    array (
                        'type' => 'Text',
                        'value' => '<div ng-if=\"!params.error\" style=\"margin-top:15px;\" class=\"alert alert-info\"><?= Setting::t(\"Please enter new developer account information\") ?></div>
    
    <div ng-if=\"params.error\" style=\"margin-top:15px;\" class=\"alert alert-danger\">{{params.error}}</div>',
                    ),
                    array (
                        'name' => 'username',
                        'layout' => 'Vertical',
                        'labelWidth' => '0',
                        'fieldWidth' => '12',
                        'fieldOptions' => array (
                            'placeholder' => 'username',
                        ),
                        'type' => 'TextField',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<br>',
                    ),
                    array (
                        'name' => 'email',
                        'labelWidth' => '0',
                        'fieldWidth' => '12',
                        'fieldOptions' => array (
                            'placeholder' => 'email',
                        ),
                        'type' => 'TextField',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<br>',
                    ),
                    array (
                        'name' => 'password',
                        'fieldType' => 'password',
                        'layout' => 'Vertical',
                        'fieldWidth' => '12',
                        'fieldOptions' => array (
                            'placeholder' => 'password',
                        ),
                        'type' => 'TextField',
                    ),
                    array (
                      'type' => 'Text',
                      'value' => '<br/>',
                    ),
                    array (
                        'label' => 'Finish Installation',
                        'buttonType' => 'success',
                        'buttonSize' => '',
                        'type' => 'SubmitButton',
                    ),
                    '<column-placeholder></column-placeholder>',
                ),
                'w1' => '35%',
                'w2' => '30%',
                'w3' => '35%',
                'type' => 'ColumnField',
            ),
            array (
                'type' => 'Text',
                'value' => '<br/>',
            ),
            array (
                'type' => 'Text',
                'value' => '        </div>
    </div>
</div>',
            ),
        );
    }

}