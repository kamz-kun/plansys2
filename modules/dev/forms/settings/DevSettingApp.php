<?php

class DevSettingApp extends Form {

    public $name = '';
    public $dir = 'app';
    public $host = 'http://localhost';
    public $mode = 'dev';
    public $debug = 'ON';
    public $restApi = 'OFF';
    public $restApiToken = null;
    public $dateFormat = 'd M Y';
    public $timeFormat = 'H:i';
    public $dateTimeFormat = 'd M Y - H:i';
    public $theme;
    public $oAuthGoogle = 'OFF';
    public $oAuthGoogleId = '';
    public $oAuthGoogleSecret = '';
    
    public $phpPath = '';
    
    public function __construct() {
        parent::__construct();
        $this->attributes = Setting::get('app');
    }
    
    public function save() {
        if ($this->attributes['mode'] == 'prod') {
            $va = $this->attributes;
            $va['debug'] = 'OFF';
            $this->attributes = $va;
        }
         
        Setting::set('app', $this->attributes);
        return true;
    }

    public function getForm() {
        return array (
            'title' => 'Application Setting',
            'layout' => array (
                'name' => '2-cols',
                'data' => array (
                    'col1' => array (
                        'size' => '200',
                        'sizetype' => 'px',
                        'type' => 'menu',
                        'name' => 'col1',
                        'file' => 'application.modules.dev.menus.Setting',
                        'icon' => 'fa-sliders',
                        'title' => 'Main Setting',
                        'menuOptions' => array (),
                    ),
                    'col2' => array (
                        'size' => '',
                        'sizetype' => '',
                        'type' => 'mainform',
                    ),
                ),
            ),
            'inlineJS' => 'DevSettingApp.js',
        );
    }

    public function getFields() {
        return array (
            array (
                'linkBar' => array (
                    array (
                        'label' => 'Save Setting',
                        'buttonType' => 'success',
                        'icon' => 'check-square',
                        'options' => array (
                            'ng-click' => 'form.submit(this)',
                        ),
                        'type' => 'LinkButton',
                    ),
                ),
                'title' => '<i class=\\"fa fa-home\\"></i> {{form.title}}',
                'showSectionTab' => 'No',
                'type' => 'ActionBar',
            ),
            array (
                'showBorder' => 'Yes',
                'column1' => array (
                    array (
                        'label' => 'Application Name',
                        'name' => 'name',
                        'type' => 'TextField',
                    ),
                    array (
                        'label' => 'Main Dir',
                        'name' => 'dir',
                        'fieldOptions' => array (
                            'disabled' => 'true',
                        ),
                        'type' => 'TextField',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<column-placeholder></column-placeholder>',
                    ),
                ),
                'column2' => array (
                    array (
                        'label' => 'Mode',
                        'name' => 'mode',
                        'list' => array (
                            'dev' => 'Development',
                            'prod' => 'Production',
                        ),
                        'type' => 'DropDownList',
                    ),
                    array (
                        'label' => 'Debug',
                        'name' => 'debug',
                        'options' => array (
                            'ng-if' => 'model.mode != \'prod\'',
                        ),
                        'type' => 'ToggleSwitch',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<column-placeholder></column-placeholder>',
                    ),
                ),
                'w1' => '50%',
                'w2' => '50%',
                'type' => 'ColumnField',
            ),
            array (
                'title' => 'Locale Setting',
                'type' => 'SectionHeader',
            ),
            array (
                'showBorder' => 'Yes',
                'column1' => array (
                    array (
                        'label' => 'Date Format',
                        'name' => 'dateFormat',
                        'postfix' => '{{ timestamp | dateFormat:model.dateFormat }}',
                        'type' => 'TextField',
                    ),
                    array (
                        'label' => 'Time Format',
                        'name' => 'timeFormat',
                        'postfix' => '{{ timestamp | dateFormat:model.timeFormat }}',
                        'type' => 'TextField',
                    ),
                    array (
                        'label' => 'Date Time Format',
                        'name' => 'dateTimeFormat',
                        'postfix' => '{{ timestamp | dateFormat:model.dateTimeFormat }}',
                        'type' => 'TextField',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<column-placeholder></column-placeholder>',
                    ),
                ),
                'column2' => array (
                    array (
                        'type' => 'Text',
                        'value' => '<column-placeholder></column-placeholder>',
                    ),
                ),
                'w1' => '50%',
                'w2' => '50%',
                'type' => 'ColumnField',
            ),
            array (
                'title' => 'Rest API',
                'type' => 'SectionHeader',
            ),
            array (
                'column1' => array (
                    array (
                        'label' => 'Enable',
                        'name' => 'restApi',
                        'type' => 'ToggleSwitch',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<column-placeholder></column-placeholder>',
                    ),
                ),
                'column2' => array (
                    array (
                        'type' => 'Text',
                        'value' => '<column-placeholder></column-placeholder>',
                    ),
                    array (
                        'label' => 'Token',
                        'name' => 'restApiToken',
                        'type' => 'TextField',
                    ),
                ),
                'w1' => '50%',
                'w2' => '50%',
                'type' => 'ColumnField',
            ),
            array (
                'title' => 'Google OAuth',
                'type' => 'SectionHeader',
            ),
            array (
                'column1' => array (
                    array (
                        'label' => 'Enable',
                        'name' => 'oAuthGoogle',
                        'type' => 'ToggleSwitch',
                    ),
                    array (
                        'renderInEditor' => 'Yes',
                        'type' => 'Text',
                        'value' => '<div class=\"col-md-4\"></div>
<div class=\"col-md-8\">
    <small>
        <i class=\"fa fa-info-circle \"></i> Open <a href=\"https://console.developers.google.com\" target=\"_blank\">Google Developer Console</a> to get <b>Credential</b>. Click <a href=\"https://developers.google.com/youtube/analytics/registering_an_application\"  target=\"_blank\">here</a> for more info. 
    </small>
</div>',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<column-placeholder></column-placeholder>',
                    ),
                ),
                'column2' => array (
                    array (
                        'label' => 'Client ID',
                        'name' => 'oAuthGoogleId',
                        'options' => array (
                            'ng-if' => 'model.oAuthGoogle == \'ON\'',
                        ),
                        'type' => 'TextField',
                    ),
                    array (
                        'label' => 'Client Key',
                        'name' => 'oAuthGoogleSecret',
                        'options' => array (
                            'ng-if' => 'model.oAuthGoogle == \'ON\'',
                        ),
                        'type' => 'TextField',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<column-placeholder></column-placeholder>',
                    ),
                ),
                'w1' => '50%',
                'w2' => '50%',
                'type' => 'ColumnField',
            ),
            array (
                'title' => 'System Setting',
                'type' => 'SectionHeader',
            ),
            array (
                'column1' => array (
                    array (
                        'label' => 'Config Dir',
                        'js' => 'model.config_dir',
                        'type' => 'LabelField',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<div class=\"col-md-4\"></div>
<div class=\"col-md-8\">
    <small>
        <i class=\"fa fa-info-circle \"></i>
        You can change this for security purpose
    </small>
</div>
',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<column-placeholder></column-placeholder>',
                    ),
                ),
                'column2' => array (
                    array (
                        'type' => 'Text',
                        'value' => '<column-placeholder></column-placeholder>',
                    ),
                    array (
                        'label' => 'PHP CLI Path',
                        'name' => 'phpPath',
                        'fieldOptions' => array (
                            'placeholder' => 'ex: /usr/bin/php -or- c:\\\\xampp\\\\php\\\\php.exe',
                        ),
                        'type' => 'TextArea',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<div class=\"col-md-4\"></div>
<div class=\"col-md-8\">
    <small>
        <i class=\"fa fa-info-circle \"></i>
        if not filled, plansys will search php binary in the environment variable
    </small>
</div>',
                    ),
                ),
                'w1' => '50%',
                'w2' => '50%',
                'type' => 'ColumnField',
            ),
        );
    }

}