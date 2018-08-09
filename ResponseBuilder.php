<?php

/**
 * Clase para el control de las peticiones HTTP
 */
class ResponseJson
{
	public $Request = array(
		'Status' => null,
		'Code'   => null,
		'Message'=> null
	);
	
	public function __construct(){}
	public function SetRequestStatus($status, $message){
		if ($status == "ok") {
			$this->Request['Status']  = 'Success';
			$this->Request['Code']    = 200;
			$this->Request['Message'] = $message;
		}
		if ($status == "error") {
			$this->Request['Status']  = 'Failed';
			$this->Request['Code']    = 404;
			$this->Request['Message'] = $message;
		}
	}

	public function SetRequestData($name, $data){
		$this->Request[$name] = $data;
    }
    
    public function BuildJson(){
        return json_encode($this->Request);
    }
}

?>