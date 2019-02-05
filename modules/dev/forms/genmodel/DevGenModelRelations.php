<?php

class DevGenModelRelations extends Form {

    public static function getRels($pathModel) {
        if ($pathModel != "") {
            $modelClass = end(explode(".", $pathModel));
            
            if (method_exists($modelClass, 'model')) {
                $model = $modelClass::model();
                if (is_subclass_of($model, 'ActiveRecord')) {
                    if (method_exists($model, "relations")) {
                        $rels = ($model->relations());
                        $return = [];
                        foreach ($rels as $k=>$r) {
                            $ret = [
                                'name' => $k,
                                'type' => $r[0],
                                'modelClass' => $r[1],
                                'fk' => $r[2],
                                'raw' => $r 
                            ];
                            $return[] = $ret;
                        }
                        return $return;
                    }
                    return [];
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function getForm() {
        return array (
            'title' => 'Gen Model Relations',
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
            ),
            array (
                'name' => 'dsRel',
                'fieldType' => 'php',
                'php' => 'DevGenModelRelations::getRels(@$_GET[\\"active\\"]);',
                'type' => 'DataSource',
            ),
            array (
                'type' => 'Text',
                'value' => '<div class=\"alert alert-warning\" ng-if=\"dsRel.data[0] === false\" style=\"margin-top:15px;text-align:center;\">
    This model does not have database relations
</div>',
            ),
            array (
                'showBorder' => 'Yes',
                'column1' => array (
                    array (
                        'renderInEditor' => 'Yes',
                        'type' => 'Text',
                        'value' => '<div class=\"text-center\">
    <div ng-show=\"isChanged\" class=\"btn btn-default btn-sm btn-success\"
    ng-click=\"dsRel.query();markUnchange()\"
    ><i class=\"fa fa-refresh\"></i> Load Relations</div>
</div>',
                    ),
                    array (
                        'name' => 'lvRel',
                        'templateForm' => 'application.modules.dev.forms.genmodel.DevGenModelRelForm',
                        'datasource' => 'dsRel',
                        'options' => array (
                            'ng-show' => '!isChanged',
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
                        'value' => '<column-placeholder></column-placeholder>',
                    ),
                ),
                'w1' => '50%',
                'w2' => '50%',
                'options' => array (
                    'ng-if' => 'dsRel.data[0] !== false',
                ),
                'perColumnOptions' => array (
                    'style' => 'padding',
                ),
                'type' => 'ColumnField',
            ),
            array (
                'type' => 'Text',
                'value' => '<hr style=\\"margin:0px\\">',
            ),
        );
    }

}