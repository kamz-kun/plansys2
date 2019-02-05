<?php

class InstallDbForm extends Form {

    public $driver;
    public $host;
    public $username;
    public $password;
    public $dbname;
    public $resetdb = "yes";
    public $phpPath = '';

    public function rules() {
        return [
            ['host, username', 'required']
        ];
    }

    public function getForm() {
        return array(
            'title' => 'Plansys Installer - Database Information',
            'layout' => array(
                'name' => 'full-width',
                'data' => array(
                    'col1' => array(
                        'type' => 'mainform',
                        'size' => '100',
                    ),
                ),
            ),
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
                        'value' => '<div ng-if=\"!params.error\" style=\"margin-top:-15px;\" class=\"alert alert-info\"><?= Setting::t(\"Enter your database server information \") ?></div>
    
    <div ng-if=\"params.error\" style=\"margin-top:15px;\" class=\"alert alert-danger\">{{params.error}}</div>',
                    ),
                    array (
                        'name' => 'driver',
                        'list' => array (
                            'mysql' => 'MySQL',
                            'pgsql' => 'PostgreSQL',
                        ),
                        'layout' => 'Vertical',
                        'labelWidth' => '0',
                        'fieldWidth' => '12',
                        'type' => 'DropDownList',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<br>',
                    ),
                    array (
                        'name' => 'host',
                        'layout' => 'Vertical',
                        'labelWidth' => '0',
                        'fieldWidth' => '12',
                        'fieldOptions' => array (
                            'placeholder' => 'host',
                        ),
                        'type' => 'TextField',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<br>',
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
                        'name' => 'password',
                        'fieldType' => 'password',
                        'layout' => 'Vertical',
                        'labelWidth' => '0',
                        'fieldWidth' => '12',
                        'fieldOptions' => array (
                            'placeholder' => 'password',
                        ),
                        'type' => 'TextField',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<br>',
                    ),
                    array (
                        'name' => 'dbname',
                        'layout' => 'Vertical',
                        'labelWidth' => '0',
                        'fieldWidth' => '12',
                        'fieldOptions' => array (
                            'placeholder' => 'database name',
                        ),
                        'type' => 'TextField',
                    ),
                    array (
                        'name' => 'resetdb',
                        'list' => array (
                            'yes' => 'Create Plansys table',
                        ),
                        'labelWidth' => '0',
                        'options' => array (
                            'style' => 'color: white;',
                        ),
                        'labelOptions' => array (
                            'style' => 'text-align:left;',
                        ),
                        'type' => 'CheckboxList',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<div class=\"info text-left\" style=\"margin:-20px 0px 0px -3px; color: white;\">
    only tables with prefix p_ (e.g. p_user, p_role, etc)<br/> that will be created.
</div>

<br/>',
                    ),
                    array (
                        'label' => 'Next Step',
                        'buttonType' => 'success',
                        'buttonSize' => '',
                        'type' => 'SubmitButton',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<column-placeholder></column-placeholder>',
                    ),
                ),
                'w1' => '35%',
                'w2' => '30%',
                'w3' => '35%',
                'type' => 'ColumnField',
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