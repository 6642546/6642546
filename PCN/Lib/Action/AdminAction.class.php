<?php

class AdminAction extends CommonAction {
   
  public function index(){
         $this->assign("userName",$_SESSION['displayname']);
		 $this->display();
	}
 public function add(){
	  $Model = M("userrules"); // 实例化User对象
      $this->display();
 }
public function edit(){
	     $id= $this->_get('id');
		 $Model = M("userrules"); 
	     $ls=$Model->where("id=$id")->find();
		 $this->assign('vo',$ls);
		 $this->display();
	 }
  public function save(){
          $userrules=M('userrules');
		  $data=$_POST;
		  $ls=$userrules->where('user_name="'.$data['user_name'].'"')->find();
		  if ($ls){
		     $this->assign("jumpUrl",__URL__);
		     $this->error(L('user_exist'));
		  }
		  if($data['status']=='Valid'){
		      $data['status']=1;
		  }else{
		     $data['status']=0;
		  }
		  unset($data['__hash__']);	 
		  $datetime = new \DateTime;
          $data['create_time'] =$datetime->format('Y-m-d H:i:s');
		  if (is_array( $data['access_rule'])){
		    $data['access_rule'] =implode( ';', $data['access_rule']) ;
		  }
	      $userrules->add($data);
		 // echo var_dump($data);
		 // echo $main->getLastSql();
		   redirect(__URL__);
 }
  public function update(){
          $Model=M("userrules");
		  $data=$_POST;
		  unset($data['__hash__']);
		  $id=$data['id'];
		  if($data['status']=='Valid'){
		      $data['status']=1;
		  }else{
		     $data['status']=0;
		  }
		   if (is_array( $data['access_rule'])){
		    $data['access_rule'] =implode( ';', $data['access_rule']) ;
		  }
	      $Model->where("id=$id")->save($data);
		  redirect(__URL__);
 }
 public function del(){
	 $id= $this->_get('id');
	 $Model = M("userrules"); // 实例化User对象	 
	 $result=$Model->where("id=$id")->delete();
	 $this->assign("jumpUrl",__URL__);
	 if($result){
	    $this->success('删除记录成功!');
	 }else{
	     $this->error('删除记录失败!');
	 }
	  redirect(__URL__);
 }
 
  public function getUserRule(){
		 $voList = M("userrules");
		 $ps=$_GET["limit"];
		 $pn=$_GET["offset"];
		 $searchtext=$_GET["search"];
	 

		 $count = $voList->where("user_name like '%$searchtext%' ")->limit($pn,$ps)->count();
		 $list=$voList->where("user_name like '%$searchtext%' ")->limit($pn,$ps)->order('id desc')->select();
		// $count = $voList->limit($pn,$ps)->count();
		// $list=$voList->limit($pn,$ps)->select();
		 $data["total"]=$count;
		 $data["rows"]=$list;
		 $this->ajaxReturn($data,'JSON');
		 // $this->ajaxReturn($list,'JSON');	
	}
	public function getMailSetting(){
		 $voList = M("setting");
		 $ps=$_GET["limit"];
		 $pn=$_GET["offset"];
		 $searchtext=$_GET["search"];
	 

		 $count = $voList->where("setting_value like '%$searchtext%' ")->limit($pn,$ps)->count();
		 $list=$voList->where("setting_value like '%$searchtext%' ")->limit($pn,$ps)->order('id desc')->select();
		// $count = $voList->limit($pn,$ps)->count();
		// $list=$voList->limit($pn,$ps)->select();
		 $data["total"]=$count;
		 $data["rows"]=$list;
		 $this->ajaxReturn($data,'JSON');
		 // $this->ajaxReturn($list,'JSON');	
	}
	public function getData_approval(){
	 $map="id >0"	;
	 $pcnstatus=$this->_get('pcnstatus');
     $voList = M("main");
	 $ps=$_GET["limit"];
	 $pn=$_GET["offset"];
     $searchtext=$_GET["search"];
 

     $count = $voList->where("curr_status = '$pcnstatus' ")->limit($pn,$ps)->count();
	 $list=$voList->where("curr_status = '$pcnstatus' ")->limit($pn,$ps)->order('id desc')->select();
	// $count = $voList->limit($pn,$ps)->count();
	// $list=$voList->limit($pn,$ps)->select();
	 $data["total"]=$count;
	 $data["rows"]=$list;
     $this->ajaxReturn($data,'JSON');
	 // $this->ajaxReturn($list,'JSON');	
	}
		public function mailsetting(){
				 //echo $this->getMailSetting();
				 $this->assign("userName",$_SESSION['displayname']);
				 $this->display();
			}
			public function mail_add(){
			  $this->display();
		  }
		   public function mail_edit(){
				 $id= $this->_get('id');
				 $Model = M("setting"); 
				 $ls=$Model->where("id=$id")->find();
				 $this->assign('vo',$ls);
				 $this->display();
			 }
			 public function mail_save(){
				  $setting=M('setting');
				  $data=$_POST;
				  unset($data['__hash__']);	 
				  $setting->add($data);
				 // echo var_dump($data);
				 // echo $main->getLastSql();
				   $this->redirect('Admin/mailsetting');
		 }
		 public function mail_update(){
				  $Model=M("setting");
				  $data=$_POST;
				  unset($data['__hash__']);
				  $id=$data['id'];		  
				  $Model->where("id=$id")->save($data);
				  $this->redirect('Admin/mailsetting');
		 }
		  public function mail_del(){
			 $id= $this->_get('id');
			 $Model = M("setting"); // 实例化User对象	 
			 $result=$Model->where("id=$id")->delete();
			 if($result){
				$this->success('删除记录成功!');
			 }else{
				 $this->error('删除记录失败!');
			 }
			 $this->redirect('Admin/mailsetting');
		 }
	public function InplanJob() {
			$kw = $this->_get('key');
			$st_query = "select item_name as jobname,da.customer_pn_, cs.customer_name from items jb, job,customer cs ,job_da da
						where jb.item_type=2 and jb.item_id=job.item_id
						and jb.last_checked_in_rev=job.revision_id and job.customer_id=cs.cust_id
                        and job.item_id=da.item_id and job.revision_id=da.revision_id
						and job.odb_site_id=1
						and length(jb.item_name)=12 and item_name like '%".strtoupper($kw)."%' and rownum<=10";
			$conn = oci_connect(C("InPlan_User"), C("InPlan_Pass"), C("InPlan_SID"),'utf8');
			$stid = oci_parse($conn,$st_query);
			$r = oci_execute($stid, OCI_DEFAULT);
			while ($row = oci_fetch_array ($stid, OCI_BOTH)) {
				 $response[] = array("value"=>$row['CUSTOMER_PN_'].'%'.$row['CUSTOMER_NAME'],"label"=>$row['JOBNAME']);
			}
			oci_close($conn);
			echo  json_encode($response);
			
		}
   public function getExecUser() {
			$kw = $this->_get('key');
			$execuser=M('execuser');
			$ls=$execuser->where("name like '%". $kw ."%'")->select();
			foreach($ls as $key=>$val){
			     $response[] = array("value"=>$val['email'],"label"=>$val['name']);
			}
			echo  json_encode($response);			
	  }
	public function getPeMangementInfo() {
		$conn = @mysql_connect("localhost","root","") or die("connection failed");
				mysql_select_db("project_status",$conn);
				mysql_query("set names 'UTF8'");
			    $kw = $this->_get('key');
					$sql = "SELECT fg_item_number,ptl_no FROM project_status.project_status_form where fg_item_number like '%".$kw."%' and fg_item_number is not null and fg_item_number<>'' limit 10";			
					$rs = mysql_query($sql);				
					while($obj = mysql_fetch_object($rs))
					{				   
						 $response[] = array("value"=>$obj->ptl_no,"label"=>$obj->fg_item_number);
					}
                mysql_close($conn);
			       echo  json_encode($response);			
	  }
    function str_to_utf8 ($str = '') {
    $current_encode = mb_detect_encoding($str, array("ASCII","GB2312","GBK",'BIG5','UTF-8')); 
    $encoded_str = mb_convert_encoding($str, 'UTF-8', $current_encode);
    return $encoded_str;
}
}