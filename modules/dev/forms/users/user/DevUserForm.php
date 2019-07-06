<?php

class DevUserForm extends User {

    public $changePassword = '';
    public $repeatPassword = '';
    public function getFields() {
        return array (
            array (
                'linkBar' => array (
                    array (
                        'label' => 'Back',
                        'url' => '/dev/user/index',
                        'icon' => 'chevron-left',
                        'options' => array (
                            'ng-show' => 'module == \'dev\'',
                            'href' => 'url:/dev/user/{backUrl}',
                        ),
                        'type' => 'LinkButton',
                    ),
                    array (
                        'label' => 'Save',
                        'buttonType' => 'success',
                        'icon' => 'save',
                        'options' => array (
                            'ng-click' => 'form.submit(this)',
                        ),
                        'type' => 'LinkButton',
                    ),
                    array (
                        'renderInEditor' => 'Yes',
                        'type' => 'Text',
                        'value' => '<div ng-if=\\"!isNewRecord && module == \'dev\'\\" class=\\"separator\\"></div>',
                    ),
                    array (
                        'label' => 'Delete',
                        'buttonType' => 'danger',
                        'icon' => 'trash',
                        'options' => array (
                            'href' => 'url:/dev/user/del?id={model.id}',
                            'ng-if' => '!isNewRecord && module == \'dev\'',
                            'prompt' => 'Ketik \'DELETE\' (tanpa kutip) untuk menghapus user ini',
                            'prompt-if' => 'DELETE',
                        ),
                        'type' => 'LinkButton',
                    ),
                ),
                'title' => '{{ form.title}}',
                'type' => 'ActionBar',
            ),
            array (
                'name' => 'id',
                'type' => 'HiddenField',
            ),
            array (
                'showBorder' => 'Yes',
                'column1' => array (
                    array (
                        'type' => 'Text',
                        'value' => '<div ng-show=\\"module == \'dev\'\\">',
                    ),
                    array (
                        'label' => 'Username',
                        'name' => 'username',
                        'type' => 'TextField',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<div class=\"form-group form-group-sm\">   
    <label class=\"control-label col-sm-4\"> 
        User Role(s)
    </label>
    <div class=\"col-sm-8\">',
                    ),
                    array (
                        'name' => 'userRoles',
                        'fieldTemplate' => 'form',
                        'templateForm' => 'application.modules.dev.forms.users.user.DevUserRoleList',
                        'options' => array (
                            'ng-change' => 'updateRole()',
                        ),
                        'singleViewOption' => array (
                            'name' => 'val',
                            'fieldType' => 'text',
                            'labelWidth' => 0,
                            'fieldWidth' => 12,
                            'fieldOptions' => array (
                                'ng-delay' => 500,
                            ),
                        ),
                        'type' => 'ListView',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '    </div>
</div>
<div class=\"clearfix\"></div>',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<div style=\"float:right;margin:-25px 0px 0px 0px;padding:0px;text-align:right;color:#999;font-size:12px;\">
      <i class=\"fa fa-info-circle\"></i> 
     Geser role ke atas 
         untuk menjadikan default
</div>',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '</div>
<div ng-if=\"module != \'dev\'\">',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '    <div class=\"form-group form-group-sm\">
        <label 
        class=\"col-sm-4 control-label\">
        Username 
        </label>
        <div class=\"col-sm-6\" 
           style=\"padding-top:5px;\">
           {{ model.username }}
        </div>
    </div>',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '    <div class=\"form-group form-group-sm\">
        <label 
        class=\"col-sm-4 control-label\">
        </label>
        <div class=\"col-sm-8\" 
           style=\"padding-top:10px;\">
            
           <table class=\"table\" style=\"font-size:12px;border:1px solid #ccc;\">
               <tr>
                   <th style=\"padding:2px 5px 0px 5px;background:#ececeb;\">Role</th>
               </tr>
               <tr ng-repeat=\"ur in model.roles\">
                   <td style=\"padding:2px 5px 0px 5px;\">{{ ur.role_description }}</td>
               </tr>
           </table>
        </div>
    </div>',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '
</div>',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<column-placeholder></column-placeholder>',
                    ),
                ),
                'column2' => array (
                    array (
                        'label' => 'Email',
                        'name' => 'email',
                        'labelWidth' => '2',
                        'type' => 'TextField',
                    ),
                    array (
                        'label' => 'Last Login',
                        'name' => 'last_login',
                        'labelWidth' => '2',
                        'type' => 'LabelField',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<div ng-if=\\"module == \'dev\' && !isNewRecord\\">',
                    ),
                    array (
                        'label' => 'LDAP User',
                        'js' => '\'Yes - Synced\'',
                        'labelWidth' => '2',
                        'options' => array (
                            'ng-if' => 'model.useLdap && model.password == \'\'',
                        ),
                        'type' => 'LabelField',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '</div>',
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
                'type' => 'Text',
                'value' => '<div ng-if=\"!params.ldap && (!model.useLdap || (model.useLdap && model.password != \'\'))\">
',
            ),
            array (
                'title' => '{{ isNewRecord ? \\"\\" : \\"Update \\"}} Password',
                'type' => 'SectionHeader',
            ),
            array (
                'showBorder' => 'Yes',
                'column1' => array (
                    array (
                        'label' => 'Password',
                        'name' => 'changePassword',
                        'fieldType' => 'password',
                        'fieldOptions' => array (
                            'autocomplete' => 'off',
                        ),
                        'type' => 'TextField',
                    ),
                    array (
                        'label' => 'Repeat Password',
                        'name' => 'repeatPassword',
                        'fieldType' => 'password',
                        'fieldOptions' => array (
                            'autocomplete' => 'off',
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
                        'type' => 'Text',
                        'value' => '<column-placeholder></column-placeholder>',
                    ),
                    array (
                        'type' => 'Text',
                        'value' => '<div class=\"info\" ng-if=\"!isNewRecord\"><i class=\"fa fa-info-circle fa-nm fa-fw\"></i>&nbsp;Leave this field for keep old password
</div>',
                    ),
                ),
                'w1' => '50%',
                'w2' => '50%',
                'type' => 'ColumnField',
            ),
            array (
                'type' => 'Text',
                'value' => '</div>',
            ),
        );
    }

    public function rules() {
        $rules = array(
            array('changePassword, repeatPassword', 'editPassword')
        );

        return array_merge($rules, parent::rules());
    }

    public function editPassword() {
        if ($this->useLdap) return true;
        
        if ($this->changePassword != '' && $this->repeatPassword != $this->changePassword) {
            $this->addError('changePassword', 'Password tidak cocok.');
            $this->addError('repeatPassword', 'Password tidak cocok.');
        }
        
        if ($this->isNewRecord && $this->changePassword == '') {
            $this->addError('changePassword', 'Password harus diisi.');
        }
        
        if (count($this->errors) == 0 && $this->changePassword != '') {
            $this->password = Helper::hash($this->changePassword);
        }
    }

    public function getForm() {
        return array (
            'title' => 'Detail User',
            'layout' => array (
                'name' => 'full-width',
                'data' => array (
                    'col1' => array (
                        'type' => 'mainform',
                        'size' => '100',
                    ),
                ),
            ),
            'inlineJS' => 'js/form.js',
            'options' => array (
                'autocomplete' => 'off',
            ),
        );
    }

    public function beforeSave() {
        parent::beforeSave();

        $p = $this->getAttributes();
        $p['userRoles'] = Helper::uniqueArray($p['userRoles'], 'role_id');
        
        foreach ($p['userRoles'] as $k => $v) {
            if ($k == 0) {
                $p['userRoles'][$k]['is_default_role'] = 'Yes';
            } else {
                $p['userRoles'][$k]['is_default_role'] = 'No';
            }
        }
        return true;
    }

}