<?php

class DocsWelcome extends Form {

    public function getForm() {
        return array (
            'title' => 'Plansys - Welcome',
            'layout' => array (
                'name' => 'full-width',
                'data' => array (
                    'col1' => array (
                        'type' => 'mainform',
                        'size' => '100',
                    ),
                ),
            ),
            'inlineJS' => 'welcome.js',
        );
    }

    public function getFields() {
        return array (
            array (
                'renderInEditor' => 'Yes',
                'type' => 'Text',
                'value' => '<style>
    
    .welcome-page {
        text-align: center;
        padding: 12% 25% 10% 25%;
    }
    
    .welcome-page h1 {
        font-size: 135px;
        font-weight: 600;
        vertical-align: middle;
        background-color: #37474F;
        color: transparent;
        text-shadow: 0px 2px 5px rgba(255,255,255,.3);
        -webkit-background-clip: text;
        -moz-background-clip: text;
        background-clip: text;
        margin-bottom: 20px;
    }
    
    @media screen and (max-width: 768px) {
        .welcome-page h1 {
            font-size: 70px
        }
        
        .welcome-page {
            text-align: center;
            padding: 12% 10% 10% 10%;
        }
        
    }
</style>',
            ),
            array (
                'renderInEditor' => 'Yes',
                'display' => 'all-line',
                'type' => 'Text',
                'value' => '<div class=\"welcome-page\">
    <img src=\"plansys/static/img/welcome.png\" width=\"450px\">
    <h4 style=\"margin-top: -40px;\">Build 2.00.00</h4>
    <hr>
    <br>
</div>
',
            ),
        );
    }

}