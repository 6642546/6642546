<?php
if (!defined('THINK_PATH')) exit();
//$config	=	require 'config.php';
$array=array(
    /*'DB_DSN' => 'mysql://root:@localhost:3306/project_status',
	'DB_PREFIX'=>'project_status_',
	'DB_CHARSET'=> 'utf8',*/
	//'DB_DSN' => 'mysql://root:@localhost:3306/pcn',
   
    'DB_TYPE'=>'mysql',
	'DB_HOST'=>'localhost',
	'DB_NAME'=>'pcn',
	'DB_USER'=>'root',
	'DB_PWD'=>'root',
	'DB_PORT'=>'3306',
	'DB_PREFIX'=>'pcn_',
	'DB_CHARSET'=> 'utf8',
	'USER_AUTH_ON'=>true,
	'USER_AUTH_TYPE'			=>1,
    'USER_AUTH_KEY'				=>'pcnAuthId',
    'ADMIN_AUTH_KEY'			=>'pcn_administrator',
	 'USER_AUTH_MODEL'			=>'User',
	'USER_AUTH_GATEWAY'			=>'/Public/login',
	'NOT_AUTH_MODULE'			=>'Public',
	'REQUIRE_AUTH_MODULE'		=>'',
	'NOT_AUTH_ACTION'			=>'show',
	'REQUIRE_AUTH_ACTION'		=>'',
    'GUEST_AUTH_ON'          	=>false,
    'GUEST_AUTH_ID'           	=>0,
	'SHOW_RUN_TIME'				=>false,
	'SHOW_CACHE_TIMES'			=>false,
	'SHOW_USE_MEM'				=>false,
	'SHOW_ADV_TIME'				=>false,
	//'SHOW_DB_TIMES'				=>false,
  //  'DB_LIKE_FIELDS'			=>'title|remark',
	 'HTML_CACHE_ON'  			=>true,
	 'TMPL_CACHE_ON' 			=>true,
	//'DB_FIELDS_CACHE' 			=>true, 
	 //'ACTION_CACHE_ON' 			=>true,
	'LANG_SWITCH_ON' 			=>true,
	'LANG_AUTO_DETECT' 			=>true,
	//'DATA_CACHE_TIME'			=>0,
	'LIMIT_REFLESH_ON' 			=>false,
	'DEFAULT_LANGUAGE'			=>'zh-cn',
	'SESSION_AUTO_START' 		=>false, 
	'TOKEN_ON'=>true, 
	//'DEFAULT_THEME' 			=>'default',
	'APP_AUTOLOAD_PATH'			=>'@.ORG',
	'LANG_LIST'					=>'zh-cn,en-us',
	 'TMPL_ACTION_ERROR' 		=> APP_PATH. 'Tpl/Public/error_.html',
	//'TMPL_ACTION_SUCCESS' 		=> APP_PATH. 'Tpl/Public/success.html',
	'TMPL_ACTION_SUCCESS' 		=> APP_PATH. 'Tpl/Public/error_.html',
	'LANG_SWITCH_ON' => TRUE, //�������԰����ܣ�������뿪��
	//'VAR_LANGUAGE'              =>'l',
    //'APP_STATUS' => 'test', //Ӧ�õ���ģʽ״̬
    //"LOAD_EXT_FILE"=>"user,db",
	'DEFAULT_FILTER'=>'htmlspecialchars,strip_tags',
	'DB_FIELDS_CACHE'=>false,
	  //'DB_FIELDS_CACHE'=>false,
    'InPlan_SID' =>'10.120.1.30:1521/inmind',
	'InPlan_User' =>'VIA_GZ',
	'InPlan_Pass' =>'cedb',
);
//return array_merge($config,$array);
return $array ;
?>
							
