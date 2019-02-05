<?php

class SysJasperParams extends Form {

    public function getForm() {
        return array (
            'title' => 'Jasper Params',
            'layout' => array (
                'name' => 'full-width',
                'data' => array (
                    'col1' => array (
                        'type' => 'mainform',
                        'size' => '100',
                    ),
                ),
            ),
            'inlineJS' => 'jasperparams.js',
        );
    }

    public function getFields() {
        return array (
        );
    }

}