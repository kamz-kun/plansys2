<?php

/**
 * Class TextField
 * @author rizky
 */
class LabelField extends FormField {

    /** @var string $toolbarName */
    public static $toolbarName = "Label Field";
    /** @var string $category */
    public static $category = "User Interface";
    /** @var string $toolbarIcon */
    public static $toolbarIcon = "fa fa-font";
    /** @var string $label */
    public $label = '';
    /** @var string $name */
    public $name = '';
    /** @var string $value */
    public $value = '';
    public $js = '';
    /** @var string $layout */
    public $layout = 'Horizontal';
    /** @var integer $labelWidth */
    public $labelWidth = 4;
    /** @var integer $fieldWidth */
    public $fieldWidth = 8;
    /** @var array $options */
    public $options = [];
    /** @var array $labelOptions */
    public $labelOptions = [];
    /** @var array $fieldOptions */
    public $fieldOptions = [];

    /**
     * @return array me-return array property TextField.
     */
    public function getFieldProperties() {
        return array(
            array(
                'label' => 'Field Name',
                'name' => 'name',
                'options' => array(
                    'ng-model' => 'active.name',
                    'ng-change' => 'changeActiveName()',
                    'ps-list' => 'modelFieldList',
                    'ng-if' => 'active.js == \'\' || !active.js',
                ),
                'searchable' => 'Yes',
                'type' => 'DropDownList',
            ),
            array(
                'label' => 'JS:',
                'name' => 'js',
                'fieldHeight' => '0',
                'options' => array(
                    'ng-model' => 'active.js',
                    'ng-change' => 'active.name=\'\';save()',
                    'ng-delay' => '500',
                ),
                'fieldOptions' => array(
                    'auto-grow' => '',
                ),
                'type' => 'TextArea',
            ),
            array(
                'label' => 'Label',
                'name' => 'label',
                'options' => array(
                    'ng-model' => 'active.label',
                    'ng-change' => 'save()',
                    'ng-delay' => '500',
                ),
                'type' => 'TextField',
            ),
            array(
                'label' => 'Layout',
                'name' => 'layout',
                'options' => array(
                    'ng-model' => 'active.layout',
                    'ng-change' => 'save();',
                ),
                'listExpr' => 'array(\'Horizontal\',\'Vertical\')',
                'fieldWidth' => '6',
                'type' => 'DropDownList',
            ),
            array(
                'column1' => array(
                    array(
                        'label' => 'Label Width',
                        'name' => 'labelWidth',
                        'layout' => 'Vertical',
                        'labelWidth' => '12',
                        'fieldWidth' => '11',
                        'options' => array(
                            'ng-model' => 'active.labelWidth',
                            'ng-change' => 'save()',
                            'ng-delay' => '500',
                            'ng-disabled' => 'active.layout == \'Vertical\'',
                        ),
                        'type' => 'TextField',
                    ),
                    array(
                        'type' => 'Text',
                        'value' => '<column-placeholder></column-placeholder>',
                    ),
                ),
                'column2' => array(
                    array(
                        'label' => 'Field Width',
                        'name' => 'fieldWidth',
                        'layout' => 'Vertical',
                        'labelWidth' => 12,
                        'fieldWidth' => '11',
                        'options' => array(
                            'ng-model' => 'active.fieldWidth',
                            'ng-change' => 'save()',
                            'ng-delay' => '500',
                        ),
                        'type' => 'TextField',
                    ),
                    array(
                        'type' => 'Text',
                        'value' => '<column-placeholder></column-placeholder>',
                    ),
                ),
                'column3' => array(
                    array(
                        'type' => 'Text',
                        'value' => '<column-placeholder></column-placeholder>',
                    ),
                ),
                'column4' => array(
                    array(
                        'type' => 'Text',
                        'value' => '<column-placeholder></column-placeholder>',
                    ),
                ),
                'w1' => '50%',
                'w2' => '50%',
                'type' => 'ColumnField',
            ),
            array(
                'type' => 'Text',
                'value' => '<hr/>',
            ),
            array(
                'label' => 'Options',
                'name' => 'options',
                'type' => 'KeyValueGrid',
            ),
            array(
                'label' => 'Label Options',
                'name' => 'labelOptions',
                'type' => 'KeyValueGrid',
            ),
            array(
                'label' => 'Field Options',
                'name' => 'fieldOptions',
                'type' => 'KeyValueGrid',
            ),
        );
    }

    /**
     * @return array me-return array javascript yang di-include
     */
    public function includeJS() {
        return ['label-field.js'];
    }

    /**
     * getLayoutClass
     * Fungsi ini akan mengecek nilai property $layout untuk menentukan nama Class Layout
     * @return string me-return string Class layout yang digunakan
     */
    public function getLayoutClass() {
        return ($this->layout == 'Vertical' ? 'form-vertical' : '');
    }

    /**
     * @return string me-return string Class error jika terdapat error pada satu atau banyak attribute.
     */
    public function getErrorClass() {
        return (count($this->errors) > 0 ? 'has-error has-feedback' : '');
    }

    /**
     * getlabelClass
     * Fungsi ini akan mengecek $layout untuk menentukan layout yang digunakan
     * dan me-load option label dari property $labelOptions
     * @return string me-return string Class label
     */
    public function getlabelClass() {
        if ($this->layout == 'Vertical') {
            $class = "control-label col-sm-12";
        } else {
            $class = "control-label col-sm-{$this->labelWidth}";
        }

        $class .= @$this->labelOptions['class'];
        return $class;
    }

    /**
     * getFieldColClass
     * Fungsi ini untuk menetukan width field
     * @return string me-return string class
     */
    public function getFieldColClass() {
        return "col-sm-" . $this->fieldWidth;
    }

    /**
     * render
     * Fungsi ini untuk me-render field dan atributnya
     * @return mixed me-return sebuah field dan atribut checkboxlist dari hasil render
     */
    public function render() {
        $this->addClass('form-group form-group-sm', 'options');
        $this->addClass($this->layoutClass, 'options');
        $this->addClass($this->errorClass, 'options');

        $this->addClass('label-field-value', 'fieldOptions');

        if ($this->js == "") {
            $this->setDefaultOption('ng-model', "model['{$this->originalName}']", $this->options);
        }

        if (!is_string($this->value))
            $this->value = json_encode($this->value);

        return $this->renderInternal('template_render.php');
    }

}