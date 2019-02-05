<?php
/**
 * Curl wrapper for Yii
 * @author hackerone
 */
class Curl extends CComponent
{
    private $_ch;
    private $response;

    // config from config.php
    public $options;

    // default config
    private $_config = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HEADER         => false,
        CURLOPT_VERBOSE        => true,
        CURLOPT_AUTOREFERER    => true,         
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        //CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'
    );

    private function exec($url)
    {
        $this->setOption(CURLOPT_URL, $url);
        Yii::log($url, "warning", "curl log");
        $this->response = curl_exec($this->_ch);
        if (!curl_errno($this->_ch)) {
            if ($this->options[CURLOPT_HEADER]) {
                $header_size = curl_getinfo($this->_ch, CURLINFO_HEADER_SIZE);
                return substr($this->response, $header_size);
            }
            return $this->response;
        } else {
            throw new CException(curl_error($this->_ch));
            return false;
        }
    }

    public function get($url, $params = array())
    {
        $this->setOption(CURLOPT_HTTPGET, true);

        return $this->exec($this->buildUrl($url, $params));
    }

    public function post($url, $data = array())
    {
        $this->setOption(CURLOPT_POST, true);
        $this->setOption(CURLOPT_POSTFIELDS, http_build_query($data));

        return $this->exec($url);
    }

    public function put($url, $data, $params = array())
    {
        // write to memory/temp
        $f = fopen('php://temp', 'rw+');
        fwrite($f, $data);
        rewind($f);

        $this->setOption(CURLOPT_PUT, true);
        $this->setOption(CURLOPT_INFILE, $f);
        $this->setOption(CURLOPT_INFILESIZE, strlen($data));
        
        return $this->exec($this->buildUrl($url, $params));
    }

    public function delete($url, $params = array())
    {
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'DELETE');

        return $this->exec($this->buildUrl($url, $params));
    }

    public function buildUrl($url, $data = array())
    {
        $parsed = parse_url($url);
        isset($parsed['query']) ? parse_str($parsed['query'], $parsed['query']) : $parsed['query'] = array();
        $params = isset($parsed['query']) ? array_merge($parsed['query'], $data) : $data;
        $parsed['query'] = ($params) ? '?' . http_build_query($params) : '';
        if (!isset($parsed['path'])) {
            $parsed['path']='/';
        }

        $parsed['port'] = isset($parsed['port'])?':'.$parsed['port']:'';

        return $parsed['scheme'].'://'.$parsed['host'].$parsed['port'].$parsed['path'].$parsed['query'];
    }

    public function setOptions($options = array())
    {
        curl_setopt_array($this->_ch, $options);

        return $this;
    }

    public function setOption($option, $value)
    {
        curl_setopt($this->_ch, $option, $value);

        return $this;
    }

    public function setHeaders($header = array())
    {
        if ($this->isAssoc($header)) {
            $out = array();
            foreach ($header as $k => $v) {
                $out[] = $k .': '.$v;
            }
            $header = $out;
        }

        $this->setOption(CURLOPT_HTTPHEADER, $header);
        
        return $this;
    }

    private function isAssoc($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public function getError()
    {
        return curl_error($this->_ch);
    }

    public function getInfo()
    {
        return curl_getinfo($this->_ch);
    }

    public function getStatus()
    {
        return curl_getinfo($this->_ch, CURLINFO_HTTP_CODE);
    }

    // initialize curl
    public function init()
    {
        try {
            $this->_ch = curl_init();
            $options = is_array($this->options) ? ($this->options + $this->_config) : $this->_config;
            $this->setOptions($options);

            $ch = $this->_ch;
            
            // close curl on exit
            @Yii::app()->onEndRequest = function() use(&$ch){
                curl_close($ch);
            };
        } catch (Exception $e) {
            throw new CException('Curl not installed');
        }
    }

    public function getHeaders()
    {
        $headers = array();

        $header_text = substr($this->response, 0, strpos($this->response, "\r\n\r\n"));

        foreach (explode("\r\n", $header_text) as $i => $line) {
            if ($i === 0) {
                $headers['http_code'] = $line;
            } else {
                list ($key, $value) = explode(': ', $line);

                $headers[$key] = $value;
            }
        }

        return $headers;
    }

}