<?php

class JasperController extends Controller 
{
    public function actionView($params = [], $ignoreGet = false)
    {
        Asset::registerJS('application.modules.sys.forms.pdfjs.build.pdf');
        
        if (!isset($_GET['name'])) {
            throw new CHttpException("Please provide report name");
        }
        
        $get = $_GET;
        if (isset($get['r'])) {
            unset($get['r']);
        }
        if (isset($get['name'])) {
            $get['_name'] = $get['name'];
            unset($get['name']);
        }
        $input = file_get_contents("php://input");
        if (!is_array($input)) $input = [];
        
        
        if (!is_array($params)) {
            $params = [];
        }
        
        if ($ignoreGet) {
            $get = [
                '_name' => $get['_name']
            ];
        }
        
        if (isset($_POST[Yii::app()->request->csrfTokenName])) {
            unset($_POST[Yii::app()->request->csrfTokenName]);
        }
        
        $resparams = array_merge($get, $_POST, $input, $params, [
            '_rid' => str_replace(".", "", uniqid("", true) . "")
        ]);
        
        $parseparams = false;
        foreach ($resparams as $k => $v) {
            $v = trim($v);
            if (strpos($v, "[[") !== false) {
                if (!isset($_POST[$k])) {
                    $parseparams = true; 
                } else {
                    $resparams[$k] = $_POST[$k];
                }
            }
        }
        
        $pdfurl = Yii::app()->baseUrl . "/assets/reports/pdf/".$resparams['_name']. '/'.$resparams['_rid'] . ".pdf";
        if ($parseparams) {
            $this->renderForm("SysJasperParams", [
                'pdfurl' => $pdfurl,
                'prm' => $resparams,
                'csrf' => Yii::app()->request->getCsrfToken(),
                'csrfname' => Yii::app()->request->csrfTokenName
            ]);
            return;
        }
        ServiceManager::run("Jasper", $resparams);
        
        $this->renderForm("SysJasperView", [
            'pdfurl' => $pdfurl,
            'prm' => $resparams
        ]);
    }
    
    public function actionCheck($name, $rid) {
        $d = DIRECTORY_SEPARATOR;
        $result = Setting::getAssetPath().$d."reports".$d. "pdf".$d.$name.$d.$rid.".pdf";
        if (is_file($result)) echo "ok";
    }
    
    public function actionDownload($rid) {
        
    }
}