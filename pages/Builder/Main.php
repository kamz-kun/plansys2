<?php

class MainPage extends Page {
    public $store = 'builder';
    
    public function css() {
        ob_start();
        include("Main.css");
        return ob_get_clean();
    }
    
    public function render() {
        return ['div', 'asdas'];
    }
}