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
}