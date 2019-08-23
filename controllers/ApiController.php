<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// Yii::import('app.controllers.*');

class ApiController extends CController {
    
    public function queryDB($query, $param){
        $sql = Yii::app()->db->createCommand($query);
        if($param=='all'):
            return json_encode($sql->queryAll());
        elseif($param=='row'):
            return json_encode($sql->queryRow());
        endif;
    }
    
    public function jsonDecoder($rest){
        return json_decode($rest, true);
    }
    
    public function handleRestJson($id = null){
        $rest_json = file_get_contents("php://input");
        $post = $this->jsonDecoder($rest_json);//json_decode($rest_json, true);
        
        echo $post['col'];
    }
    
    public function actionIndex($params){
        //echo 'ini nama modelnya: <b>'.$params.'</b> & ini parameternya: <b>'.$_GET['q'].'</b>';
        //operates url request
        $enabled = Setting::get('app.restApi');
        if($enabled == 'ON'){
            $method = $_SERVER['REQUEST_METHOD']; 
            $api = str_replace('r=', "", $_SERVER['QUERY_STRING']);
            $request = explode("&", $api, 2)[0];
            $request_arr = explode('/', trim($request,'/'));
            $filter = '';
            $id = null;
            $query_param = null;
            
            //operates request id
            if(isset($request_arr[2]))
                $id = $request_arr[2];
            if(is_numeric($id)) //converts id to an int if it's a number
                $id = intval($id);
                
            //operates request query
            if(isset($_GET['q']))
                $query_param = $_GET['q'];
            
            switch($method):
                case 'GET':
                    if($id)
                        $filter = ' AND id='.$id;
                    if($query_param):
                        $query_param = $this->jsonDecoder($query_param);
                        if(is_array($query_param)):
                            foreach($query_param as $key => $item):
                                $op = $item['op'];
                                $val = $item['val'];
                                if(!is_numeric($val)) //kalau ga numeric jadiin string
                                    $val = "'$val'";
                                $filter = $filter.' AND '.$key.' '.$op.' '.$val;
                            endforeach;
                        else:
                            $res = 'non-array';
                        endif;
                    endif;
                    $query = 'SELECT * FROM '.$params.' WHERE 1=1'.$filter;
                    try {
                      $res = $this->queryDB($query, 'all');
                    }
                    catch(Exception $e) {
                      $res = 'Error: ' .$e->getMessage();
                    }
                    break;
                case 'POST':
                    $res = $this->handleRestJson();
                    break;
                case 'PUT':
                    $res = $this->handleRestJson($id);
                    break;
                case 'DELETE':
                    $res = 'delete '.$params.' nih';
                    break;
            endswitch;
            
            echo $res;    
        } else {
            echo 'Rest API is not Enabled';
        }
    }
    
}
