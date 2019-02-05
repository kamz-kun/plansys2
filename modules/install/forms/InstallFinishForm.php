<?php

class InstallFinishForm extends Form {
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
            'inlineJS' => 'finish.js',
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
    .install-pane {
        display: table;
        position: absolute;
        height: 100%;
        width: 100%;
    }
    .middle {
        display: table-cell;
        vertical-align: middle;
    }
    
    .inner {
        margin-left: auto;
        margin-right: auto; 
        width: 100%;
    }
</style>',
            ),
            array (
                'type' => 'Text',
                'value' => '<div class=\"install-pane\" style=\"width:100%\">
    <div class=\"middle\">
        
        <div class=\"inner\">
            <div class=\"install-pane-head\">
                <img src=\"<?= Yii::app()->controller->staticUrl(\"/img/logo.png\"); ?>\" alt=\"Logo Plansys\" width=\"300px;\"/>
            </div>
    ',
            ),
            array (
                'renderInEditor' => 'Yes',
                'type' => 'Text',
                'value' => '


<center>

<h3 style=\"color: white;\">Finalizing Installation</h3>
<div class=\"progress\" style=\"width:300px;margin:10px auto;\">
  <div class=\"progress-bar progress-bar-success progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: 100%;\">
    
  </div>
</div>

<div class=\"info\">
    You will be redirected when it\'s finished
</div>
</center>',
            ),
            array (
                'type' => 'Text',
                'value' => '        </div>
    </div>',
            ),
            array (
                'type' => 'Text',
                'value' => '</div>',
            ),
        );
    }

}