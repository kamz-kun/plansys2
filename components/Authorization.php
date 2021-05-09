<?php
class Authorization
{
    private $secretKey = '26CI1VZVB8OICUWQI2QTSUYR03ZRTBZ4RX81RJF3';

    public function encrypt($data)
    {
        $response = "";
        $payload = $data;
        if (!isset($payload)) {
            $response = ["status" => false, "message" => "Data kosong."];
        } else {
            if (!is_string($payload)) {
                $payload = json_encode($payload);
            }
            $cipher = "AES-128-CBC";
            $ivlen = openssl_cipher_iv_length($cipher);
            $iv = openssl_random_pseudo_bytes($ivlen);
            $encrypt = openssl_encrypt($payload, $cipher, $this->secretKey, $options = OPENSSL_RAW_DATA, $iv);
            $hmac = hash_hmac('sha256', $encrypt, $this->secretKey, $as_binary = true);
            $ciphertext = base64_encode($iv . $hmac . $encrypt);

            if ($encrypt === false) {
                $response = ["status" => false, "message" => "Gagal mengenkripsi"];
            } else {
                $response = ["status" => true, "message" => "Berhasil mengenkripsi", "data" => $ciphertext];
            }
        }

        return $response;
    }

    public function decrypt($token)
    {
        $response = [];
        $c = base64_decode($token);
        $cipher = "AES-128-CBC";
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len = 32);
        
        $ciphertext_raw = substr($c, $ivlen + $sha2len);
        $decrypt = openssl_decrypt($ciphertext_raw, $cipher, $this->secretKey, $options = OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $this->secretKey, $as_binary = true);
        $data = json_decode($decrypt, true);
        if (is_null($data)) {
             $data = $decrypt;
        }
    
        
        if ($hmac == $calcmac) {
            $response = ["status" => true, "message" => "Berhasil mendekripsi", "data" => $data];
        } else {
             $response = ["status" => false, "message" => "Gagal mendekripsi"];
        }
        return $response;
    }

    // public function generateToken($appName, $appToken, $data)
    public function generateToken($data)
    {
        $header = $this->encrypt(['typ' => 'JWT', 'alg' => 'HS256'])["data"];
        $payload = $this->encrypt($data)['data'];
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secretKey, true); //SECRET KEY HERE
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
        return $jwt;
    }

    public function getPayload($token)
    {
        $tokenParts = explode('.', $token);
        $payload = $tokenParts[1];
        $payloadObj = $this->decrypt(base64_decode($payload))["data"];

        return $payloadObj;
    }

    private function getBearerToken()
    {
        $headers = null;

        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return strval($headers);
    }

    public function validateToken()
    {
        // split the token
        $jwt = $this->getBearerToken();
        $tokenParts = explode('.', $jwt);
        $header = @$tokenParts[0];
        $payload = @$tokenParts[1];
        $signatureProvided = @$tokenParts[2];
        $payloadObj = @$this->decrypt(base64_decode($payload))['data'];
        $signature = hash_hmac('sha256', $header . "." . $payload, $this->secretKey, true); //SECRET KEY HERE
        
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        if ($signatureProvided !== $base64UrlSignature) {
             return ["status" => false, "message" => "The signature is NOT valid."];
        }

        if (isset($payloadObj["exp"])) {
            $expiration = $payloadObj["exp"];
            if (strtotime(date("Y-m-d")) > $expiration) {
                return ["status" => false, "message" => "Token has expired."];
            }
        }

        return ["status" => true, "message" => "The signature is valid.", "payload" => $payloadObj];
    }
}
