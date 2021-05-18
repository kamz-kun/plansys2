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
    <h4 style=\"margin-top: -40px;\">Build 1.13.00</h4>
    <hr>
    <div class=\"row\" style=\"margin-top: 10px;\">
        <div class=\"col-sm-12 col-md-3\">
            CPU : {{cpu}}%
        </div>
        <div class=\"col-sm-12 col-md-3\">
            RAM : {{mem}}%
        </div>
        <div class=\"col-sm-12 col-md-3\">
            HDD : {{hdd}}
        </div>
        <div class=\"col-sm-12 col-md-3\">
            PHP : {{php}}
        </div>
    </div>
    <br>
    <div class=\"row\" style=\"margin-top: 10px;\">
        <h3><small>leader</small> <br>
            <a href=\"mailto:kamil@andromedia.co.id\">Kamz</a>
        </h3>
        <h3><small>main dev.</small> <br>
            <a href=\"mailto:jessica@andromedia.co.id\">Jesz</a>, <a href=\"mailto:arbi@andromedia.co.id\">Arbz</a>, <a href=\"mailto:hansel.sindu@andromedia.co.id\">Hanz</a>
        </h3>
        <h3><small>initiator</small> <br>
            Riz
        </h3>
    </div>
    
    
    
</div>
',
            ),
        );
    }

}