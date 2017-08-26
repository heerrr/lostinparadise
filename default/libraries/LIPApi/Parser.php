<?php


class LIPApi_Parser {
    protected $engine;
    protected $method;
    protected $parser;
    protected $request = null;

    protected $url = null;
    public function __construct($engine,$method,$parser, $org_id, $request = null) {
        $this->engine = $engine;
        $this->method = $method;
        $this->parser = $parser;
        $this->org_id = $org_id;
        $this->request = $request;
        
    }
    
    public function parser() {
        return $this->parser;
    }
    public function method() {
        return $this->method;
    }
    public function engine() {
        return $this->engine;
    }
    public function url() {
        return $this->url;
    }
    
    
    
    protected function request_callback(&$curl, $parser_object, $data = NULL) {
        
    }
    
    public function curl($parser_object, $data) {
        
        $curl = CCurl::factory($this->url);
        $curl->set_timeout(40000);
        $curl->set_useragent('Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0');

        if ($data != NULL) {
            if (is_array($data)) {
                $curl->set_post($data);
            } else {
                $curl->set_raw_post($data);
            }
        }
        $curl->set_opt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl->set_opt(CURLOPT_SSL_VERIFYHOST, 2);
        $curl->set_opt(CURLOPT_ENCODING, 'gzip, deflate');


        $this->request_callback($curl, $parser_object, $data);
        CFBenchmark::start($parser_object->engine() . '_' . $parser_object->parser() . '_request');
        
        return $curl;
    }
}