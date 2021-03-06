<?php 

class Basecontroller {
	var $public = false;
	var $controller = "Base";
	
	function __construct() {
		
		$this->joel = $_SESSION['joel'];	
		$this->db = $_SESSION['db'];
		
		// if the request is accessing a controller that requires authentication
		// but user is not logged in, kick him in his ass ;-)
		if(!$this->public && !$this->joel->user->user_id) {
    		header('HTTP/1.1 401 Unauthorized');
			print 'ERROR 401: Access of this Controller requires valid user session.';
    		exit;
		}
	}
	
	/**
	 * returns true or false whether a user as admin privs or not
	 * 
	 * @return Bool
	 */
	public function _isAdmin() {
		return (bool)$this->joel->user->u_type == 'admin' ? true : false;
	}
	
	public function _localize($str) {
		if($locale = new Locale($this->controller)) {
			$str = $locale->str($str);
		}
		
		return $str;
	}
	
	
}
