<?php
static $exceptionClass;
if(@class_exists("TMException"))
{
	$exceptionClass = "TMException";
}else{
	$exceptionClass = "Exception";
}

eval("class TaeBaseException extends $exceptionClass {}");

class TaeException extends TaeBaseException {
	
	private $request;
	private $response;
	private $ret_code;
	
	public function __construct($message, $code = TaeConstants::EXCEPTION_TAE)
    {
        parent::__construct ( $message, $code );
    }
    
    public function getRequest()
    {
    	return $this->request;
    }
    
    public function getResponse()
    {
    	return $this->response;
    }
    
    public function setRequest($request)
    {
    	$this->request = $request;
    }
    
	public function setResponse($response)
    {
    	$this->response = $response;
    }
    
    public function setRetCode($ret_code)
    {
    	$this->ret_code = $ret_code;
    }
    
    public function getRetCode()
    {
    	return $this->ret_code;
    }
}
?>