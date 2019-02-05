<?php

class SysChangePass extends Form {

    public function getForm() {
        return array (
            'title' => 'Change Pass',
            'layout' => array (
                'name' => 'full-width',
                'data' => array (
                    'col1' => array (
                        'type' => 'mainform',
                    ),
                ),
            ),
        );
    }

    public function getFields() {
        return array (
            array (
                'linkBar' => array (
                    array (
                        'label' => 'Save',
                        'buttonType' => 'success',
                        'type' => 'LinkButton',
                        'options' => array (
                            'ng-click' => 'form.submit(this)',
                        ),
                        'icon' => 'save',
                    ),
                ),
                'title' => 'Change Password',
                'showSectionTab' => 'No',
                'type' => 'ActionBar',
            ),
            array (
                'column1' => array (
                    array (
                        'label' => 'New Password',
                        'name' => 'NewPassword',
                        'fieldType' => 'password',
                        'type' => 'TextField',
                    ),
                    array (
                        'label' => 'Retype Password',
                        'name' => 'RetypePassword',
                        'fieldType' => 'password',
                        'type' => 'TextField',
                    ),
                    '<column-placeholder></column-placeholder>',
                ),
                'w1' => '50%',
                'w2' => '50%',
                'type' => 'ColumnField',
            ),
        );
    }

}