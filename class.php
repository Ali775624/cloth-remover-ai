<?php


class _5sim_com {
	
	//private $api_key;
	private $link = "http://api1.5sim.net/stubs/handler_api.php";
	public function __construct ($api_key) {
		//$this->api_key = $api_key;
		$this->link .= "?api_key=" . $api_key;
	}
	//get balance
	public function getBalance () {
		$get = file_get_contents($this->link ."&action=getBalance");
		$balance = explode (":",$get)[1] ?? 0;
		return $this->isError ($get) ? 
			["ok"=>true,"balance"=>$balance]:
			["ok"=>false,"error"=>$get];
	}
	//get Number 
	public function getNumber ($co,$ser) {
		$get = file_get_contents($this->link ."&action=getNumber&service={$ser}&country={$co}");
		$ex = explode (":",$get); 
		$id = $ex[1]??0;
		$number = $ex[2]??0;
		return $this->isError ($get) ? 
			["ok"=>true,"id"=>$id,"number"=>$number]:
			["ok"=>false,"error"=>$get];
	}
	
	//get code 
	public function getCode ($id) {
		//STATUS_OK:code 
		$get = file_get_contents($this->link ."&action=getStatus&id={$id}");
		$code = explode (":",$get)[1]??0;
		return $this->isError ($get) ? 
			["ok"=>true,"code"=>$code]:
			["ok"=>false,"error"=>$get];
	}
	
	//cancel number 
	public function cencel ($id) {
		$get = file_get_contents($this->link ."&action=setStatus&id={$id}&status=-1");
		return $this->isError ($get) ? 
			["ok"=>true]:
			["ok"=>false,"error"=>$get];
	}
	// check if there is an error 
	public function isError ($data) {
		$listError = array(
			"BAD_KEY",
			"BAD_ACTION",
			"NO_ACTIVATION",
			"NO_NUMBERS",
			"STATUS_WAIT_CODE",
			"STATUS_CANCEL",
			"NO_BALANCE",
			"BAD_STATUS",
			"STATUS_WAIT_RESEND",
			"STATUS_WAIT_RETRY"
		);
		$preg = join ("|",$listError);
		return !preg_match("/$preg/i",$data) ;
	}
}




