<?php

class JasperCommand extends Service {
    public function actionIndex() {       
        $d = DIRECTORY_SEPARATOR;
        $jasper  = new \JasperPHP\JasperPHP(Setting::getAppPath().$d."reports"); 
        $params = $this->params;
        
        $jrxmlfile = $jasperfile = Setting::getAppPath().$d."reports".$d. $params['_name'] . ".jrxml";
        if ($jrxmlfile === "") {
            
        }
        
        $jasperfile = Setting::getAppPath().$d."reports".$d. $params['_name'] . ".jasper";
        $result = Setting::getAssetPath().$d."reports".$d. "pdf".$d.$params['_name'].$d.$params['_rid'];
        $resultdir = dirname($result);
        if (!is_dir($resultdir)) {
            mkdir($resultdir, 0777, true);
        }
        
        unset($params['_rid']);
        unset($params['_name']);
        
        foreach ($params as $k=>$v) {
            $params[$k] = '"' . str_replace('"', "'", $v) . '"';
        }
        
        $db = Setting::get("db");
        
        $driver = $db['driver'];
        if ($db['driver'] === 'pgsql') {
            $driver = 'postgres';
        }
        
        $jasper->process(
            $jasperfile,
            $result,
        	array('pdf'),
        	$params,
        	[
        	    'driver' => $driver,
        	    'username' => $db['username'],
        	    'password' => $db['password'],
        	    'database' => $db['dbname'],
        	    'host'     => $db['host']
        	],
        	false,
        	true
        )->execute();
        
        echo $jasper->output();
    }
}