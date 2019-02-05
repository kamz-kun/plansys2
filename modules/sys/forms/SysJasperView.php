<?php

class SysJasperView extends Form {

    public function getForm() {
        return array (
            'title' => 'Jasper View',
            'layout' => array (
                'name' => 'full-width',
                'data' => array (
                    'col1' => array (
                        'type' => 'mainform',
                        'size' => '100',
                    ),
                ),
            ),
            'inlineJS' => 'view.js',
        );
    }

    public function getFields() {
        return array (
            array (
                'type' => 'Text',
                'value' => '<div class=\"panel panel-default\" ng-if=\"!pdf\"
    style=\"width:500px;margin:40px auto;\">
    <div class=\"panel-body\" style=\"text-align:center;\">
        <i 
        class=\"fa fa-refresh fa-spin fa-3x\">
        </i>
        <br/>
        <h4 style=\"
        margin:10px 0px 0px 0px;
        line-height:25px;
        \">
            Generating Report: {{ params.name }}
        </h4>
        
        <code ng-click=\"toggleParams()\" 
              style=\"font-size:12px;display:block;text-align:left;cursor:pointer;margin-top:15px;\">Report ID: {{ params.prm._rid }} (View Params)</code>
    </div>
</div>
<div id=\"the-canvas\" style=\"position:absolute;top:0;left:0;right:0;bottom:0;text-align:center;\"></div>

<a href=\"{{params.pdfurl}}\" class=\"btn btn-success btn-sm\"
    ng-if=\"pdf\" target=\"_blank\" style=\"position:fixed;right:30px;top:50px;z-index:999\">
    Download PDF
</a>',
            ),
        );
    }

}