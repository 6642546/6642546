<?php
class PublicAction extends Action {
	//Login page
	public function login() {
		if(!isset($_SESSION[C('USER_AUTH_KEY')]) || !$_SESSION[C('USER_AUTH_KEY')]) {
		    $this->assign ( "jumpUrl", $this->_get("jumpUrl") );
			$this->display();

		}else{
			$this->redirect('Index/index');
		}
	}
	
	public function index()
	{
		//If access then go the index page.
		redirect(__APP__);

	}
	
	//logout page
    public function logout()
    {
        if(isset($_SESSION[C('USER_AUTH_KEY')])) {
			unset($_SESSION[C('USER_AUTH_KEY')]);
			session_unset();
			session_destroy();
            $this->assign("jumpUrl",__URL__.'/login/');
            $this->success(L('_OPERATION_SUCCESS_'));
        }else {
            $this->error('Already logout!');
        }
    }

	//Login check.
	public function checkLogin() {
		if(empty($_POST['account'])) {
			$this->error(L('need_account'));
		}elseif (empty($_POST['password'])){
			$this->error(L('need_password'));
		}
		$admin = 'guest';
		$user = M('userrules');
		$data['local_ip']=gethostbyname($_ENV['COMPUTERNAME']);
		$data['last_time']=date('Y-m-d h:i:s', time());
		$user->where("user_name='".$_POST['account']."' and status=1")->save($data);
        $ls= $user->where("user_name='".$_POST['account']."' and status=1")->find();
        if($ls){
			 $_SESSION['admin']=$ls['role'];
			 $_SESSION['access_rules']=$ls['access_rule'].';index';
		     if($ls['user_pwd']!=''){
			      if ($ls['user_pwd']==$_POST['password']){
                      $_SESSION['displayname']=$_POST['account'];
					   $_SESSION[C('USER_AUTH_KEY')]	=	strtolower($_POST['account']);
						 
						  $this->success(L('_OPERATION_SUCCESS_'));
					   exit;

				  }else{				  
                       $this->error(L('error_password'));
					   exit;
				  }
			 
			 }
		
		}else{
		    
			 $_SESSION['admin']		=	$admin;
			  $_SESSION['access_rules']='view_detail;index';
		}	
		
		$ldap_server = '10.65.8.51';//'10.1.1.83';
		$domain="vspri";
		$conn_ = ldap_connect($ldap_server);
		
		if(!$conn_){
			$this->error('Connect to LDAP server error!');
		}	
		$bind = @ldap_bind($conn_, "$domain\\".$_POST['account'], utf8_decode($_POST['password']));
		if(!$bind){
			$this->error(L('error_password'));
		} else {
			$ad = getADUsersData($_POST['account'],utf8_decode($_POST['password']));
			$ls= $user->where(" (find_in_set(user_name, '".$ad['memberof']."') or user_name='".$_POST['account']."')  and status=1")->find();
            if(!$ls){

				$this->error('没权限访问该网页,请与管理员联系!');
			}

		}
	
		
		$lifeTime = 24 * 3600 * 7; 
		//session_set_cookie_params($lifeTime);
		//Session::setExpire(time(),$lifeTime);

		$_SESSION[C('USER_AUTH_KEY')]	=	strtolower($_POST['account']);
        $_SESSION['LoginUserName']	=	strtolower($_POST['account']);
		$_SESSION['LoginUserPass']    =   utf8_decode($_POST['password']);
		
		
		if ($ad) {
			$_SESSION['displayname'] = $ad['displayname'];
			$_SESSION['email'] = $ad['email'];
			$_SESSION['manager_email'] = $ad['manager_email'];
			$_SESSION['manager_name'] = $ad['manager_name'];
			$_SESSION['manager_phone'] = $ad['manager_phone'];
			$_SESSION['telephonenumber'] = $ad['telephonenumber'];
			$_SESSION['dept'] = $ad['department'];
		}
		
		if (isset($_POST['jumpUrl']) && $_POST['jumpUrl'] != '') {
			$url = $_POST['jumpUrl'];
		} else {
			$url = U("Index/index");
		}
		$this->assign("jumpUrl",$url);
		$this->success(L('_OPERATION_SUCCESS_'),$url);
		
		
        
	}


  
}
?>