<?php
Yii::import("application.components.Authorization");

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// Yii::import('app.controllers.*');

class ApiController extends CController {
    
    protected $token_list = [];

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
    
    public function actionIndex(){ 
        $api_params = json_decode(file_get_contents("php://input"), true);        

        $method = $_SERVER['REQUEST_METHOD']; 
        if($method != 'POST'){
            die();
        }
        if(isset($api_params['model'])){
            $model = $api_params['model'];
            $table_name = $model::model()->tableName();
        }
        
        $enabled = Setting::get('app.restApi');     
        //AuthToken dari setting (nyontek Irul)

        if($enabled == 'ON'){ 
            if($api_params['mode'] == 'login'){ //Ambil Data
                $authAppToken = Self::authorizeToken($api_params['token'], null, false);
                if($authAppToken){
                    $res = $this->login($api_params['params']);
                    echo json_encode($res);
                }
            } else {
                $authAppToken = Self::authorizeToken($api_params['token'], $api_params['user_token']);
                if($authAppToken){
                    if($api_params['mode'] == 'find'){ //Ambil Data
                        $query = Yii::app()->db->createCommand(); //Yii.1 Create Command
                        if(isset($api_params['select'])){
                            if(isset($api_params['distinct'])){
                                if($api_params['distinct']){
                                    $query->selectDistinct($api_params['select']);
                                } else {
                                    $query->select($api_params['select']);
                                }
                            } else {
                                $query->select($api_params['select']);
                            }                    
                        } else {
                            if(isset($api_params['distinct'])){
                                if($api_params['distinct']){
                                    $query->selectDistinct('*');
                                } else {
                                    $query->select('*');
                                }
                            } else {
                                $query->select('*');
                            }                    
                        }
                        $query->from($table_name);
                        if(isset($api_params['join'])){
                            $query->join($api_params['join']['table'], $api_params['join']['condition']);
                        }
                        if(isset($api_params['order'])){
                            $query->order($api_params['order']);
                        }
                        if(isset($api_params['condition'])){
                            $query->where($api_params['condition']);
                            //need query builder?? //$query->where('id=100');
                        }
                        if(isset($api_params['limit'])){
                            $query->limit($api_params['limit']);
                        }
                        if(isset($api_params['offset'])){
                            $query->offset($api_params['offset']);
                        }
                        if(isset($api_params['exec'])){
                            $exec = $api_params['exec'];
                            $res = $query->$exec(); 
                        } else {
                            $res = $query->queryAll();
                        }          
                        $ret['status'] = 200;
                        $ret['data'] = $res;
                        echo json_encode($ret);
                    } else if($api_params['mode'] == 'edit'){ //Simpan Data
                        if(isset($api_params['attributes'])) {
                            $res = null;
                            if(isset($api_params['conditions'])) { //find by attributes
                                $res = $model::model()->findByAttributes($api_params['conditions']);
                            } else if(isset($api_params['attributes']['id'])) { //find by PK
                                $res = $model::model()->findByPk($api_params['attributes']['id']);
                            }
                            if(!$res) $res = new $model();
                            $data = $res->attributes;
                            $input = $api_params['attributes'];
                            foreach (array_keys($data) as $key) {
                                if(isset($input[$key])) $data[$key] = $input[$key];
                            }
                            $res->attributes = $data;
                            if($res->save()) {
                                $ret['status'] = 200;
                                $ret['data'] = $res->attributes;
                                echo json_encode($ret);
                            } else {
                                $ret['status'] = 500;
                                $ret['data'] = $res->getErrors();
                                echo json_encode($ret);
                            }
                        } else {
                            $ret['status'] = 500;
                            $ret['data'] = 'Attr cannot be empty';
                            echo json_encode($ret);
                        }
                    } else if($api_params['mode'] == 'delete'){ //Delete Data
                        $res = null;
                        if(isset($api_params['conditions'])) { //find by attributes
                            $res = $model::model()->findByAttributes($api_params['conditions']);
                        } else if(isset($api_params['attributes']['id'])) { //find by PK
                            $res = $model::model()->findByPk($api_params['attributes']['id']);
                        }
                        if($res) {
                            if($res->delete()) {
                                $ret['status'] = 200;
                                $ret['data'] = 'Success';
                                echo json_encode($ret);
                            } else {
                                $ret['status'] = 500;
                                $ret['data'] = 'Failed';
                                echo json_encode($ret);
                            }
                        } else {
                            $ret['status'] = 404;
                            $ret['data'] = 'Not Found';
                            echo json_encode($ret);
                        }
                    } else if($api_params['mode'] == 'custom'){ //Custom SQL
                        if(isset($api_params['sql'])){
                            $query = Yii::app()->db->createCommand($api_params['sql']);                    
                        }
                        if(isset($api_params['exec'])){
                            $exec = $api_params['exec'];
                            $res = $query->$exec();
                        } else {
                            $res = $query->queryAll();
                        }            
                        $ret['status'] = 200;
                        $ret['data'] = $res;
                        echo json_encode($ret);   
                    } else if($api_params['mode'] == 'function'){ //Panggil Function di MODEL                
                        $func = $api_params['function'];
                        echo json_encode($model::$func($api_params['params']));
                    } else if($api_params['mode'] == 'login'){ //Khusus Login
                        // Nyontek Punya Irul
                    } else if($api_params['mode'] == 'request_token'){
                        // $token = Helper::hash(date('ymdhis'));
                        // $token_list[$token]['token_next'] = Helper::hash(date('ymdhis2'));                    
                        // $token_list[$token]['ip_address'] = $_SERVER['REMOTE_ADDR'];
                        // $token_list[$token]['browser'] = $_SERVER['HTTP_USER_AGENT'];
                        // vdump($token_list);
                    }
                } else {
                    $ret['status'] = 401;
                    $ret['data'] = 'Unauthorized';
                    echo json_encode($ret);
                }
            }            
        } else {
            echo 'Rest API is not Enabled';
        }
    }
    

    private function authorizeToken($appToken, $userToken, $skipUserToken = true){
        //check application token
        $token = Setting::get('app.restApiToken');
        if($appToken == $token){
            if($skipUserToken){
                //check user token
                $t = User::model()->findByAttributes(['user_token' => $userToken]);
                if($t){
                    return true;
                } else {
                    return false;
                }            
            } else {
                return true;
            }            
        } else {
            return false;
        }
    }

    private function login($post){
        $Muser = User::model()->findByAttributes([
            "username" => $post["username"]
        ]);
    
        if (isset($Muser)) {
            if (password_verify($post["password"], $Muser->password)) {
                $user = (array)$Muser->attributes;

                unset($user["password"]);
                unset($user["username"]);

                $data = [
                    'user' => $user,
                    'user_token' => $user["user_token"],
                    'exp' => strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . " + 1 year"),
                    'time' => date('Y-m-d H:i:s'),
                ];

                if(isset($post["return"])){
                    $api_params = $post["return"];     
                    if(isset($api_params['model'])){
                        $model = $api_params['model'];
                        $table_name = $model::model()->tableName();
                    }   
                    if($api_params['mode'] == 'find'){ //Ambil Data
                        $query = Yii::app()->db->createCommand(); //Yii.1 Create Command
                        if(isset($api_params['select'])){
                            if(isset($api_params['distinct'])){
                                if($api_params['distinct']){
                                    $query->selectDistinct($api_params['select']);
                                } else {
                                    $query->select($api_params['select']);
                                }
                            } else {
                                $query->select($api_params['select']);
                            }                    
                        } else {
                            if(isset($api_params['distinct'])){
                                if($api_params['distinct']){
                                    $query->selectDistinct('*');
                                } else {
                                    $query->select('*');
                                }
                            } else {
                                $query->select('*');
                            }                    
                        }
                        $query->from($table_name);
                        if(isset($api_params['join'])){
                            $query->join($api_params['join']['table'], $api_params['join']['condition']);
                        }
                        if(isset($api_params['order'])){
                            $query->order($api_params['order']);
                        }
                        if(isset($api_params['condition'])){
                            $query->where($api_params['condition'], array(':id'=>$Muser->id));
                            //need query builder?? //$query->where('id=100');
                        }
                        if(isset($api_params['limit'])){
                            $query->limit($api_params['limit']);
                        }
                        if(isset($api_params['offset'])){
                            $query->offset($api_params['offset']);
                        }
                        if(isset($api_params['exec'])){
                            $exec = $api_params['exec'];
                            $res = $query->$exec(); 
                        } else {
                            $res = $query->queryAll();
                        }          
                        if(isset($api_params['data_key'])){
                            $data[$api_params['data_key']] = (array)$res;
                        }                        
                        if(isset($api_params['id_key'])){
                            $data[$api_params['id_key']] = $res['id'];
                        }                        
                    }
                }
                $a = new Authorization();
                $data['jwt'] = $a->generateToken($data);
                $result = ["status" => true, "message" => "Berhasil masuk.", "data" => $data];
            } else {
                $result = ["status" => false, "message" => "Kata sandi salah."];
            }
        } else {
            $result = ["status" => false, "message" => "Pengguna belum terdaftar."];
        }
        return $result;
    }
}
