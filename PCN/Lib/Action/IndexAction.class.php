<?php
//$url = "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["REQUEST_URI"])."/upload_handler.php";

class IndexAction extends CommonAction {
  public function index(){
	    // var_dump($_SESSION['access_rules']);
		 $this->assign("rules",$_SESSION['access_rules']);
         $this->assign("userName",$_SESSION['displayname']);
		 $this->display();
	}
 public function add(){
	 $Model = M("main"); // 实例化User对象
  // $maxno= $Model->query("SELECT max(pcn_no) as no FROM pcn_main");
    // echo $this-> InPlanJob();
	 $dateNo=strval(date("Y").date("m").date("d"));
	 $maxno=$Model->where("pcn_no like \"$dateNo%\"")->max('pcn_no');
	 //echo dump($maxno);
	 if ($maxno){
	     $maxno=$maxno+1;
	  }else{
	    $maxno=date("Y").date("m").date("d").'001';	  
	  }
      $prem=M();
	  $prem1 = $prem->query("SELECT user_name  FROM pcn_userrules where access_rule like 'pre%'");
	  $pcntype = $prem->query("SELECT setting_value  FROM pcn_setting where setting_name= 'pcntype'");
	  $pcntype=explode(',',$pcntype[0]['setting_value']);
	 //  echo dump($pcntype);
	  $Attach = M('attach');
	 $AttachFile=$Attach->where("pcnNo=$maxno")->select();
	 if(!empty($AttachFile)){ //判断一级是否为空
         foreach($AttachFile as $key=>$value){   //循环读取
         @unlink($value['savepath'].'/'.$value['savename']);
        //echo D('Category')->getLastSql(); //打印sql语句的写法
       }
	 }
	 $Attach->where("pcnNo=$maxno")->delete();
	 // echo $initialPreviewConfig;
	  $initialPreview=json_encode([]);
	  $initialPreviewConfig= '[]';
	  $this->assign("initialPreview",$initialPreview);
	  $this->assign("initialPreviewConfig",$initialPreviewConfig);
      $this->assign("userName",$_SESSION['displayname']);
	  $this->assign("pcn_no",$maxno);
	  $this->assign("initiator_dept",$_SESSION['displayname']);//.'/'.$_SESSION['dept']);//$_SESSION['dept']
      $this->assign("mlist",$prem1);
	  $this->assign("pcntype",$pcntype);
      $downurl="'http://". $_SERVER['HTTP_HOST'].__ROOT__.'/'.APP_NAME."/Uploads/{filename}'";
	  $this->assign("downurl",$downurl);
	  $this->display();
 }
	public function edit(){
	     $pcn_no= $this->_get('pcn_no');
		 $Model = M("main"); 
	     $ls=$Model->where("pcn_no=$pcn_no")->find();
		 //echo dump($ls);
		// $downurl="'http://". $_SERVER['HTTP_HOST'].__ROOT__.'/'.APP_NAME."/uploads/{filename}'";
		 $internal_pn = explode(";",$ls['internal_pn']);
         $cust_pn =     explode(";",$ls['cust_pn']);
         $cust_name=    explode(";", $ls['cust_name']);
		 $tableDetail="";
		$actions='<a class="add" title="Add" data-toggle="tooltip"><i class="fa fa-tasks" style="font-size: 16px;"></i></a>
					<a class="edit" title="Edit" data-toggle="tooltip"><i class="fa fa-pencil-alt" style="font-size:16px;"></i></a>
					<a class="delete" title="Delete" data-toggle="tooltip"><i class="fa fa-trash" style="font-size:16px;"></i></a>';
			 foreach($internal_pn as $key=>$val){
					 $tableDetail.="
									<tr>
										<td>$val</td>
										<td>$cust_pn[$key]</td>
										<td>$cust_name[$key]</td>
										<td> $actions </td>
									</tr>";
			 }
			 $Attach = M('attach');
			 $AttachFile=$Attach->where("pcnNo=$pcn_no and location_attach is null")->select();
			 if(!empty($AttachFile)){ //判断一级是否为空
				 foreach($AttachFile as $key=>$value){   //循环读取
					// $initialPreview[]="http://10.120.1.243/webtools/PCN/uploads/".$value['name'];
					 $initialPreview[]="http://". $_SERVER['HTTP_HOST'].__ROOT__.'/'.APP_NAME."/Uploads/".$value['name'];
					 $initialPreviewConfig[]=array("type"=>$value['category'],
												   "size"=>$value['size'],
												   "caption"=>$value['name'],
												   "url"=>"__URL__/del_file",
												   "key"=>$value['recordid'],
                                                                                                                                                                                                   "filename"=> $value['savename']);
			   }
			 }
		  if ($initialPreview){
			   $initialPreview= json_encode($initialPreview);
		  }else{
		       $initialPreview=json_encode([]);
			  
		   }
		  if ($initialPreviewConfig){
			  $initialPreviewConfig= json_encode($initialPreviewConfig);
		  }else{
		      $initialPreviewConfig= '[]';
		  }
          $prem=M();
	      $prem1 = $prem->query("SELECT user_name  FROM pcn_userrules where access_rule like 'pre%'");
		  $pcntype = $prem->query("SELECT setting_value  FROM pcn_setting where setting_name= 'pcntype'");
	      $pcntype=explode(',',$pcntype[0]['setting_value']);
		  $this->assign("initialPreview",$initialPreview);
		  $this->assign("initialPreviewConfig",$initialPreviewConfig);
		  $this->assign("userName",$_SESSION['displayname']);
		  $this->assign('vo',$ls);
		  $this->assign("tableDetail",$tableDetail);
          $this->assign("mlist",$prem1);
          $this->assign("pcntype",$pcntype);
		  $downurl="'http://". $_SERVER['HTTP_HOST'].__ROOT__.'/'.APP_NAME."/Uploads/{filename}'";
		  $this->assign("downurl",$downurl);
		  $this->display();
	 }
	 public function copy_rec(){
	     $pcn_no= $this->_get('pcn_no');
		 $Model = M("main"); 
		 $dateNo=strval(date("Y").date("m").date("d"));
	     $maxno=$Model->where("pcn_no like \"$dateNo%\"")->max('pcn_no');
	 //echo dump($maxno);
	     if ($maxno){
	        $maxno=$maxno+1;
	     }else{
	        $maxno=date("Y").date("m").date("d").'001';	  
	     }
	     $ls=$Model->where("pcn_no=$pcn_no")->find();
		 //echo dump($ls);
		 $ls['pcn_no']       =$maxno;
		 $ls['internal_pn']  ='';
		 $ls['cust_pn']      ='';
		 $ls['cust_name']    ='';
		// echo "<script>alert('http://". $_SERVER['HTTP_HOST'].__ROOT__.'/'.APP_NAME."/uploads/"."')</script>";
			 $Attach = M('attach');
			 $AttachFile=$Attach->where("pcnNo=$pcn_no and location_attach is null")->select();
			  if(!empty($AttachFile)){ //判断一级是否为空
				 foreach($AttachFile as $key=>$value){   //循环读取
					// $sourfile=$_SERVER['DOCUMENT_ROOT'].__ROOT__.'/'.APP_NAME.'/'.$value['savepath'].$value['savename'];
					 $sourfile=$value['savepath'].'/'.$value['savename'];
					chmod($sourfile, 777);
					 $value['savename']=str_replace(".","_copy.",$value['savename']);
					// $targetfile=$_SERVER['DOCUMENT_ROOT'].__ROOT__.'/'.APP_NAME.'/'.$value['savepath'].$value['savename'];
                     $targetfile=$value['savepath'].'/'.$value['savename'];
					 if(copy($sourfile,$targetfile)){
						 $value['id']=NULL;
						 $value['pcnNo']=$maxno;					 
						 $value['uploadTime']= time();
						 $value['savepath']= 'Uploads/';
						 $Attach->add($value);
                         $initialPreview[]="http://". $_SERVER['HTTP_HOST'].__ROOT__.'/'.APP_NAME."/Uploads/".$value['name'];
						// $initialPreview[]="http://10.120.1.243/webtools/PCN/uploads/".$value['name'];
						 $initialPreviewConfig[]=array("type"=>$value['category'],
													   "size"=>$value['size'],
													   "caption"=>$value['name'],
													   "url"=>"__URL__/del_file",
													   "key"=>$value['recordid'],
													    "filename"=> $value['savename']);
					 }
			   }
			 }
		  if ($initialPreview){
			   $initialPreview= json_encode($initialPreview);
		  }else{
		       $initialPreview=json_encode([]);
			  
		   }
		  if ($initialPreviewConfig){
			  $initialPreviewConfig= json_encode($initialPreviewConfig);
		  }else{
		      $initialPreviewConfig= '[]';
		  }
          $prem=M();
	      $prem1 = $prem->query("SELECT user_name  FROM pcn_userrules where access_rule like 'pre%'");
		  $pcntype = $prem->query("SELECT setting_value  FROM pcn_setting where setting_name= 'pcntype'");
	      $pcntype=explode(',',$pcntype[0]['setting_value']);
		  $this->assign("initialPreview",$initialPreview);
		  $this->assign("initialPreviewConfig",$initialPreviewConfig);
		  $this->assign("userName",$_SESSION['displayname']);
		  $this->assign('vo',$ls);
          $this->assign("mlist",$prem1);
          $this->assign("pcntype",$pcntype);
		  $downurl="'http://". $_SERVER['HTTP_HOST'].__ROOT__.'/'.APP_NAME."/Uploads/{filename}'";
		  $this->assign("downurl",$downurl);
		  $this->display();
	 }
	 public function split_rec(){
          $pcn_no= $this->_get('pcn_no');
		  $Model = M("main"); 
          $data=$Model->where("pcn_no='$pcn_no'")->find();
          $internal_pn = explode(";",$data['internal_pn']);
          $cust_pn =     explode(";",$data['cust_pn']);
          $cust_name=    explode(";", $data['cust_name']);
          $uniqueCust=array_unique($cust_name);
          if (count($uniqueCust)==1){
                 $this->error('TCN一个客户不用拆分!');
          }
         if ($data['splited']==1){
                 $this->error('该PCN已拆分过,不需要再拆分!');
          }
         if ($data['curr_status']!='CCOMP'){
                 $this->error('TCN只有在待客户回复状态下才能拆分!');
          }
          $ip=0 ;
		  foreach($uniqueCust as $key=>$val){
					$ip+=1;
                    $cpn='';
                    $ipn='';
                    $cname='';
                    $sub_pcn_no='';
					foreach($cust_name as $k=>$v){
                      if($val==$v){
                          $cpn.=';'. $cust_pn[$k];
                          $ipn.=';'. $internal_pn[$k];
                          $cname.=';'.$v;
                      }                  
                   }
                 if ($ip<10){
                   $sub_pcn_no= $pcn_no.'00'.$ip; 
                 } else if ($ip<100){
                    $sub_pcn_no= $pcn_no.'0'.$ip; 
                 }else{
                   $sub_pcn_no=  $pcn_no.''.$ip; 
                 }
                /* echo $sub_pcn_no.'  ';
                 echo trim($cpn,";").'<br>';
                 echo trim($ipn,";").'<br>';
                 echo trim($cname,";").'<br>';*/
                 $data['id']='';
                 $data['pcn_no']=$sub_pcn_no;
                 $data['internal_pn']=trim($ipn,";");
                 $data['cust_pn']=trim($cpn,";");
                 $data['cust_name']=trim($cname,";");
                 $data['initiation_date']=date("Y-m-d H:i:s");
                 $data['curr_status']='SPCN';
               //  echo dump($data);
                 $Attach = M('attach');
		         $AttachFile=$Attach->where("pcnNo=$pcn_no and location_attach <>'ccomp'")->select();
                 foreach ($AttachFile as $subkey => $subval){
                       $AttachFile[$subkey]['pcnNo']=$sub_pcn_no; 
                       $AttachFile[$subkey]['id']='';  
                       $Attach->add($AttachFile[$subkey]);          
		         }
                
                 $Model->add($data);
               }
               $update_status['splited']=1;
               $Model->where("pcn_no=$pcn_no")->save($update_status);          
               $this->success('PCN拆分成功!');
        // echo  dump($data);
         //echo $data['id'];
        //  $this->success(L('_OPERATION_SUCCESS_'));
		
	 }
	public function approval(){
	     $pcn_no= $this->_get('pcn_no');
		 $Model = M("main"); 
	     $ls=$Model->where("pcn_no=$pcn_no")->find();
		 $internal_pn = explode(";",$ls['internal_pn']);
         $cust_pn =     explode(";",$ls['cust_pn']);
         $cust_name=    explode(";", $ls['cust_name']);
		 $cctocs=M();
		 $cctocsl = $cctocs->query("SELECT setting_value  FROM pcn_setting where setting_name= 'cc_to_cs_list'");
	     $cctocsl=$cctocsl[0]['setting_value'];
         $cctocsl=
		 $tableDetail="";
		/* $actions='<a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a>
					 <a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>
					 <a class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>';*/
			 foreach($internal_pn as $key=>$val){
					 $tableDetail.="
									<tr>
										<td>$val</td>
										<td>$cust_pn[$key]</td>
										<td>$cust_name[$key]</td>
									</tr>";
			 }
			 $Attach = M('attach');
			 $AttachFile=$Attach->where("pcnNo=$pcn_no and location_attach is null")->select();
			 if(!empty($AttachFile)){ //判断一级是否为空
				 foreach($AttachFile as $key=>$value){   //循环读取
					// $initialPreview[]="http://10.120.1.243/webtools/PCN/uploads/".$value['savename'];
					 $initialPreview[]="http://". $_SERVER['HTTP_HOST'].__ROOT__.'/'.APP_NAME."/Uploads/".$value['name'];
					 $initialPreviewConfig[]=array("type"=>$value['category'],
												   "size"=>$value['size'],
												   "caption"=>$value['name'],
												   //"url"=>"__URL__/del_file",
												   "key"=>$value['recordid'] ,
                                                   "filename"=> $value['savename']);
			   }
			 }
			  $AttachFile=$Attach->where("pcnNo=$pcn_no and location_attach='spcn'")->select();
			 // echo var_dump($AttachFile);
			  $spcnfile='';
			 if(!empty($AttachFile)){ //判断一级是否为空

				 foreach($AttachFile as $key=>$value){   //循环读取
                      // $spcnfle='<table>';
					  $spcnfile.=$this->showExt(strtolower($value['extension'])).'&nbsp;&nbsp;&nbsp;'.'<a href=__URL__/download/id/'.$value['id'].'>'.$value["name"].'</a>   '.$this->byte_format($value['size']).'<img alt="{$Think.lang.del_item}" class="file_del" src="__TMPL__assets/file_extension_image/b_drop.png" id='.$value['id'].'</br>';
                      // $spcnfile.='<tr><td>'.$this->showExt(strtolower($value['extension'])).'</td>'.'<td><a href=__URL__/download/id/'.$value['id'].'>'.$value["name"].'</a></td><td>'.$this->byte_format($value['size']).'</td><td><img alt="{$Think.lang.del_item}" class="file_del" src="__TMPL__assets/file_extension_image/b_drop.png" id='.$value['id'].'</td></tr>';
                      // $spcnfle.='</table>';			   
}
			 }
		  if ($initialPreview){
			   $initialPreview= json_encode($initialPreview);
		  }else{
		       $initialPreview=json_encode([]);
			  
		   }
		  if ($initialPreviewConfig){
			  $initialPreviewConfig= json_encode($initialPreviewConfig);
		  }else{
		      $initialPreviewConfig= '[]';
		  }
		  /*timeLine*/
		  $timeline=' <input type="text" class="form-control" name="curr_status1" id="curr_status1"  style="outline: 0;border-width: 0 0 1px; border-color: light-green"  value="'.$ls['curr_status'].'"  hidden >';
		  $timeline.='<ul class="timeline">';    
          if($ls['curr_status']=='PE'){
				 $timeline.= '<li><div class="tldate">'.date("M Y").'</div></li>
				       <li>
						  <div class="tl-circ"></div>
						  <div class="timeline-panel">
							<div class="tl-heading">
							  <h4>PE Manger Confirm</h4>
							  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s") .'</small></p> 							  
							</div>
							<div class="tl-body">
                                  <!--div class="form-group form-inline"> text和标签在同一行  
	                                   <label for="pe_m_d">日期:</label-->
								      <input type="text" class="form-control" name="pe_m_d" id="pe_m_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. date("Y-m-d H:i:s").'" readonly hidden >
                                   <!--/div-->
								                       
								     <input type="text" class="form-control"  aria-lable="Singal" name="pe_m" id="pe_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $_SESSION['displayname'].'" readonly>
									 <input type="text" class="form-control" name="pe_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="outline: 0;border-width: 0 0 1px; border-color: light-green">
									 <input type="text" class="form-control" name="curr_status" id="curr_status"  style="outline: 0;border-width: 0 0 1px; border-color: light-green"  value="PRE"  hidden >
                                 
							</div>
						  </div>
						</li>';
		  }else if($ls['curr_status']=='PRE'){
		  
		       $timeline.= '<li><div class="tldate">'. date("M Y",strtotime($ls['pe_m_d'])).'</div></li>
				          <li>
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PE Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['pe_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="pe_m" id="pe_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['pe_m'].'" readonly>
										 <input type="text" class="form-control" name="pe_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["pe_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
					  if(date("M Y",strtotime($ls['pe_m_d']))<>date("M Y")) {
					         $timeline.='<li><div class="tldate">'.date("M Y").'</div></li>';
					  	 }
			    $timeline.= '<li class="timeline-inverted">
						  <div class="tl-circ"></div>
						  <div class="timeline-panel">
							<div class="tl-heading">
							  <h4>PRE Manger Confirm</h4>
							  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s") .'</small></p> 							  
							</div>
							<div class="tl-body">
								      <input type="text" class="form-control" name="pre_m_d" id="pre_m_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. date("Y-m-d H:i:s").'" readonly hidden >
								     <input type="text" class="form-control"  aria-lable="Singal" name="pre_m" id="pre_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $_SESSION['displayname'].'" readonly>
									 <input type="text" class="form-control" name="pre_m_r" id="pre_m_r" placeholder="'.L('affect_remark').'" style="outline: 0;border-width: 0 0 1px; border-color: light-green">
									 <input type="text" class="form-control" name="curr_status" id="curr_status"  style="outline: 0;border-width: 0 0 1px; border-color: light-green"  value="QA"  hidden >
                                 
							</div>
						  </div>
						</li>';
		  
		    }else if($ls['curr_status']=='QA'){
			
			    $timeline.= '<li><div class="tldate">'. date("M Y",strtotime($ls['pe_m_d'])).'</div></li>
				          <li>
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PE Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['pe_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="pe_m" id="pe_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['pe_m'].'" readonly>
										 <input type="text" class="form-control" name="pe_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green;text-wrap:normal;" value="'.$ls["pe_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
					  if(date("M Y",strtotime($ls['pe_m_d']))<>date("M Y",strtotime($ls['pre_m_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['pre_m_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li class="timeline-inverted">
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PRE Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['pre_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="pre_m" id="pre_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['pre_m'].'" readonly>
										 <input type="text" class="form-control" name="pre_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["pre_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
					  if(date("M Y",strtotime($ls['pre_m_d']))<>date("M Y")) {
					         $timeline.='<li><div class="tldate">'.date("M Y").'</div></li>';
					  	 }
			    $timeline.= '<li>
						  <div class="tl-circ"></div>
						  <div class="timeline-panel">
							<div class="tl-heading">
							  <h4>QA Manger Confirm</h4>
							  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s") .'</small></p> 							  
							</div>
							<div class="tl-body">
								      <input type="text" class="form-control" name="qa_m_d" id="qa_m_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. date("Y-m-d H:i:s").'" readonly hidden >
								     <input type="text" class="form-control"  aria-lable="Singal" name="qa_m" id="qa_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $_SESSION['displayname'].'" readonly>
									 <input type="text" class="form-control" name="qa_m_r" id="qa_m_r" placeholder="'.L('affect_remark').'" style="outline: 0;border-width: 0 0 1px; border-color: light-green">
									 <input type="text" class="form-control" name="curr_status" id="curr_status"  style="outline: 0;border-width: 0 0 1px; border-color: light-green"  value="ENGD"  hidden >
                                 
							</div>
						  </div>
						</li>';
			
			
			 }else if($ls['curr_status']=='ENGD'){

                        $timeline.= '<li><div class="tldate">'. date("M Y",strtotime($ls['pe_m_d'])).'</div></li>
				          <li>
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PE Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['pe_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="pe_m" id="pe_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['pe_m'].'" readonly>
										 <input type="text" class="form-control" name="pe_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green;text-wrap:normal;" value="'.$ls["pe_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
					  if(date("M Y",strtotime($ls['pe_m_d']))<>date("M Y",strtotime($ls['pre_m_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['pre_m_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li class="timeline-inverted">
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PRE Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['pre_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="pre_m" id="pre_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['pre_m'].'" readonly>
										 <input type="text" class="form-control" name="pre_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["pre_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
                       if(date("M Y",strtotime($ls['pre_m_d']))<>date("M Y",strtotime($ls['qa_m_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['qa_m_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li>
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>QA Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['qa_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="qa_m" id="qa_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['qa_m'].'" readonly>
										 <input type="text" class="form-control" name="qa_m_r" id="qa_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["qa_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';

					  if(date("M Y",strtotime($ls['qa_m_d']))<>date("M Y")) {
					         $timeline.='<li><div class="tldate">'.date("M Y").'</div></li>';
					  	 }
			    $timeline.= '<li class="timeline-inverted">
						  <div class="tl-circ"></div>
						  <div class="timeline-panel">
							<div class="tl-heading">
							  <h4>Engineering Director Confirm</h4>
							  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s") .'</small></p> 							  
							</div>
							<div class="tl-body">
								      <input type="text" class="form-control" name="engi_d_d" id="engi_d_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. date("Y-m-d H:i:s").'" readonly hidden >
								     <input type="text" class="form-control"  aria-lable="Singal" name="engi_d" id="engi_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $_SESSION['displayname'].'" readonly>
									 <input type="text" class="form-control" name="engi_d_r" id="geni_d_r" placeholder="'.L('affect_remark').'" style="outline: 0;border-width: 0 0 1px; border-color: light-green">
									 <input type="text" class="form-control" name="curr_status" id="curr_status"  style="outline: 0;border-width: 0 0 1px; border-color: light-green"  value="MFGD"  hidden >
                                 
							</div>
						  </div>
						</li>';
			  }else if($ls['curr_status']=='MFGD'){

                        $timeline.= '<li><div class="tldate">'. date("M Y",strtotime($ls['pe_m_d'])).'</div></li>
				          <li>
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PE Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['pe_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="pe_m" id="pe_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['pe_m'].'" readonly>
										 <input type="text" class="form-control" name="pe_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green;text-wrap:normal;" value="'.$ls["pe_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
					  if(date("M Y",strtotime($ls['pe_m_d']))<>date("M Y",strtotime($ls['pre_m_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['pre_m_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li class="timeline-inverted">
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PRE Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['pre_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="pre_m" id="pre_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['pre_m'].'" readonly>
										 <input type="text" class="form-control" name="pre_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["pre_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
                       if(date("M Y",strtotime($ls['pre_m_d']))<>date("M Y",strtotime($ls['qa_m_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['qa_m_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li>
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>QA Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['qa_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="qa_m" id="qa_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['qa_m'].'" readonly>
										 <input type="text" class="form-control" name="qa_m_r" id="qa_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["qa_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>'; 
						 if(date("M Y",strtotime($ls['qa_m_d']))<>date("M Y",strtotime($ls['engi_d_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['engi_d_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li class="timeline-inverted">
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>Engineering Director Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['engi_d_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="engi_d" id="engi_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['engi_d'].'" readonly>
										 <input type="text" class="form-control" name="engi_d_r" id="engi_d_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["engi_d_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';

					  if(date("M Y",strtotime($ls['engi_d_d']))<>date("M Y")) {
					         $timeline.='<li><div class="tldate">'.date("M Y").'</div></li>';
					  	 }
			    $timeline.= '<li>
						  <div class="tl-circ"></div>
						  <div class="timeline-panel">
							<div class="tl-heading">
							  <h4>MFG Director Confirm</h4>
							  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s") .'</small></p> 							  
							</div>
							<div class="tl-body">
								      <input type="text" class="form-control" name="prod_d_d" id="prod_d_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. date("Y-m-d H:i:s").'" readonly hidden >
								     <input type="text" class="form-control"  aria-lable="Singal" name="prod_d" id="prod_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $_SESSION['displayname'].'" readonly>
									 <input type="text" class="form-control" name="prod_d_r" id="prod_d_r" placeholder="'.L('affect_remark').'" style="outline: 0;border-width: 0 0 1px; border-color: light-green">
									 <input type="text" class="form-control" name="curr_status" id="curr_status"  style="outline: 0;border-width: 0 0 1px; border-color: light-green"  value="QAD"  hidden >
                                 
							</div>
						  </div>
						</li>';
			  }else if($ls['curr_status']=='QAD'||$ls['curr_status']=='SPCN'||$ls['curr_status']=='CCOMP'){

                        $timeline.= '<li><div class="tldate">'. date("M Y",strtotime($ls['pe_m_d'])).'</div></li>
				          <li>
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PE Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['pe_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="pe_m" id="pe_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['pe_m'].'" readonly>
										 <input type="text" class="form-control" name="pe_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green;text-wrap:normal;" value="'.$ls["pe_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
					  if(date("M Y",strtotime($ls['pe_m_d']))<>date("M Y",strtotime($ls['pre_m_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['pre_m_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li class="timeline-inverted">
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PRE Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['pre_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="pre_m" id="pre_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['pre_m'].'" readonly>
										 <input type="text" class="form-control" name="pre_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["pre_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
                       if(date("M Y",strtotime($ls['pre_m_d']))<>date("M Y",strtotime($ls['qa_m_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['qa_m_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li>
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>QA Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['qa_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="qa_m" id="qa_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['qa_m'].'" readonly>
										 <input type="text" class="form-control" name="qa_m_r" id="qa_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["qa_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>'; 
						 if(date("M Y",strtotime($ls['qa_m_d']))<>date("M Y",strtotime($ls['engi_d_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['engi_d_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li class="timeline-inverted">
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>Engineering Director Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['engi_d_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="engi_d" id="engi_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['engi_d'].'" readonly>
										 <input type="text" class="form-control" name="engi_d_r" id="engi_d_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["engi_d_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
                	 if(date("M Y",strtotime($ls['prod_d_d']))<>date("M Y",strtotime($ls['engi_d_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['prod_d_d'])).'</div></li>';
					  	 }
						 
			       $timeline.= '
				          <li >
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>MFG Director Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['prod_d_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="prod_d" id="prod_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['prod_d'].'" readonly>
										 <input type="text" class="form-control" name="prod_d_r" id="prod_d_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["prod_d_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
					if ($ls['curr_status']=='QAD'){
							  if(date("M Y",strtotime($ls['engi_d_d']))<>date("M Y")) {
									 $timeline.='<li><div class="tldate">'.date("M Y").'</div></li>';
								 }			  
							 $timeline.= '<li class="timeline-inverted">
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>QA Director Confirm</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s") .'</small></p> 							  
									</div>
									<div class="tl-body">
											  <input type="text" class="form-control" name="qa_d_d" id="qa_d_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. date("Y-m-d H:i:s").'" readonly hidden >
											 <input type="text" class="form-control"  aria-lable="Singal" name="qa_d" id="qa_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $_SESSION['displayname'].'" readonly>
											 <input type="text" class="form-control" name="qa_d_r" id="qa_d_r" placeholder="'.L('affect_remark').'" style="outline: 0;border-width: 0 0 1px; border-color: light-green">
											 <input type="text" class="form-control" name="curr_status" id="curr_status"  style="outline: 0;border-width: 0 0 1px; border-color: light-green"  value="SPCN"  hidden >
										 
									</div>
								  </div>
								</li>';
					}else if($ls['curr_status']=='SPCN'){
						  if(date("M Y",strtotime($ls['qa_d_d']))<>date("M Y",strtotime($ls['engi_d_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['qa_d_d'])).'</div></li>';
					  	 }	  
							 $timeline.= '<li class="timeline-inverted">
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>QA Director Confirm</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['qa_d_d'])).'</small></p> 							  
									</div>
									<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="qa_d" id="qa_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['qa_d'].'" readonly>
										 <input type="text" class="form-control" name="qa_d_r" id="prod_d_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["qa_d_r"].'"  readonly>								 
								</div>
								  </div>
								</li>';
								 if(date("M Y",strtotime($ls['qa_d_d']))<>date("M Y")) {
									 $timeline.='<li><div class="tldate">'.date("M Y").'</div></li>';
								 }			  
							    $timeline.= '<li>
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>Sending Customer</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s") .'</small></p> 
									  
									 </div>																												      	
                                    		
									<div class="tl-body">
                                            <div class="form-group form-check">
												<input type="checkbox" class="form-check-input"  name="nform" id="nform">
												<label class="form-check-label" for="nform" style="font-size:12px;">Not Needing PCN Excel Form</label>
											 </div>	
									      <div class="input-group">									          
										      <input type="text" class="form-control" name="mail_to_list" id="mail_to_list" placeholder="'.L('mail_to').'" value="'.$ls['mail_to_list'].'" style="font-size:12px;outline: 0;border-width: 0 0 1px; border-color: light-green;float:left">
									          <div class="input-group-addon">
										         <span class="fas fa-envelope" id ="sendMail" style="font-size:22px;"></span>
									          </div>  
                                           </div>	
                                          
										  <div style="margin-top:10px;margin-bottom:10px;">
										      <table class="file_table" style="font-size:12px;"></table>
								              <div class ="row">
                                                     <div class="col-sm-9">'.$spcnfile.'</div>
                                                     <div class="col-sm-3"><button type="button" id="upload_button" class="btn btn-info btn-sm"  style="margin-top:10px;float:right">Browse</button></div>
                                              </div>
				                               
				                         </div>									
									   
										     <div class ="row">
                                                     <div class="col-sm-4">  <lable for="datepicker" style="font-size:14px;"> '.L('response_date').':</lable></div>
                                                     <div class="col-sm-8"><input clase="c" type="text" name="expected_reply_date"  id="datepicker" style="font-size:14px;" value="'.$ls['expected_reply_date'].'" /></div>
                                              </div>
											 <input type="text" class="form-control" name="s_c_d" id="s_c_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. date("Y-m-d H:i:s").'" readonly hidden >
											 <input type="text" class="form-control"  aria-lable="Singal" name="s_c_o" id="s_c_o" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $_SESSION['displayname'].'" readonly>
											 <input type="text" class="form-control" name="s_c_r" id="s_c_r" placeholder="'.L('affect_remark').'" style="outline: 0;border-width: 0 0 1px; border-color: light-green">
											 <input type="text" class="form-control" name="curr_status" id="curr_status"  style="outline: 0;border-width: 0 0 1px; border-color: light-green"  value="CCOMP"  hidden >
										 
									</div>
								  </div>
								 
								</li>';
				       
				   }else if($ls['curr_status']=='CCOMP'){
				 
				     if(date("M Y",strtotime($ls['qa_d_d']))<>date("M Y",strtotime($ls['engi_d_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['qa_d_d'])).'</div></li>';
					  	 }	  
							 $timeline.= '<li class="timeline-inverted">
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>QA Director Confirm</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['qa_d_d'])).'</small></p> 							  
									</div>
									<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="qa_d" id="qa_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['qa_d'].'" readonly>
										 <input type="text" class="form-control" name="qa_d_r" id="prod_d_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["qa_d_r"].'"  readonly>								 
								</div>
								  </div>
								</li>';
						  if(date("M Y",strtotime($ls['qa_d_d']))<>date("M Y",strtotime($ls['s_c_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['s_c_d'])).'</div></li>';
					     	 }	  
							 $timeline.= '<li>
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>Sending Customer</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['s_c_d'])).'</small></p> 							  
									</div>
									 <div class="input-group">
									          <div class="input-group-addon">
										         <span class="fas fa-envelope" style="color:	green; background-color: #ffffff;font-size:15px;"></span>
									          </div>
											   <input type="text" class="form-control" name="mail_to_list" id="mail_to_list" placeholder="'.L('mail_to').'" style="font-size:12px;background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green;float:right" value="'.$ls["mail_to_list"].'"  readonly>								 
									      </div>	
									<div class="tl-body">  
										<present name="attachs">
											<fieldset  style="width:95%;margin:8px;color:gray">
											<legend style="border:none;font-size:16px;">'.L('attachments').':</legend>
											<div style="font-size:12px;">
											 '. $spcnfile.'
										 </div>
										</fieldset>
										</present>
										 <input type="text" class="form-control"  aria-lable="Singal" name="s_c_o" id="s_c_o" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['s_c_o'].'" readonly>
										 <input type="text" class="form-control" name="s_c_r" id="s_c_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["s_c_r"].'"  readonly>								 
								  </div>
								  </div>
								</li>';
								 if(date("M Y",strtotime($ls['s_c_d']))<>date("M Y")) {
									 $timeline.='<li><div class="tldate">'.date("M Y").'</div></li>';
								 }			  
							 $timeline.= '<li class="timeline-inverted">
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>Customer Replying</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s") .'</small></p> 							  
									</div>
									  <div style="margin-top:10px;margin-bottom:10px;">
										      <table class="file_table" style="font-size:12px;"></table>
								              <div class ="row">
                                                     <div class="col-sm-9"></div>
                                                     <div class="col-sm-3"><button type="button" id="upload_rcd" class="btn btn-info btn-sm"  style="margin-top:10px;float:right">Browse</button></div>
                                              </div>
				                               
				                         </div>					
									<div class="tl-body">
											  <input type="text" class="form-control" name="r_c_d" id="r_c_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. date("Y-m-d H:i:s").'" readonly hidden >
											 <input type="text" class="form-control"  aria-lable="Singal" name="r_c_o" id="r_c_o" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $_SESSION['displayname'].'" readonly>
											 <input type="text" class="form-control" name="r_c_r" id="r_c_r" placeholder="'.L('affect_remark').'" style="outline: 0;border-width: 0 0 1px; border-color: light-green">
											 <input type="text" class="form-control" name="curr_status" id="curr_status"  style="outline: 0;border-width: 0 0 1px; border-color: light-green"  value="PEE"  hidden >
										 
									</div>
								  </div>
								</li>';
				 }
			 }

		
          $timeline.='</ul>';
		  $this->assign("timeline",$timeline);
		  $this->assign("initialPreview",$initialPreview);
		  $this->assign("initialPreviewConfig",$initialPreviewConfig);
		  $this->assign("userName",$_SESSION['displayname']);
		  $this->assign('vo',$ls);
		  $this->assign("tableDetail",$tableDetail);
		  $downurl="'http://". $_SERVER['HTTP_HOST'].__ROOT__.'/'.APP_NAME."/Uploads/{filename}'";
		  $this->assign("downurl",$downurl);
		  $this->display();
	 }
public function download()
    {
        import("@.ORG.Http");
        $id         =   $_GET['id'];
        $dao        =   M("Attach");
        $attach	    =   $dao->where("id=".$id)->find();
        $filename   =   $attach["savepath"].$attach["savename"];
        if(is_file($filename)) {			
             Http::download($filename,auto_charset($attach["name"],'utf-8','gbk'));
        }
    }
public function view_detail(){
	     $pcn_no= $this->_get('pcn_no');
		 $Model = M("main"); 
	     $ls=$Model->where("pcn_no=$pcn_no")->find();
		 $internal_pn = explode(";",$ls['internal_pn']);
         $cust_pn =     explode(";",$ls['cust_pn']);
         $cust_name=    explode(";", $ls['cust_name']);
		 $exec_status =     explode(";",$ls['exec_status']);
         $exec_date=    explode(";", $ls['exec_date']);
		 $exec_user=    explode(";", $ls['exec_user']);
         $exec_remark=    explode(";", $ls['exec_remark']);
		 $n_internal_pn=explode(";", $ls['n_internal_pn']);
		 $ptl_no=       explode(";", $ls['ptl_no']);
		 $tableDetail="";
		   $actions='<a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a>
					 <a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>';
					 //<a class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>';
			 foreach($internal_pn as $key=>$val){
					$tableDetail.="
									<tr>
										<td>$val</td>
										<td>$cust_pn[$key]</td>
										<td>$cust_name[$key]</td>
										<td>$n_internal_pn[$key]</td>
										<td>$ptl_no[$key]</td>
										";
										if ($exec_status[$key]=='true'){
								             $tableDetail.="<td><input type='checkbox' checked disabled></td>";
										}else{                                      
									         $tableDetail.="<td><input type='checkbox' disabled></td>";
			                            } 									
									$tableDetail.="<td>$exec_user[$key]</td>
										<td>$exec_date[$key]</td>
										<td>$exec_remark[$key]</td>
									
									   </tr>";
			 }
			 
			 $Attach = M('attach');
			 $AttachFile=$Attach->where("pcnNo=$pcn_no and location_attach is null")->select();
			 if(!empty($AttachFile)){ //判断一级是否为空
				 foreach($AttachFile as $key=>$value){   //循环读取
					// $initialPreview[]=urldecode("http://10.120.1.243/webtools/PCN/uploads/".$value['savename']);
					  $initialPreview[]="http://". $_SERVER['HTTP_HOST'].__ROOT__.'/'.APP_NAME."/Uploads/".$value['name'];
					 $initialPreviewConfig[]=array("type"=>$value['category'],
												   "size"=>$value['size'],
												   "caption"=>$value['name'],
												   //"url"=>"__URL__/del_file",
												   "key"=>$value['recordid'] ,    
												   "filename"=> $value['savename'] );
			   }
			 }
		  $AttachFile=$Attach->where("pcnNo=$pcn_no and location_attach='spcn'")->select();
		 // echo var_dump($AttachFile);
		  $spcnfile='';
		 if(!empty($AttachFile)){ //判断一级是否为空
			 foreach($AttachFile as $key=>$value){   //循环读取
				 $spcnfile.=$this->showExt(strtolower($value['extension'])).'&nbsp;&nbsp;&nbsp;'.'<a href=__URL__/download/id/'.$value['id'].'>'.$value["name"].'</a>   '.$this->byte_format($value['size']).'</br>';
		   }
		 }
		 $AttachFile=$Attach->where("pcnNo=$pcn_no and location_attach='ccomp'")->select();
		 $ccomp='';
		 if(!empty($AttachFile)){ //判断一级是否为空
			 foreach($AttachFile as $key=>$value){   //循环读取
				 $ccomp.=$this->showExt(strtolower($value['extension'])).'&nbsp;&nbsp;&nbsp;'.'<a href=__URL__/download/id/'.$value['id'].'>'.$value["name"].'</a>   '.$this->byte_format($value['size']).'</br>';
		   }
		 }
		  if ($initialPreview){
			   $initialPreview= json_encode($initialPreview);
		  }else{
		       $initialPreview=json_encode([]);
			  
		   }
		  if ($initialPreviewConfig){
			  $initialPreviewConfig= json_encode($initialPreviewConfig);
		  }else{
		      $initialPreviewConfig= '[]';
		  }
		 // $DownloadUrl='http://10.120.1.243/webtools/PCN/Uploads/{filename}';
		  $DownloadUrl="http://". $_SERVER['HTTP_HOST'].__ROOT__.'/'.APP_NAME."/uploads/".'{filename}';
		  /*timeLine*/
		  $timeline='<ul class="timeline">';    
          if($ls['curr_status']=='PE' ||strpos($ls['curr_status'],'PE/Reject') !== false){
				
		  }else if($ls['curr_status']=='PRE'||strpos($ls['curr_status'],'PRE/Reject') !== false){
		  
		       $timeline.= '<li><div class="tldate">'. date("M Y",strtotime($ls['pe_m_d'])).'</div></li>
				          <li>
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PE Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['pe_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="pe_m" id="pe_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['pe_m'].'" readonly>
										 <input type="text" class="form-control" name="pe_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["pe_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';				  		  
		    }else if($ls['curr_status']=='QA' ||strpos($ls['curr_status'],'QA/Reject') !== false){
			
			    $timeline.= '<li><div class="tldate">'. date("M Y",strtotime($ls['pe_m_d'])).'</div></li>
				          <li>
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PE Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['pe_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="pe_m" id="pe_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['pe_m'].'" readonly>
										 <input type="text" class="form-control" name="pe_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green;text-wrap:normal;" value="'.$ls["pe_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
					  if(date("M Y",strtotime($ls['pe_m_d']))<>date("M Y",strtotime($ls['pre_m_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['pre_m_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li class="timeline-inverted">
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PRE Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['pre_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="pre_m" id="pre_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['pre_m'].'" readonly>
										 <input type="text" class="form-control" name="pre_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["pre_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';					  
				 }else if($ls['curr_status']=='ENGD'||strpos($ls['curr_status'],'ENGD/Reject') !== false){

                        $timeline.= '<li><div class="tldate">'. date("M Y",strtotime($ls['pe_m_d'])).'</div></li>
				          <li>
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PE Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['pe_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="pe_m" id="pe_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['pe_m'].'" readonly>
										 <input type="text" class="form-control" name="pe_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green;text-wrap:normal;" value="'.$ls["pe_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
					  if(date("M Y",strtotime($ls['pe_m_d']))<>date("M Y",strtotime($ls['pre_m_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['pre_m_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li class="timeline-inverted">
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PRE Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['pre_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="pre_m" id="pre_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['pre_m'].'" readonly>
										 <input type="text" class="form-control" name="pre_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["pre_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
                       if(date("M Y",strtotime($ls['pre_m_d']))<>date("M Y",strtotime($ls['qa_m_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['qa_m_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li>
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>QA Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['qa_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="qa_m" id="qa_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['qa_m'].'" readonly>
										 <input type="text" class="form-control" name="qa_m_r" id="qa_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["qa_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
					 
			  }else if($ls['curr_status']=='MFGD'||strpos($ls['curr_status'],'MFGD/Reject') !== false){

                        $timeline.= '<li><div class="tldate">'. date("M Y",strtotime($ls['pe_m_d'])).'</div></li>
				          <li>
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PE Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['pe_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="pe_m" id="pe_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['pe_m'].'" readonly>
										 <input type="text" class="form-control" name="pe_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green;text-wrap:normal;" value="'.$ls["pe_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
					  if(date("M Y",strtotime($ls['pe_m_d']))<>date("M Y",strtotime($ls['pre_m_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['pre_m_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li class="timeline-inverted">
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PRE Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['pre_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="pre_m" id="pre_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['pre_m'].'" readonly>
										 <input type="text" class="form-control" name="pre_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["pre_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
                       if(date("M Y",strtotime($ls['pre_m_d']))<>date("M Y",strtotime($ls['qa_m_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['qa_m_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li>
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>QA Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['qa_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="qa_m" id="qa_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['qa_m'].'" readonly>
										 <input type="text" class="form-control" name="qa_m_r" id="qa_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["qa_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>'; 
						 if(date("M Y",strtotime($ls['qa_m_d']))<>date("M Y",strtotime($ls['engi_d_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['engi_d_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li class="timeline-inverted">
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>Engineering Director Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['engi_d_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="engi_d" id="engi_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['engi_d'].'" readonly>
										 <input type="text" class="form-control" name="engi_d_r" id="engi_d_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["engi_d_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
					 
			  }else if(($ls['curr_status']=='QAD'||$ls['curr_status']=='SPCN'||$ls['curr_status']=='CCOMP'||$ls['curr_status']=='PEE' ||$ls['curr_status']=='COMPLETION') || strpos($ls['curr_status'],'Reject') !== false){
                        $timeline.= '<li><div class="tldate">'. date("M Y",strtotime($ls['pe_m_d'])).'</div></li>
				          <li>
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PE Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['pe_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="pe_m" id="pe_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['pe_m'].'" readonly>
										 <input type="text" class="form-control" name="pe_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green;text-wrap:normal;" value="'.$ls["pe_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
					  if(date("M Y",strtotime($ls['pe_m_d']))<>date("M Y",strtotime($ls['pre_m_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['pre_m_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li class="timeline-inverted">
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PRE Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['pre_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="pre_m" id="pre_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['pre_m'].'" readonly>
										 <input type="text" class="form-control" name="pre_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["pre_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
                       if(date("M Y",strtotime($ls['pre_m_d']))<>date("M Y",strtotime($ls['qa_m_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['qa_m_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li>
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>QA Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['qa_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="qa_m" id="qa_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['qa_m'].'" readonly>
										 <input type="text" class="form-control" name="qa_m_r" id="qa_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["qa_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>'; 
						 if(date("M Y",strtotime($ls['qa_m_d']))<>date("M Y",strtotime($ls['engi_d_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['engi_d_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li class="timeline-inverted">
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>Engineering Director Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['engi_d_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="engi_d" id="engi_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['engi_d'].'" readonly>
										 <input type="text" class="form-control" name="engi_d_r" id="engi_d_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["engi_d_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
                	 if(date("M Y",strtotime($ls['prod_d_d']))<>date("M Y",strtotime($ls['engi_d_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['prod_d_d'])).'</div></li>';
					  	 }
						 
			       $timeline.= '
				          <li >
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>MFG Director Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['prod_d_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="prod_d" id="prod_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['prod_d'].'" readonly>
										 <input type="text" class="form-control" name="prod_d_r" id="prod_d_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["prod_d_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
					if ($ls['curr_status']=='QAD'||strpos($ls['curr_status'],'QAD/Reject') !== false){
							  if(date("M Y",strtotime($ls['engi_d_d']))<>date("M Y")) {
									 $timeline.='<li><div class="tldate">'.date("M Y").'</div></li>';
								 }			  
							 $timeline.= '<li class="timeline-inverted">
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>QA Director Confirm</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s") .'</small></p> 							  
									</div>
									<div class="tl-body">
											 <input type="text" class="form-control"  aria-lable="Singal" name="qa_d" id="qa_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['qa_d'].'" readonly>
											 <input type="text" class="form-control" name="qa_d_r" id="prod_d_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["qa_d_r"].'"  readonly>
										 
									</div>
								  </div>
								</li>';
					}else if($ls['curr_status']=='SPCN' ||strpos($ls['curr_status'],'SPCN/Reject') !== false){
						  
						  if(date("M Y",strtotime($ls['qa_d_d']))<>date("M Y",strtotime($ls['engi_d_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['qa_d_d'])).'</div></li>';
					  	 }	  
							 $timeline.= '<li class="timeline-inverted">
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>QA Director Confirm</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['qa_d_d'])).'</small></p> 							  
									</div>
									<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="qa_d" id="qa_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['qa_d'].'" readonly>
										 <input type="text" class="form-control" name="qa_d_r" id="prod_d_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["qa_d_r"].'"  readonly>								 
								</div>
								  </div>
								</li>';
				            if(date("M Y",strtotime($ls['qa_d_d']))<>date("M Y",strtotime($ls['s_c_d']))) {
					             $timeline.='<li><div class="tldate>'.date("M Y",strtotime($ls['s_d_d'])).'</div></li>';
					     	 }	  
							 $timeline.= '<li>
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>Sending Customer</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['s_c_d'])).'</small></p> 							  
									</div>
									<div class="input-group">
									          <div class="input-group-addon">
										         <span class="fas fa-envelope" style="color:	green; background-color: #ffffff;font-size:15px;"></span>
									          </div>
											   <input type="text" class="form-control" name="mail_to_list" id="mail_to_list" placeholder="'.L('mail_to').'" style="font-size:12px;background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green;float:right" value="'.$ls["mail_to_list"].'"  readonly>								 
									   </div>	
									<div class="tl-body">  
										<present name="attachs">
											<fieldset  style="width:95%;margin:8px;color:gray">
											<legend style="border:none;font-size:16px;">'.L('attachments').':</legend>
											  <div style="font-size:12px;padding-left:20px;">
											      '. $spcnfile.'
										        </div>
										    </fieldset>
										</present>
                                     </div>
                                  <div class ="row">
                                             <div class="col-sm-4">  <lable for="datepicker" style="font-size:14px;"> '.L('response_date').':</lable></div>
                                           <div class="col-sm-8"> <input type="text" class="form-control" name="expected_reply_date" id="expected_reply_date" placeholder="'.L('response_date').'" style="font-size:12px;background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green;float:right" value="'.$ls["expected_reply_date"].'"  readonly></div>
                                  </div>
									<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="s_c_o" id="s_c_o" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['s_c_o'].'" readonly>
										 <input type="text" class="form-control" name="s_c_r" id="prod_d_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["s_c_r"].'"  readonly>								 
								</div>
								  </div>
								</li>';	
				       
				   }else if($ls['curr_status']=='CCOMP' ||strpos($ls['curr_status'],'CCOMP/Reject') !== false){
				       // echo '<script>alert("fdsfdfdfd");</script>';
				     if(date("M Y",strtotime($ls['qa_d_d']))<>date("M Y",strtotime($ls['engi_d_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['qa_d_d'])).'</div></li>';
					  	 }	  
							 $timeline.= '<li class="timeline-inverted">
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>QA Director Confirm</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['qa_d_d'])).'</small></p> 							  
									</div>
									<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="qa_d" id="qa_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['qa_d'].'" readonly>
										 <input type="text" class="form-control" name="qa_d_r" id="prod_d_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["qa_d_r"].'"  readonly>								 
								</div>
								  </div>
								</li>';
						  if(date("M Y",strtotime($ls['qa_d_d']))<>date("M Y",strtotime($ls['s_c_d']))) {
					         $timeline.='<li><div class="tldate>'.date("M Y",strtotime($ls['s_d_d'])).'</div></li>';
					     	 }	  
							 $timeline.= '<li>
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>Sending Customer</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['s_c_d'])).'</small></p> 							  
									</div>
									<div class="input-group">
									          <div class="input-group-addon">
										         <span class="fas fa-envelope" style="color:	green; background-color: #ffffff;font-size:15px;"></span>
									          </div>
											   <input type="text" class="form-control" name="mail_to_list" id="mail_to_list" placeholder="'.L('mail_to').'" style="font-size:12px;background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green;float:right" value="'.$ls["mail_to_list"].'"  readonly>								 
									   </div>	
									<div class="tl-body">  
										<present name="attachs">
											<fieldset  style="width:95%;margin:8px;color:gray">
											<legend style="border:none;font-size:16px;">'.L('attachments').':</legend>
											  <div style="font-size:12px;padding-left:20px;">
											      '. $spcnfile.'
										        </div>
										    </fieldset>
										</present>
                                     </div>
									<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="s_c_o" id="s_c_o" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['s_c_o'].'" readonly>
										 <input type="text" class="form-control" name="s_c_r" id="prod_d_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["s_c_r"].'"  readonly>								 
								</div>
								  </div>
								</li>';
								 if(date("M Y",strtotime($ls['qa_d_d']))<>date("M Y")) {
									 $timeline.='<li><div class="tldate">'.date("M Y").'</div></li>';
								 }			  
							 $timeline.= '<li class="timeline-inverted">
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>Customer Replying</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s") .'</small></p> 							  
									</div>
									<div class="tl-body">								
											 <input type="text" class="form-control"  aria-lable="Singal" name="r_c_o" id="r_c_o" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['r_c_o'].'" readonly>
											 <input type="text" class="form-control" name="r_c_r" id="r_c_r" placeholder="'.L('affect_remark').'" style="outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["r_c_r"].'"  readonly>		
											 
										 
									</div>
								  </div>
								</li>';
				  }else if($ls['curr_status']=='PEE'||strpos($ls['curr_status'],'PEE/Reject') !== false ||$ls['curr_status']=='COMPLETION'){
				 
				     if(date("M Y",strtotime($ls['qa_d_d']))<>date("M Y",strtotime($ls['engi_d_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['qa_d_d'])).'</div></li>';
					  	 }	  
							 $timeline.= '<li class="timeline-inverted">
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>QA Director Confirm</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['qa_d_d'])).'</small></p> 							  
									</div>
									<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="qa_d" id="qa_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['qa_d'].'" readonly>
										 <input type="text" class="form-control" name="qa_d_r" id="prod_d_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["qa_d_r"].'"  readonly>								 
								</div>
								  </div>
								</li>';
						  if(date("M Y",strtotime($ls['qa_d_d']))<>date("M Y",strtotime($ls['s_c_d']))) {
					         $timeline.='<li><div  class="tldate">'.date("M Y",strtotime($ls['s_c_d'])).'</div></li>';
							  
					     	 }	  
							 $timeline.= '<li>
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>Sending Customer</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['s_c_d'])).'</small></p> 							  
									</div>
									<div class="input-group">
									          <div class="input-group-addon">
										         <span class="fas fa-envelope" style="color:	green; background-color: #ffffff;font-size:15px;"></span>
									          </div>
											   <input type="text" class="form-control" name="mail_to_list" id="mail_to_list" placeholder="'.L('mail_to').'" style="font-size:12px;background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green;float:right" value="'.$ls["mail_to_list"].'"  readonly>								 
									   </div>	
									<div class="tl-body">  
										<present name="attachs">
											<fieldset  style="width:95%;margin:8px;color:gray">
											<legend style="border:none;font-size:16px;">'.L('attachments').':</legend>
											  <div style="font-size:12px;padding-left:20px;">
											      '. $spcnfile.'
										        </div>
										    </fieldset>
										</present>
                                     </div>
									<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="s_c_o" id="s_c_o" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['s_c_o'].'" readonly>
										 <input type="text" class="form-control" name="s_c_r" id="prod_d_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["s_c_r"].'"  readonly>								 
								</div>
								  </div>
								</li>';
							 if(date("M Y",strtotime($ls['r_c_d']))<>date("M Y",strtotime($ls['s_c_d']))) {
					                 $timeline.='<li><div  class="tldate">'.date("M Y",strtotime($ls['r_c_d'])).'</div></li>';
							  }	  
							 $timeline.= '<li class="timeline-inverted">
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>Customer Replying</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['r_c_d'])) .'</small></p> 							  
									</div>
									 <present name="attachs">
											<fieldset  style="width:95%;margin:8px;color:gray">
											<legend style="border:none;font-size:16px;">'.L('attachments').':</legend>
											  <div style="font-size:12px; padding-left:20px;">
											      '. $ccomp.'
										        </div>
										    </fieldset>
								   </present>
									<div class="tl-body">								
											 <input type="text" class="form-control"  aria-lable="Singal" name="r_c_o" id="r_c_o" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['r_c_o'].'" readonly>
											 <input type="text" class="form-control" name="r_c_r" id="r_c_r" placeholder="'.L('affect_remark').'" style="outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["r_c_r"].'"  readonly>		
											 
										 
									</div>
								  </div>
								</li>';
                     if ($ls['curr_status']=='COMPLETION'){
						 
							 $peedate=explode(';',$ls['exec_date']);
							if(date("M Y",strtotime($ls['r_c_d']))<>date("M Y",strtotime($peedate[0]))) {
					                 $timeline.='<li><div  class="tldate">'.date("M Y",strtotime($peedate[0])).'</div></li>';
							 }	 
						 $timeline.= '
							  <li >
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>Pe Excute Completion</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($peedate[0])).'</small></p> 							  
									</div>
									<div class="tl-body">   
																	 
									</div>
								  </div>
							 </li>';
					
					 }else{
						 if(date("M Y",strtotime($ls['r_c_d']))<>date("M Y")) {
								 $timeline.='<li><div class="tldate">'.date("M Y").'</div></li>';
							 }
						 $timeline.= '
							  <li >
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>Waiting For Pe Excute</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s").'</small></p> 							  
									</div>
									<div class="tl-body">   
																	 
									</div>
								  </div>
							 </li>';
					 }
				 }

			 }

		
          $timeline.='</ul>';
		  $this->assign("timeline",$timeline);
		  $this->assign("initialPreview",$initialPreview);
		  $this->assign("initialPreviewConfig",$initialPreviewConfig);
		  $this->assign("userName",$_SESSION['displayname']);
		  $this->assign('vo',$ls);
		  $this->assign("tableDetail",$tableDetail);
		  $downurl="'http://". $_SERVER['HTTP_HOST'].__ROOT__.'/'.APP_NAME."/Uploads/{filename}'";
		  $this->assign("downurl",$downurl);
		  $this->display();
	 }
/*******/
 public function pe_exec(){
	     $pcn_no= $this->_get('pcn_no');
		 $Model = M("main"); 
	     $ls=$Model->where("pcn_no=$pcn_no")->find();
		 $internal_pn = explode(";",$ls['internal_pn']);
         $cust_pn =     explode(";",$ls['cust_pn']);
         $cust_name=    explode(";", $ls['cust_name']);
		 $exec_status = explode(";",$ls['exec_status']);
         $exec_date=    explode(";", $ls['exec_date']);
		 $exec_user=    explode(";", $ls['exec_user']);
		 $exec_remark=  explode(";", $ls['exec_remark']);
		 $n_internal_pn=explode(";", $ls['n_internal_pn']);
		 $ptl_no=       explode(";", $ls['ptl_no']);
		 $tableDetail="";
		   $actions='<a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a>
					 <a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>';
					 //<a class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>';
			$actions='<a class="add" title="Add" data-toggle="tooltip"><i class="fa fa-tasks" style="font-size: 16px;"></i></a>
					<a class="edit" title="Edit" data-toggle="tooltip"><i class="fa fa-pencil-alt" style="font-size:16px;"></i></a>';
					//<a class="delete" title="Delete" data-toggle="tooltip"><i class="fa fa-trash" style="font-size:16px;"></i></a>';
			 foreach($internal_pn as $key=>$val){
					 $tableDetail.="
									<tr>
										<td>$val</td>
										<td>$cust_pn[$key]</td>
										<td>$cust_name[$key]</td>
										<td>$n_internal_pn[$key]</td>
										<td>$ptl_no[$key]</td>
										";
										if ($exec_status[$key]=='true'){
								             $tableDetail.="<td><input type='checkbox' checked disabled></td>";
                                            $actions="";
										}else{                                      
									         $tableDetail.="<td><input type='checkbox'></td>";
											 $actions='<a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a>
					                          <a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>';
											  $actions='<a class="add" title="Add" data-toggle="tooltip"><i class="fa fa-tasks" style="font-size: 16px;"></i></a>
					                                    <a class="edit" title="Edit" data-toggle="tooltip"><i class="fa fa-pencil-alt" style="font-size:16px;"></i></a>';
			                            } 									
									$tableDetail.="<td>$exec_user[$key]</td>
										<td>$exec_date[$key]</td>
										<td>$exec_remark[$key]</td>
										<td>$actions</td>
									   </tr>";
			 }
			 $Attach = M('attach');
			 $AttachFile=$Attach->where("pcnNo=$pcn_no and location_attach is null")->select();
			 if(!empty($AttachFile)){ //判断一级是否为空
				 foreach($AttachFile as $key=>$value){   //循环读取
					// $initialPreview[]="http://10.120.1.243/webtools/PCN/uploads/".$value['savename'];
					 $initialPreview[]="http://". $_SERVER['HTTP_HOST'].__ROOT__.'/'.APP_NAME."/Uploads/".$value['name'];
					 $initialPreviewConfig[]=array("type"=>$value['category'],
												   "size"=>$value['size'],
												   "caption"=>$value['name'],
												   //"url"=>"__URL__/del_file",
												   "key"=>$value['recordid'] ,
                                                   "filename"=> $value['savename']);
			   }
			 }
			  $AttachFile=$Attach->where("pcnNo=$pcn_no and location_attach='spcn'")->select();
			 // echo var_dump($AttachFile);
			  $spcnfile='';
			 if(!empty($AttachFile)){ //判断一级是否为空
    			 foreach($AttachFile as $key=>$value){   //循环读取
                     $spcnfile.=$this->showExt(strtolower($value['extension'])).'&nbsp;&nbsp;&nbsp;'.'<a href=__URL__/download/id/'.$value['id'].'>'.$value["name"].'</a>   '.$this->byte_format($value['size']).'</br>';
			   }
			 }
			 $AttachFile=$Attach->where("pcnNo=$pcn_no and location_attach='ccomp'")->select();
		     $ccomp='';
			 if(!empty($AttachFile)){ //判断一级是否为空
    			 foreach($AttachFile as $key=>$value){   //循环读取
                     $ccomp.=$this->showExt(strtolower($value['extension'])).'&nbsp;&nbsp;&nbsp;'.'<a href=__URL__/download/id/'.$value['id'].'>'.$value["name"].'</a>   '.$this->byte_format($value['size']).'</br>';
			   }
			 }
		  if ($initialPreview){
			   $initialPreview= json_encode($initialPreview);
		  }else{
		       $initialPreview=json_encode([]);
			  
		   }
		  if ($initialPreviewConfig){
			  $initialPreviewConfig= json_encode($initialPreviewConfig);
		  }else{
		      $initialPreviewConfig= '[]';
		  }
		  /*timeLine*/
		  $timeline='<ul class="timeline">';    

			   $timeline.= '<li><div class="tldate">'. date("M Y",strtotime($ls['pe_m_d'])).'</div></li>
				          <li>
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PE Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['pe_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="pe_m" id="pe_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['pe_m'].'" readonly>
										 <input type="text" class="form-control" name="pe_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green;text-wrap:normal;" value="'.$ls["pe_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
					  if(date("M Y",strtotime($ls['pe_m_d']))<>date("M Y",strtotime($ls['pre_m_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['pre_m_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li class="timeline-inverted">
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PRE Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['pre_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="pre_m" id="pre_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['pre_m'].'" readonly>
										 <input type="text" class="form-control" name="pre_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["pre_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
                       if(date("M Y",strtotime($ls['pre_m_d']))<>date("M Y",strtotime($ls['qa_m_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['qa_m_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li>
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>QA Manger Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['qa_m_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="qa_m" id="qa_m" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['qa_m'].'" readonly>
										 <input type="text" class="form-control" name="qa_m_r" id="qa_m_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["qa_m_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>'; 
						 if(date("M Y",strtotime($ls['qa_m_d']))<>date("M Y",strtotime($ls['engi_d_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['engi_d_d'])).'</div></li>';
					  	 }
						 
			     $timeline.= '
				          <li class="timeline-inverted">
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>Engineering Director Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['engi_d_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="engi_d" id="engi_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['engi_d'].'" readonly>
										 <input type="text" class="form-control" name="engi_d_r" id="engi_d_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["engi_d_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
                	 if(date("M Y",strtotime($ls['prod_d_d']))<>date("M Y",strtotime($ls['engi_d_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['prod_d_d'])).'</div></li>';
					  	 }
						 
			       $timeline.= '
				          <li >
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>MFG Director Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['prod_d_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="prod_d" id="prod_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['prod_d'].'" readonly>
										 <input type="text" class="form-control" name="prod_d_r" id="prod_d_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["prod_d_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
                     if(date("M Y",strtotime($ls['prod_d_d']))<>date("M Y",strtotime($ls['qa_d_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['qa_d_d'])).'</div></li>';
					  	 }
						 
			        $timeline.= '
				          <li class="timeline-inverted" >
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>QA Director Confirm</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['qa_d_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="prod_d" id="prod_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['qa_d'].'" readonly>
										 <input type="text" class="form-control" name="prod_d_r" id="prod_d_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["qa_d_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
						      if(date("M Y",strtotime($ls['s_c_d']))<>date("M Y",strtotime($ls['qa_d_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['s_c_d'])).'</div></li>';
					  	 }
						 
			       $timeline.= '
				          <li>
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>PCN Sending Customer</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['s_c_d'])) .'</small></p> 							  
								</div>
								<div class="input-group">
									          <div class="input-group-addon">
										         <span class="fas fa-envelope" style="color:	green; background-color: #ffffff;font-size:15px;"></span>
									          </div>
											   <input type="text" class="form-control" name="mail_to_list" id="mail_to_list" placeholder="'.L('mail_to').'" style="font-size:12px;background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green;float:right" value="'.$ls["mail_to_list"].'"  readonly>								 
									      </div>	
									<div class="tl-body">  
										<present name="attachs">
											<fieldset  style="width:95%;margin:8px;color:gray">
											<legend style="border:none;font-size:16px;">'.L('attachments').':</legend>
											  <div style="font-size:12px;padding-left:20px;">
											      '. $spcnfile.'
										        </div>
										    </fieldset>
										</present>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="s_c_o" id="s_c_o" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['s_c_o'].'" readonly>
										 <input type="text" class="form-control" name="s_c_r" id="s_c_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["s_c_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
						      if(date("M Y",strtotime($ls['s_c_d']))<>date("M Y",strtotime($ls['r_c_d']))) {
					         $timeline.='<li><div class="tldate">'.date("M Y",strtotime($ls['r_c_d'])).'</div></li>';
					  	 }
						 
			       $timeline.= '
				          <li class="timeline-inverted" >
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>Customer PCN Replying</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['r_c_d'])) .'</small></p> 							  
								</div>
								  <present name="attachs">
											<fieldset  style="width:95%;margin:8px;color:gray">
											<legend style="border:none;font-size:16px;">'.L('attachments').':</legend>
											  <div style="font-size:12px; padding-left:20px;">
											      '. $ccomp.'
										        </div>
										    </fieldset>
								 </present>
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="r_c_o" id="r_c_o" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['r_c_o'].'" readonly>
										 <input type="text" class="form-control" name="r_c_r" id="r_c_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["r_c_r"].'"  readonly>								 
								</div>
							  </div>
						 </li>';
					  if(date("M Y",strtotime($ls['r_c_d']))<>date("M Y")) {
					         $timeline.='<li><div class="tldate">'.date("M Y").'</div></li>';
					  	 }
			         $timeline.= '
				          <li >
							  <div class="tl-circ"></div>
							  <div class="timeline-panel">
								<div class="tl-heading">
								  <h4>Waiting For Pe Excute</h4>
								  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['qa_d_d'])) .'</small></p> 							  
								</div>
								<div class="tl-body">   
																 
								</div>
							  </div>
						 </li>';
			 

		
          $timeline.='</ul>';
		  $this->assign("timeline",$timeline);
		  $this->assign("initialPreview",$initialPreview);
		  $this->assign("initialPreviewConfig",$initialPreviewConfig);
		  $this->assign("userName",$_SESSION['displayname']);
		  $this->assign('vo',$ls);
		  $this->assign("tableDetail",$tableDetail);
		  $downurl="'http://". $_SERVER['HTTP_HOST'].__ROOT__.'/'.APP_NAME."/Uploads/{filename}'";
		  $this->assign("downurl",$downurl);
		  $this->display();
	 }
  public function save(){
          $main=M('main');
		  $data=$_POST;
		  unset($data['__hash__']);
		  $data['pcn_no']=str_replace("No.","",$data['pcn_no']);
		  $datetime = new \DateTime;
          $data['initiation_date'] =$datetime->format('Y-m-d H:i:s');
		  $data['curr_status'] ="PE";
		  $main->add($data);
		  //echo var_dump($data);
		 // echo $main->getLastSql();
		  /* $data['pcn_no'] =str_replace("No.","",$_POST['pcn_no']);
          $data['internal_pn'] =   $_POST['internal_pn']; 
          $data['cust_pn'] =       $_POST['cust_pn'];
          $data['cust_name']=      $_POST['cust_name'];
          $data['initiator_dept']= $_POST['initiator_dept'];
          $data['before_change'] = $_POST['before_change'];
          $data['after_change'] =  $_POST['after_change'];
          $data['reason_change'] = $_POST['reason_change']; 
          $data['affect_shipping_schedule'] =$_POST['affect_shipping_schedule'];
          $data['affect_remark'] = $_POST['affect_remark'];
          echo  var_dump($data);*/
		 // echo $_POST['affect_shipping_schedule'];
          //echo var_dump($main);
		  //$this->display('index');
		   redirect(__URL__);
 }
 public function del(){
	 $pcn_no= $this->_get('pcn_no');
	
	 $Attach = M('attach');
	 $AttachFile=$Attach->where("pcnNo=$pcn_no")->select();
	 if(!empty($AttachFile)){ //判断一级是否为空
         foreach($AttachFile as $key=>$value){   //循环读取
         @unlink($value['savepath'].'/'.$value['savename']);
        //echo D('Category')->getLastSql(); //打印sql语句的写法
       }
	 }
	 $Attach->where("pcnNo=$pcn_no")->delete();
	 $Model = M("main"); // 实例化User对象	 
	 $result=$Model->where("pcn_no=$pcn_no")->delete();
	 if($result){
	    $this->success('删除记录成功!');
	 }else{
	     $this->error('删除记录失败!');
	 }
	  redirect(__URL__);
 }
 public function export_excel_bk(){
               	$pcn_no= $this->_get('pcn_no');
				 $Model = M("main");
	            $ls=$Model->where("pcn_no=$pcn_no")->find();
                $cust_name1=    explode(";", $ls['cust_name']);
			    $uniqueCust=array_unique( $cust_name1);
                $ip=0 ;
				foreach($uniqueCust as $key=>$val){
					$ip+=1;
					$this->create_excel($val,$pcn_no,$ip);
					//echo $val;
				}
				$filename ="List_". date('Y_m_d',time());
				$zip = new ZipArchive();
				$zipfile='ExcelFile/Reports.zip';
				$flag = (file_exists($zipfile))? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE;
				$zip->open('ExcelFile/Reports.zip',$flag);
				$zip->addGlob("ExcelFile/$filename_*.xlsx");
				$zip->close();
				array_map('unlink', glob("ExcelFile/*.xlsx"));
				header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header('Content-disposition: attachment; filename='.basename($zipfile)); //文件名
                header("Content-Type: application/zip"); //zip格式的
                header("Content-Transfer-Encoding: binary"); //告诉浏览器，这是二进制文件
                header('Content-Length: '. filesize($zipfile)); //告诉浏览器，文件大小
                @readfile($zipfile);
 }
  public function create_excel($cname,$pcn_no,$ip){
	           ini_set("max_execution_time","-1"); 
				Vendor("PHPExcel.PHPExcel");			
	            $Model = M("main");
	            $ls=$Model->where("pcn_no=$pcn_no")->find();
				$internal_pn = explode(";",$ls['internal_pn']);
                $cust_pn =     explode(";",$ls['cust_pn']);
                $cust_name=    explode(";", $ls['cust_name']);
				foreach($cust_name as $skey=>$sval){					         
					       if ($sval==$cname){
						        $cinfo[$cname]['internal_pn'][]=$internal_pn[$skey];
							    $cinfo[$cname]['cust_pn'][]=$cust_pn[$skey];
						   }
				     }
				
				
				foreach($cinfo as $key=>$val){

						$objPHPExcel = new PHPExcel();
						$objReader = PHPExcel_IOFactory::createReader('Excel2007');
						$objPHPExcel = $objReader->load("Tpl/template/pcn_tmp_20200505.xlsx");
						$objPHPExcel->setActiveSheetIndex(0);
						/*$objPHPExcel->getActiveSheet()->setCellValue('L9', $cust_name[0]);
						$objPHPExcel->getActiveSheet()->setCellValue('BB7', $pcn_no);
						$objPHPExcel->getActiveSheet()->setCellValue('AS9', $cust_pn[0]);*/
						$objPHPExcel->getActiveSheet()->setCellValue('L9', $key);
						$objPHPExcel->getActiveSheet()->setCellValue('BB7', $pcn_no);
						//$objPHPExcel->getActiveSheet()->setCellValue('AS9', $val['cust_pn'][0]);
						$objPHPExcel->getActiveSheet()->setCellValue('AS9','See below PN list');
						$objPHPExcel->getActiveSheet()->setCellValue('AS10', $ls['initiation_date']);
						$objPHPExcel->getActiveSheet()->setCellValue('L10', $ls['initiator_dept']);
						$objPHPExcel->getActiveSheet()->setCellValue('S15', $ls['before_change']);
						$objPHPExcel->getActiveSheet()->setCellValue('S18', $ls['after_change']);
						$objPHPExcel->getActiveSheet()->setCellValue('S21', $ls['reason_change']);
						//$objPHPExcel->getActiveSheet()->setCellValue('AR14', $internal_pn[0]);
						$objPHPExcel->getActiveSheet()->setCellValue('AR14', 'See below PN list');
						$objPHPExcel->getActiveSheet()->setCellValue('B36', 'Comments:'.$ls['affect_remark']);
						//$objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$objPHPExcel->getActiveSheet()->getStyle('C12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$objPHPExcel->getActiveSheet()->getStyle('C12')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						if ($ls['affect_shipping_schedule']==0){
						  //  $objPHPExcel->getActiveSheet()->setCellValue('E19',  "√ No     × Yes    (Remark:".$ls['reason_change'].")"); #FFFFFF 
						  $objPHPExcel->getActiveSheet()->getStyle( 'AK24')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
						  $objPHPExcel->getActiveSheet()->getStyle( 'AK24')->getFill()->getStartColor()->setARGB('0000FF');
						   $objPHPExcel->getActiveSheet()->getStyle( 'AR24')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
						  $objPHPExcel->getActiveSheet()->getStyle( 'AR24')->getFill()->getStartColor()->setARGB('FFFFFF');
						
						}else{
						  $objPHPExcel->getActiveSheet()->getStyle( 'AK24')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
						  $objPHPExcel->getActiveSheet()->getStyle( 'AK24')->getFill()->getStartColor()->setARGB('FFFFFF');
						   $objPHPExcel->getActiveSheet()->getStyle( 'AR24')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
						  $objPHPExcel->getActiveSheet()->getStyle( 'AR24')->getFill()->getStartColor()->setARGB('0000FF');
										}
						 $ec =70;
						/* foreach($internal_pn as $key=>$val){
							 $ec=$ec+1;
							 $objPHPExcel->getActiveSheet()->setCellValue('B'.$ec, $cust_name[$key]);
							 $objPHPExcel->getActiveSheet()->setCellValue('T'.$ec, $cust_pn[$key]);
							 $objPHPExcel->getActiveSheet()->setCellValue('AS'.$ec, $val);
						 }*/
						  foreach($val['cust_pn'] as $skey=>$sval){
									 $ec=$ec+1;
									 $objPHPExcel->getActiveSheet()->setCellValue('B'.$ec, $key);
									 $objPHPExcel->getActiveSheet()->setCellValue('T'.$ec, $sval);
									 $objPHPExcel->getActiveSheet()->setCellValue('AS'.$ec, $val['internal_pn'][$skey]);
						 }
						  $objPHPExcel->getActiveSheet()->setCellValue('B32', $ls['pe_m']);
						  $objPHPExcel->getActiveSheet()->setCellValue('B34', 'Digitally Signed by '.$ls['pe_m']);
						  $objPHPExcel->getActiveSheet()->setCellValue('B35', $ls['pe_m_d']);
						  $objPHPExcel->getActiveSheet()->setCellValue('V32', $ls['pre_m']);
						  $objPHPExcel->getActiveSheet()->setCellValue('V34', 'Digitally Signed by '.$ls['pre_m']);
						  $objPHPExcel->getActiveSheet()->setCellValue('V35', $ls['pre_m_d']);
						  $objPHPExcel->getActiveSheet()->setCellValue('AQ32', $ls['qa_m']);
						  $objPHPExcel->getActiveSheet()->setCellValue('AQ34', 'Digitally Signed by '.$ls['qa_m']);
						  $objPHPExcel->getActiveSheet()->setCellValue('AQ35', $ls['qa_m_d']);

						  $objPHPExcel->getActiveSheet()->setCellValue('B38', $ls['engi_d']);
						  $objPHPExcel->getActiveSheet()->setCellValue('B40', 'Digitally Signed by '.$ls['engi_d']);
						  $objPHPExcel->getActiveSheet()->setCellValue('B41', $ls['engi_d_d']);
						  $objPHPExcel->getActiveSheet()->setCellValue('V38', $ls['prod_d']);
						  $objPHPExcel->getActiveSheet()->setCellValue('V40', 'Digitally Signed by '.$ls['prod_d']);
						  $objPHPExcel->getActiveSheet()->setCellValue('V41', $ls['prod_d_d']);
						  $objPHPExcel->getActiveSheet()->setCellValue('AQ38', $ls['qa_d']);
						  $objPHPExcel->getActiveSheet()->setCellValue('AQ40', 'Digitally Signed by '.$ls['qa_d']);
						  $objPHPExcel->getActiveSheet()->setCellValue('AQ41', $ls['qa_d_d']);
					   // $filename ="List_". date('Y_m_d',time()) . ".xls";
						$filename ="List_". date('Y_m_d',time()) ."_$ip". ".xlsx";
						//ob_end_clean();
						/*header('Content-Type: application/vnd.ms-excel');
						header('Content-Disposition: attachment;filename='.$filename);
						header('Cache-Control: max-age=0');*/
					  //  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
						$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
						//$objWriter->save('php://output');
						$objWriter->save("ExcelFile/$filename");
						
						//header('Content-Length: '.filesize($filename));
					//	readfile($filename); 

						/* $file = '/xampp/htdocs/sms/Reports.zip';
                         header('Content-type: application/zip'); // Please check this, i just guessed
                         header('Content-Disposition: attachment; filename="'.basename($file).'"');
                         header('Content-Length: '.filesize($file));
                         readfile($file);*/
			    	}
                  
				  /*/ 生成2007excel格式的xlsx檔案
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="01simple.xlsx"');
					header('Cache-Control: max-age=0');
					$objWriter = PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
					$objWriter->save( 'php://output');
					exit;
					header('Content-Type: application/pdf');
					header('Content-Disposition: attachment;filename="01simple.pdf"');
					header('Cache-Control: max-age=0');
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
					$objWriter->save('php://output');
					exit;
					// 生成一個pdf檔案
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
					$objWriter->save('a.pdf');*/
 }
public function export_excel(){
	           ini_set("max_execution_time","-1"); 
				Vendor("PHPExcel.PHPExcel");
                 $pcn_no= $this->_get('pcn_no');
	            $Model = M("main");
	            $ls=$Model->where("pcn_no=$pcn_no")->find();
				 $internal_pn = explode(";",$ls['internal_pn']);
                 $cust_pn =     explode(";",$ls['cust_pn']);
                 $cust_name=    explode(";", $ls['cust_name']);
				$objPHPExcel = new PHPExcel();
			    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
                $objPHPExcel = $objReader->load("Tpl/template/pcn_tmp_20200505.xlsx");
			    $objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->setCellValue('L9', $cust_name[0]);
				$objPHPExcel->getActiveSheet()->setCellValue('BB7', $pcn_no);
				//$objPHPExcel->getActiveSheet()->setCellValue('AS9', $cust_pn[0]);
				$objPHPExcel->getActiveSheet()->setCellValue('AS9', 'See below PN list');
				$objPHPExcel->getActiveSheet()->setCellValue('AS10', $ls['initiation_date']);
				$objPHPExcel->getActiveSheet()->setCellValue('L10', $ls['initiator_dept']);
				$objPHPExcel->getActiveSheet()->setCellValue('S15', $ls['before_change']);
				$objPHPExcel->getActiveSheet()->setCellValue('S18', $ls['after_change']);
				$objPHPExcel->getActiveSheet()->setCellValue('S21', $ls['reason_change']);
				//$objPHPExcel->getActiveSheet()->setCellValue('AR14', $internal_pn[0]);
				$objPHPExcel->getActiveSheet()->setCellValue('AR14', 'See below PN list');
				$objPHPExcel->getActiveSheet()->setCellValue('B36', 'Comments:'.$ls['affect_remark']);
				//$objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			    $objPHPExcel->getActiveSheet()->getStyle('C12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			    $objPHPExcel->getActiveSheet()->getStyle('C12')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				if ($ls['affect_shipping_schedule']==0){
			      //  $objPHPExcel->getActiveSheet()->setCellValue('E19',  "√ No     × Yes    (Remark:".$ls['reason_change'].")"); #FFFFFF 
				  $objPHPExcel->getActiveSheet()->getStyle( 'AK24')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                  $objPHPExcel->getActiveSheet()->getStyle( 'AK24')->getFill()->getStartColor()->setARGB('0000FF');
				   $objPHPExcel->getActiveSheet()->getStyle( 'AR24')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                  $objPHPExcel->getActiveSheet()->getStyle( 'AR24')->getFill()->getStartColor()->setARGB('FFFFFF');
				
                }else{
				  $objPHPExcel->getActiveSheet()->getStyle( 'AK24')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                  $objPHPExcel->getActiveSheet()->getStyle( 'AK24')->getFill()->getStartColor()->setARGB('FFFFFF');
				   $objPHPExcel->getActiveSheet()->getStyle( 'AR24')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                  $objPHPExcel->getActiveSheet()->getStyle( 'AR24')->getFill()->getStartColor()->setARGB('0000FF');
								}
				 $ec =70;
			     foreach($internal_pn as $key=>$val){
					 $ec=$ec+1;
					 $objPHPExcel->getActiveSheet()->setCellValue('B'.$ec, $cust_name[$key]);
					 $objPHPExcel->getActiveSheet()->setCellValue('T'.$ec, $cust_pn[$key]);
					 $objPHPExcel->getActiveSheet()->setCellValue('AS'.$ec, $val);
				 }
				  $objPHPExcel->getActiveSheet()->setCellValue('B32', $ls['pe_m']);
				  $objPHPExcel->getActiveSheet()->setCellValue('B34', 'Digitally Signed by '.$ls['pe_m']);
				  $objPHPExcel->getActiveSheet()->setCellValue('B35', $ls['pe_m_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('V32', $ls['pre_m']);
				  $objPHPExcel->getActiveSheet()->setCellValue('V34', 'Digitally Signed by '.$ls['pre_m']);
				  $objPHPExcel->getActiveSheet()->setCellValue('V35', $ls['pre_m_d']);
                  $objPHPExcel->getActiveSheet()->setCellValue('AQ32', $ls['qa_m']);
				  $objPHPExcel->getActiveSheet()->setCellValue('AQ34', 'Digitally Signed by '.$ls['qa_m']);
				  $objPHPExcel->getActiveSheet()->setCellValue('AQ35', $ls['qa_m_d']);

				  $objPHPExcel->getActiveSheet()->setCellValue('B38', $ls['engi_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('B40', 'Digitally Signed by '.$ls['engi_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('B41', $ls['engi_d_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('V38', $ls['prod_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('V40', 'Digitally Signed by '.$ls['prod_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('V41', $ls['prod_d_d']);
                  $objPHPExcel->getActiveSheet()->setCellValue('AQ38', $ls['qa_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('AQ40', 'Digitally Signed by '.$ls['qa_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('AQ41', $ls['qa_d_d']);
               // $filename ="List_". date('Y_m_d',time()) . ".xls";
			   // $filename ="List_". date('Y_m_d',time()) . ".xlsx";$pcn_no
                  $filename ="PCN". $pcn_no . ".xlsx";
				//ob_end_clean();
             
              //  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename='.$filename);
                    header('Cache-Control: max-age=0');       
                  $objWriter->save('php://output');
                exit;
 }
public function export_excel_detail(){
                $this->exportToExcel();
                exit;
	            $sql="select * from (
							SELECT
							substring_index(
									substring_index(
										a.internal_pn,
										';',
										b.help_topic_id + 1
									),
									';' ,- 1
								) AS jobName,
							   substring_index(
									substring_index(
											a.cust_pn,
										';',
										b.help_topic_id + 1
									),
									';' ,- 1
								) AS custpn,
							substring_index(
									substring_index(
											a.cust_name,
										';',
										b.help_topic_id + 1
									),
									';' ,- 1
								) AS custname,
							substring_index(
									substring_index(
										a.n_internal_pn,
										';',
										b.help_topic_id + 1
									),
									';' ,- 1
								) AS n_internalpn,
							substring_index(
									substring_index(
										a.ptl_no,
										';',
										b.help_topic_id + 1
									),
									';' ,- 1
								) AS ptlno,
							substring_index(
									substring_index(
										a.exec_date,
										';',
										b.help_topic_id + 1
									),
									';' ,- 1
								) AS execdate,
							substring_index(
									substring_index(
										a.exec_user,
										';',
										b.help_topic_id + 1
									),
									';' ,- 1
								) AS execuser,
							substring_index(
									substring_index(
										a.exec_remark,
										';',
										b.help_topic_id + 1
									),
									';' ,- 1
								) AS execremark,
							  substring_index(
									substring_index(
										a.exec_status,
										';',
										b.help_topic_id + 1
									),
									';' ,- 1
								) AS execstatus
							,a.*
							FROM
								pcn_main a
							JOIN mysql.help_topic b ON b.help_topic_id < (
								length(a.internal_pn) - length(
									REPLACE (a.internal_pn, ';', '')
								) + 1
							) ) ab where ";
                    $s_pcn_no=$_GET['pcn_no']!=1?$_GET['pcn_no']:'';
                    $s_cs_name=$_GET['cust_name']!=1?$_GET['cust_name']:'';
                    $s_cs_pn=$_GET['cust_pn']!=1?$_GET['cust_pn']:'';
                    $s_internal_pn=$_GET['internal_pn']!=1?" and jobName like '%".$_GET['internal_pn']."%'":"";
                    $s_pcn_type=$_GET['category']!=1?" and category like '%".$_GET['category']."%'":"";
                    $s_curr_status=$_GET['curr_status']?$_GET['curr_status']:'';
                    $s_exec_status=$_GET['exec_status']?" and execstatus like '%".$_GET['exec_status']."%'":'';
                    $sql1=" pcn_no like '%".$s_pcn_no."%' and custname like '%".$s_cs_name."%' and custpn like '%".$s_cs_pn."%'  
                     and curr_status like '%".$s_curr_status."%' and curr_status not like '%Rej%' $s_pcn_type $s_exec_status $s_internal_pn";
                   $sql.=$sql1;
                   $model = M();
	               $voList = $model->query($sql);
               //    echo $model->getLastSql();
                 // echo var_dump($voList);
                
                //ini_set('memory_limit','1024M'); //设置程序运行的内存
                //ini_set('max_execution_time',0); //设置程序的执行时间,0为无上限
                ini_set("max_execution_time","-1"); 
                ob_end_clean();  //清除内存
                ob_start();
             $title=array(
                           'id'=>'Id',
                           'jobName'=>'Job Name', 
                           'custpn'=>'Customer PN', 
                           'custname'=>'Customer Name',
                           'n_internalpn'=>'Internal PN',
                           'ptlno'=>'PTL No', 
                           'execdate'=>'Executed Date', 
                           'execuser'=>'Executed User',
                           'execremark'=>'Executed Remark', 
                           'execstatus'=>'Executed Status', 
                           'pcn_no'=>'PCN No', 
                           'before_change'=>'Before Change', 
                           'after_change'=>'After Change', 
                           'reason_change'=>'Reason Change', 
                           'affect_shipping_schedule'=>'Affect Shipping Schedule',
                           'affect_remark'=>'Affect Remark', 
                           'initiation_date'=>'Initiation Date', 
                           'pe_m'=>'PE Manager Approval', 
                           'pe_m_d'=>'PE Manager Approval Date',
                           'pe_m_r'=>'PE Manager Remark', 
                           'pre_m'=>'PRE Manager Approval', 
                           'pre_m_d'=>'PRE Manager Approval Date', 
                           'pre_m_r'=>'PRE Manager Remark',
                           'qa_m'=>'QA Manager Approval', 
                           'qa_m_d'=>'QA Manger Approval Date', 
                           'qa_m_r'=>'QA Manager Approval Remark', 
                           'engi_d'=>'Enginering Dirc Approval',
                           'engi_d_d'=>'Engineering Dirc Approval date', 
                           'engi_d_r'=>'Engineering Dirc Remark', 
                           'prod_d'=>'MFG Dirc Approval', 
                           'prod_d_d'=>'MFG Dirc Approval Date', 
                           'prod_d_r'=>'MFG Dirc Remark', 
                           'qa_d'=>'QA Dirc Approval', 
                           'qa_d_d'=>'QA Dirc Approval Date', 
                           'qa_d_r'=>'AQ Dirc Remark', 
                           'curr_status'=>'Current Status', 
                           's_c_o'=>'Sending Cust User',
                           's_c_d'=>'Sending Cust Date', 
                           's_c_r'=>'Sending Cust Remark', 
                           'r_c_o'=>'Cust Replying User', 
                           'r_c_d'=>'Cust Replying Date', 
                           'r_c_r'=>'Cust Replying Remark', 
                           'mail_to_list'=>'Mail to list', 
                           'category'=>'Category', 
                           'expected_reply_date'=>'Expected Cust Reply date', 
                           'splited'=>'Splited'
                           );
        
				Vendor("PHPExcel.PHPExcel");
				$objPHPExcel = new PHPExcel();
             	$j = 0;
				foreach($title as $key => $value){
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($j,1,$value);
					$j++;
				}
				$i=2;
                foreach( $voList as $key => $value){
					$j = 0;
					foreach($title as $tkey => $tvalue){
							if ($tkey =='index') {
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($j,$i ,$i-1);
							} else {
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($j,$i , $voList[$key][$tkey]);
							}
							$j++;
					}
					$i++;
                    if (i>200){
					 ob_flush();  //每1万条数据就刷新缓冲区
                     flush();
                    }
                }
                //  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($j+1,$i+1 , $model->getLastSql());
                  $filename ="List_".date("Y-m-d",time()).'.xlsx';
                  //$filename ="List_".date("Y-m-d H:i:s",time()).'.xlsx';
                    header('Content-Type: application/vnd.ms-excel;charset=utf-8');
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header("Content-Disposition: attachment;filename=".$filename);
					header('Cache-Control: max-age=0');
					$objWriter = PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
					$objWriter->save( 'php://output');
					exit();			   
 }
function exportToExcel(){
           	            $sql="select * from (
							SELECT
							substring_index(
									substring_index(
										a.internal_pn,
										';',
										b.help_topic_id + 1
									),
									';' ,- 1
								) AS jobName,
							   substring_index(
									substring_index(
											a.cust_pn,
										';',
										b.help_topic_id + 1
									),
									';' ,- 1
								) AS custpn,
							substring_index(
									substring_index(
											a.cust_name,
										';',
										b.help_topic_id + 1
									),
									';' ,- 1
								) AS custname,
							substring_index(
									substring_index(
										a.n_internal_pn,
										';',
										b.help_topic_id + 1
									),
									';' ,- 1
								) AS n_internalpn,
							substring_index(
									substring_index(
										a.ptl_no,
										';',
										b.help_topic_id + 1
									),
									';' ,- 1
								) AS ptlno,
							substring_index(
									substring_index(
										a.exec_date,
										';',
										b.help_topic_id + 1
									),
									';' ,- 1
								) AS execdate,
							substring_index(
									substring_index(
										a.exec_user,
										';',
										b.help_topic_id + 1
									),
									';' ,- 1
								) AS execuser,
							substring_index(
									substring_index(
										a.exec_remark,
										';',
										b.help_topic_id + 1
									),
									';' ,- 1
								) AS execremark,
							  substring_index(
									substring_index(
										a.exec_status,
										';',
										b.help_topic_id + 1
									),
									';' ,- 1
								) AS execstatus
							,a.*
							FROM
								pcn_main a
							JOIN mysql.help_topic b ON b.help_topic_id < (
								length(a.internal_pn) - length(
									REPLACE (a.internal_pn, ';', '')
								) + 1
							) ) ab where ";
                    $s_pcn_no=$_GET['pcn_no']!=1?$_GET['pcn_no']:'';
                    $s_cs_name=$_GET['cust_name']!=1?$_GET['cust_name']:'';
                    $s_cs_pn=$_GET['cust_pn']!=1?$_GET['cust_pn']:'';
                    $s_internal_pn=$_GET['internal_pn']!=1?" and jobName like '%".$_GET['internal_pn']."%'":"";
                    $s_pcn_type=$_GET['category']!=1?" and category like '%".$_GET['category']."%'":"";
                    $s_curr_status=$_GET['curr_status']?$_GET['curr_status']:'';
                    $s_exec_status=$_GET['exec_status']?" and execstatus like '%".$_GET['exec_status']."%'":'';
                    $sql1=" pcn_no like '%".$s_pcn_no."%' and custname like '%".$s_cs_name."%' and custpn like '%".$s_cs_pn."%'  
                     and curr_status like '%".$s_curr_status."%' and curr_status not like '%Rej%' $s_pcn_type $s_exec_status $s_internal_pn and splited=0";
                   $sql.=$sql1;
                   $title=array(
                           'id'=>'id',
                           'jobName'=>'Job Name', 
                           'custpn'=>'Customer PN', 
                           'custname'=>'Customer Name',
                           'n_internalpn'=>'Internal PN',
                           'ptlno'=>'PTL No', 
                           'execdate'=>'Executed Date', 
                           'execuser'=>'Executed User',
                           'execremark'=>'Executed Remark', 
                           'execstatus'=>'Executed Status', 
                           'pcn_no'=>'PCN No', 
                           'before_change'=>'Before Change', 
                           'after_change'=>'After Change', 
                           'reason_change'=>'Reason Change', 
                           'affect_shipping_schedule'=>'Affect Shipping Schedule',
                           'affect_remark'=>'Affect Remark', 
                           'initiation_date'=>'Initiation Date', 
                           'pe_m'=>'PE Manager Approval', 
                           'pe_m_d'=>'PE Manager Approval Date',
                           'pe_m_r'=>'PE Manager Remark', 
                           'pre_m'=>'PRE Manager Approval', 
                           'pre_m_d'=>'PRE Manager Approval Date', 
                           'pre_m_r'=>'PRE Manager Remark',
                           'qa_m'=>'QA Manager Approval', 
                           'qa_m_d'=>'QA Manger Approval Date', 
                           'qa_m_r'=>'QA Manager Approval Remark', 
                           'engi_d'=>'Enginering Dirc Approval',
                           'engi_d_d'=>'Engineering Dirc Approval date', 
                           'engi_d_r'=>'Engineering Dirc Remark', 
                           'prod_d'=>'MFG Dirc Approval', 
                           'prod_d_d'=>'MFG Dirc Approval Date', 
                           'prod_d_r'=>'MFG Dirc Remark', 
                           'qa_d'=>'QA Dirc Approval', 
                           'qa_d_d'=>'QA Dirc Approval Date', 
                           'qa_d_r'=>'AQ Dirc Remark', 
                           'curr_status'=>'Current Status', 
                           's_c_o'=>'Sending Cust User',
                           's_c_d'=>'Sending Cust Date', 
                           's_c_r'=>'Sending Cust Remark', 
                           'r_c_o'=>'Cust Replying User', 
                           'r_c_d'=>'Cust Replying Date', 
                           'r_c_r'=>'Cust Replying Remark', 
                           'mail_to_list'=>'Mail to list', 
                           'category'=>'Category', 
                           'expected_reply_date'=>'Expected Cust Reply date', 
                           'splited'=>'Splited'
                           );
			header('Access-Control-Allow-Origin:*');//允许所有来源访问
			set_time_limit(0);
			ini_set('memory_limit', '128M');			 
			$fileName = date('YmdHis', time());
			header('Content-Encoding: UTF-8');
			header("Content-type:application/vnd.ms-excel;charset=UTF-8");
			header('Content-Disposition: attachment;filename="' . $fileName . '.csv"');
			//注意，数据量在大的情况下。比如导出几十万到几百万，会出现504 Gateway Time-out,请修改php.ini的max_execution_time参数
			//打开php标准输出流以写入追加的方式打开
			$fp = fopen('php://output', 'a');
			//连接数据库
			$dbhost = '10.120.1.243';
			$dbuser = 'camvgz';
			$dbpwd = 'camvgz';
			$con = mysqli_connect($dbhost, $dbuser, $dbpwd);
			if (mysqli_connect_errno())
				die('connect error');		 
			$database = 'pcn';//选择数据库
			mysqli_select_db($con, $database);
			mysqli_query($con, "set names UTF8");//如果需要请设置编码
			 
			//用fputcsv从数据库中导出1百万的数据,比如我们每次取1万条数据，分100步来执行
			//一次性读取1万条数据，也可以把$nums调小，$step相应增大。
			$step = 5;
			$nums = 10000;
			//设置标题
			//$title = array('id', '编号', '姓名', '年龄'); //注意这里是小写id,否则ID命名打开会提示Excel 已经检测到"xxx.xsl"是SYLK文件,但是不能将其加载: CSV 文或者XLS文件的前两个字符是大写字母"I"，"D"时，会发生此问题。
			foreach ($title as $key => $item){
				$title1[$key] = iconv("UTF-8", "GB2312//IGNORE", $item);}
			fputcsv($fp, $title1);
			for ($s = 1; $s <= $step; $s++) {
				$start = ($s - 1) * $nums;
				$result = mysqli_query($con,  $sql. " ORDER BY `id` LIMIT {$start},{$nums}");
				if ($result) {
					while ($row = mysqli_fetch_assoc($result)) {
						foreach ($title as $key => $item)
							$row1[$key] = iconv("UTF-8", "GBK", $row[$key]); //这里必须转码，不然会乱码
						fputcsv($fp, $row1);
					}
					mysqli_free_result($result); //释放结果集资源
					ob_flush();  //每1万条数据就刷新缓冲区
					flush();
				}
			}
			mysqli_close($con);//断开连接
   }
   public function export_excel_pcn_list () {
	$s_pcn_no=$_GET['pcn_no']!=1?$_GET['pcn_no']:'';
	$s_cs_name=$_GET['cust_name']!=1?$_GET['cust_name']:'';
	$s_cs_pn=$_GET['cust_pn']!=1?$_GET['cust_pn']:'';
	$s_internal_pn=$_GET['internal_pn']!=1?" and jobName like '%".$_GET['internal_pn']."%'":"";
	$s_pcn_type=$_GET['category']!=1?" and category like '%".$_GET['category']."%'":"";
	$s_curr_status=$_GET['curr_status']?$_GET['curr_status']:'';
	$s_exec_status=$_GET['exec_status']?" and execstatus like '%".$_GET['exec_status']."%'":'';
	$sql1=" pcn_no like '%".$s_pcn_no."%' and custname like '%".$s_cs_name."%' and custpn like '%".$s_cs_pn."%'  
	 and curr_status like '%".$s_curr_status."%' and curr_status not like '%Rej%' $s_pcn_type $s_exec_status $s_internal_pn and splited=0";
    $sql="SELECT id,pcn_no, substring_index(cust_name,';',1)as cust_name, substring_index(cust_pn,';',1) as cust_pn, initiator_dept,substring_index(internal_pn,';',1) as  internal_pn,
	     before_change, after_change, reason_change, affect_shipping_schedule, affect_remark, initiation_date, pe_m, pe_m_d, pe_m_r, pre_m, pre_m_d, pre_m_r, qa_m, qa_m_d,
	     qa_m_r, engi_d, engi_d_d, engi_d_r, prod_d, prod_d_d, prod_d_r, qa_d, qa_d_d, qa_d_r, curr_status, exec_status, exec_date, exec_user, exec_remark, s_c_o, s_c_d,
	    s_c_r, r_c_o, r_c_d, r_c_r, n_internal_pn, ptl_no, mail_to_list, category, expected_reply_date, splited FROM pcn_main where length(pcn_no)>=11 ";
	 ini_set("max_execution_time","-1"); 
	 Vendor("PHPExcel.PHPExcel");
	 $objPHPExcel = new PHPExcel();
	 $objPHPExcel->getProperties()->setCreator("Da")
								  ->setLastModifiedBy("Da")
								  ->setTitle("Office 2003 XLS Test Document")
								  ->setSubject("Office 2003 XLS Test Document")
								  ->setDescription("Test document for Office 2003 XLS, generated using PHP classes.")
								  ->setKeywords("office 2003 openxml php")
								  ->setCategory("Result file");
	 $objPHPExcel->setActiveSheetIndex(0);                                                         
	 $objPHPExcel->getActiveSheet(0)->setTitle('List');             
	 
	 $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
	 $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
	 $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(8);
	 $objPHPExcel->getActiveSheet()->getStyle('A1:AF1')->getFont()->setBold(true);
	 $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
	$model = M();
	$result = $model->query($sql);
	 //$name="Form";
	 //$model = M ($name);
	 //$result = $model->select();
	 
		 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
		 $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(100);
		 //Default field here:
		 $title =array('id'=>'ID',
					   'pcn_no'=>'PCN No',
					   'cust_name'	=>'Customer Name',
					   'cust_pn'	=>'Customer PN',
					   'internal_pn'=>'Internal PN',
					   'before_change'=>'Before Change',
					   'after_change'	=>'After Change,',
					   'reason_change'	=>'Reason Change',
					   'affect_shipping_schedule'	=>'Affect Shipping Schedule',
					   'affect_remark'	=>'Affect Remark',
					   'initiation_date'	=>'Initiation Date',
					   'curr_status' =>'Current Status'
					   );		
	 
	 $j = 0;
	 foreach($title as $key => $value){
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($j,1,$value);
		$j++;
	}
	 $i=2;
	 $cstatus='';
	 foreach($result as $key => $value){
		 $j = 0;
		 foreach($title as $tkey => $tvalue){
				 if ($tkey =='curr_status') {
					switch($result[$key][$tkey]) {				
						case 'PE':
							$cstatus='Waiting For PE Manager Approval';
						  break;
						case 'PRE':
							$cstatus='Waiting For PRE Manager Approval';
						  break;
						case 'QA':
							$cstatus='Waiting For QA Manager Approval';
						  break;
						case 'ENGD':
							$cstatus='Waiting For PRE Director Approval';
						  break;
						case 'MFGD':
							$cstatus='Waiting For MFG Director Approval';
						  break;
						case 'QAD':
							$cstatus='Waiting For QA Director Approval';
						  break;
						case 'SPCN':
							$cstatus='Waiting For Sending Customer';
						  break;
						case 'CCOMP':
							$cstatus='Pending Customer Replying';
						  break;
						case 'PEE':			
							$cstatus='Pending Executed PTL';   
							  break;
						case 'COMPLETION':			
							$cstatus='Completion';      
							 break;              
						default:
						  $dept=$result[$key][$tkey].explode('/');
						  switch($dept[0]) {				
								case 'PE':
									$cstatus='PE Manager Reject';
								  break;
								case 'PRE':
									$cstatus='PRE Manager Reject';
								  break;
								case 'QA':
									$cstatus='QA Manager Reject';
								  break;
								case 'ENGD':
									$cstatus='PRE Director Reject';
								  break;
								case 'MFGD':
									$cstatus='MFG Director Reject';
								  break;
								case 'QAD':
									$cstatus='QA Director Reject';
								  break;
								case 'SPCN':
									$cstatus='Cancel Sending Customer';
								  break;
								case 'CCOMP':
									$cstatus='Customer Reject';
								  break;
								case 'PEE':
									$cstatus='PE Cancel Excuted';
								  break;  
						  }
					  }					 
				  
					 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($j,$i ,$cstatus);
				 } else {
					 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($j,$i ,$result[$key][$tkey]);
				 }
				 $j++;
		 }
		 $i++;
		 
	 }


	 $filename ="List_". date('Y_m_d',time()) . ".xls";
	 //ob_end_clean();
	 header('Content-Type: application/vnd.ms-excel');
	 header('Content-Disposition: attachment;filename='.$filename);
	 header('Cache-Control: max-age=0');
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	 $objWriter->save('php://output');
	 exit;

}
 public function update(){
          $main=M('main');
		  $data=$_POST;
		  unset($data['__hash__']);
		  $data['pcn_no']=str_replace("No.","",$data['pcn_no']);
		  $pcn_no=$data['pcn_no'];
		  if( strstr($data['exec_status'], 'false')==false && empty($data['exec_status'])==false){
		     $data['curr_status']='COMPLETION';
		    };
	      $main->where("pcn_no=$pcn_no")->save($data);
		  redirect(__URL__);
 }
 public function fileupload(){
 // example of a PHP server code that is called in `uploadUrl` above
// file-upload.php script
     header('Content-Type: application/json'); // set json response headers
     $outData = $this->upload(); // a function to upload the bootstrap-fileinput files
     echo json_encode($outData); // return json data
     exit(); // terminate
  }
public function upload() {
		$preview = $config = $errors = [];
		//$targetDir ='D:\wamp64\www\PCN/Uploads';//DIRECTORY_SEPARATOR 
		//$targetDir ='/opt/apache/html/webtools/PCN/Uploads';
		$targetDir = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/'.APP_NAME.'/Uploads';
		if (!file_exists($targetDir)) {
			@mkdir($targetDir);
		}
		$fileBlob = 'fileBlob';                      // the parameter name that stores the file blob
		if (isset($_FILES[$fileBlob]) && isset($_POST['uploadToken'])) {
			$token = $_POST['uploadToken'];          // gets the upload token											
			$file = $_FILES[$fileBlob]['tmp_name']; 
			$pathinfo = pathinfo($_POST['fileName']);
            $ext= $pathinfo['extension'];// the path for the uploaded file chunk 
			$fileName =$_POST['fileName'];          // you receive the file name as a separate post data
			$sfileName=$_POST['fileName'];
			$fileSize = $_POST['fileSize'];          // you receive the file size as a separate post data
			$fileId = $_POST['fileId'];              // you receive the file identifier as a separate post data
			$index =  $_POST['chunkIndex'];          // the current file chunk index
			$totalChunks = $_POST['chunkCount'];     // the total number of chunks for this file
			$targetFile = $targetDir.'/'.$fileName;  // your target file path
			//$randfilename=random(10).'.'.$ext;
			//$targetFile = $targetDir.'/'.$randfilename;
                                                $tmpfname1=uniqid('PCN_').'.'.$ext;
		               $tmpfname =$targetDir.'/'.$tmpfname1;	
		   if ($totalChunks > 1) {                  // create chunk files only if chunks are greater than 1
				//$targetFile .= '_' . str_pad($index, 4, '0', STR_PAD_LEFT); 
                                                             $tmpfname .= '_' . str_pad($index, 4, '0', STR_PAD_LEFT); 
			} 
			$thumbnail = 'unknown.jpg';
		    $targetFile= iconv('utf-8','gb2312',$targetFile);
			if(move_uploaded_file($file, $tmpfname)) {
				// get list of all chunks uploaded so far to server
				$chunks = glob("{$targetDir}/{$fileName}_*"); 
				// check uploaded chunks so far (do not combine files if only one chunk received)
				$allChunksUploaded = $totalChunks > 1 && count($chunks) == $totalChunks;
				if ($allChunksUploaded) {           // all chunks were uploaded
					$outFile = $targetDir.'/'.$fileName;
					// combines all file chunks to one file
					 $this->combineChunks($chunks, $outFile);
				} 
				// if you wish to generate a thumbnail image for the file
			   //  $path=$targetDir;
				// $targetUrl = $this->getThumbnailUrl($targetDir, $fileName);
			 	// separate link for the full blown image file
				// $zoomUrl = 'http://10.120.1.243/webtools/PCN/Uploads/'.$tmpfname1;
                 $zoomUrl = "http://". $_SERVER['HTTP_HOST'].__ROOT__.'/'.APP_NAME."/uploads/".$tmpfname1;
				 if (($totalChunks>1 && $index==1) || $totalChunks==1){
				 $Attach = M('attach');              
				 $data['name']     =  $sfileName;
				 $data['type']   = $_FILES[$fileBlob]['type']; // mime_content_type($targetFile);
				 $data['size']     =   $fileSize;
				 $data['extension']	=   $ext;
				 $data['savepath']	= 'Uploads/'; //$targetDir ;	
				 $data['savename']     =  $tmpfname1;
				 $data['module']   ='PCN';
				 $data['pcnNo']     =   $_POST['pcn_no'] ?$_POST['pcn_no'] :"";
				 $data['uploadTime'] =   time();
				 $data['category']=$this->getFileExt($_FILES[$fileBlob]['type'],$sfileName);
				 $data['recordid']=$fileId;//$index;
				 $Attach->add($data);  }
			     $type1=$this->getFileExt($_FILES[$fileBlob]['type'],$sfileName);//$model->getLastSql() getDbFields
				
				 return [
					'chunkIndex' => $index,         // the chunk index processed
					'initialPreview' => "http://". $_SERVER['HTTP_HOST'].__ROOT__.'/'.APP_NAME."/uploads/". $fileName, // the thumbnail preview data (e.g. image)
					//'initialPreview' => 'http://10.120.1.243/webtools/PCN/uploads/' . $fileName
					'initialPreviewConfig' => [
						[
							'type' =>$type1,      // check previewTypes (set it to 'other' if you want no content preview)
							'caption' => $sfileName, // caption
							'key' => $fileId,       // keys for deleting/reorganizing preview
							'fileId' => $fileId,    // file identifier
							'size' => $fileSize,    // file size
							'zoomData' => $zoomUrl, // separate larger zoom data
							//'url' =>'http://10.120.1.243/webtools/PCN/index.php/Index/del_file', // separate larger zoom data
							'url' =>"http://". $_SERVER['HTTP_HOST'].__ROOT__.'/'.APP_NAME.'/index.php/Index/del_file', // separate larger zoom data                         
							'filename' =>$tmpfname1,
						]
					],
					'append' => true
				];
			} else {
				return [
					'error' => 'Error uploading chunk ' . $_POST['chunkIndex']
				];
			}
		}
		return [
			'error' => 'No file found'
		];
}
public function other_upload(){
	  //  $targetDir ='D:\wamp64\www\PCN/Uploads';//DIRECTORY_SEPARATOR 
		//$targetDir ='/opt/apache/html/webtools/PCN/Uploads';
		$targetDir='Uploads/';
		$pcn_no = $this->_post('pcn_no'); 	
        $atype =  $this->_post('attach_type');   
		//$url = "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["REQUEST_URI"]);
		//echo '<script> alert("'.$pcn_no.$atype.'")</script>';
		import("@.ORG.UploadFile");
        $upload = new UploadFile();
        //set file size
        //$upload->maxSize  = 32922000 ;
        //set file ext
        $upload->allowExts  = array('rar','zip','doc','swf','txt','ppt','xls','pdf','msg','7z','gif','jpg','png','html','htm','xlsx','ppt','bmp','docx','tgz');        
		if (C('UPLOAD_PATH')) {
			$upload->savePath = C('UPLOAD_PATH');
		} else {
			$upload->savePath = 'Uploads/';
		}        
        $upload->saveRule = 'uniqid';
        $userId = isset($_SESSION[C('USER_AUTH_KEY')])?$_SESSION[C('USER_AUTH_KEY')]:0;		
		//$verify = build_verify(8);
        //$_SESSION['attach_verify'] = $verify;
        $uploadFileVersion = false;  
        $uploadRecord  =  true;
		$module = MODULE_NAME;     
        $uploadId =  array();
        $savename = array();
        //echo "{success:true,id:\"103\",ext:\"text\",size:\"256\"}";
        if(!$upload->upload()) {
                echo "{success:false,message:\"".iconv("UTF-8","gb2312",$upload->getErrorMsg())."\"}";
        }else {
            if($uploadRecord) {
                $uploadList = $upload->getUploadFileInfo();
                $Attach = M('Attach');
                
                foreach($uploadList as $key=>$file) {
                    
                    $file['module']     = 'PCN';
                    $file['recordId']   =   $recordId?$recordId:0;
                    $file['userId']     =   $userId;
                    $file['verify']	=   $verify?$verify:'';
                    $file['remark']	=   $remark[$key]?$remark[$key]:($remark?$remark:'');
                    $file['pcnNo']	=   $pcn_no;
					$file['location_attach']	=   $atype;
					$file['uploadTime'] =   time();
					$file['savepath'] =$targetDir;
                    $uploadId[] =  $Attach->add($file);
                  }
				  echo "{success:true,id:\"".$uploadId[0]."\",ext:\"".$file['extension']."\",size:\"".$file["size"]."\"}";
                }

        }
	
	}
	public function del_Attach()
			{
               $id = $_REQUEST["id"];
		       $Attach=M('attach');
               $fileList=$Attach->where("id=$id")->find();
		       @unlink($fileList['savepath'].'/'.$fileList['savename']);
               $Attach->where("id=$id")->delete();

		 // echo $Attach->getLastSql();
		 // $this->ajaxReturn('{error:false}','JSON');
		   echo json_encode("['info'=>'Success']");


			}
// combine all chunks
// no exception handling included here - you may wish to incorporate that
public function combineChunks($chunks, $targetFile) {
	// open target file handle
	$handle = fopen($targetFile, 'a+');
	foreach ($chunks as $file) {
		fwrite($handle, file_get_contents($file));
	}
	// you may need to do some checks to see if file 
	// is matching the original (e.g. by comparing file size)
	
	// after all are done delete the chunks
	foreach ($chunks as $file) {
		@unlink($file);
	}
	
	// close the file handle
	fclose($handle);
	
}
public function getFileExt($fileMine,$filename) {
	 if(preg_match('/(word|excel|powerpoint|office|iwork-pages|tiff?)$/i', $fileMine)!=0 ||preg_match('/(word|excel|powerpoint|office|iwork-pages|tiff?)$/i', $filename)<>0){
             $extType='office';
	  }elseif(preg_match('/\.(htm|html)$/i', $filename)<>0){
		     $extType='html';
      }elseif(preg_match('/\.(gif|png|jpe?g)$/i', $filename)<>0){
		     $extType='image';
	  }elseif(preg_match('/(word|excel|powerpoint|office|iwork-pages|tiff?)$/i)', $fileMine)!=0 ||preg_match('/\.(rtf|docx?|xlsx?|pptx?|pps|potx?|ods|odt|pages|ai|dxf|ttf|tiff?|wmf|e?ps)$/i', $filename)<>0){
		     $extType='gdocs';
	 }elseif(preg_match('/\.(txt|md|csv|nfo|php|ini)$/i', $filename)<>0){
		     $extType='text';
	 }elseif(preg_match('/\.(og?|mp4|webm)$/i', $filename)<>0){
		     $extType='video';
	 }elseif(preg_match('/\.(pdf)$/i', $filename)<>0){
		     $extType='pdf';
	 }else{
		     $extType='other';
     }

 return $extType;
}



// generate and fetch thumbnail for the file
public function getThumbnailUrl($path, $fileName) {
	// assuming this is an image file or video file
	// generate a compressed smaller version of the file
	// here and return the status
	$sourceFile = $path . '/' . $fileName;
	$targetFile = $path . '/thumbs/' . $fileName;
	//
	// generateThumbnail: method to generate thumbnail (not included)
	// using $sourceFile and $targetFile
	//
	if (generateThumbnail($sourceFile, $targetFile) === true) { 
		//return 'http://10.120.1.243/webtools/PCN/Uploads/thumbs/' . $fileName;
		return "http://". $_SERVER['HTTP_HOST'].__ROOT__.'/'.APP_NAME."/uploads/thumbs/" . $fileName;
	} else {
		//return 'http://10.120.1.243/webtools/PCN/Uploads/' . $fileName; // return the original file
		return "http://". $_SERVER['HTTP_HOST'].__ROOT__.'/'.APP_NAME."/uploads/". $fileName; // return the original file
	}
}
  public function del_file(){
          $fileid = $_POST['key'];
		  if (strpos($fileid ,'_')){
		      $fileid = substr($fileid ,0,strpos($fileid ,'_'));
		  }
		  $pcn_no = $_POST['pcn_no'];
		  $Attach=M('attach');
          $fileList=$Attach->where("pcnNo=$pcn_no and recordid=$fileid")->find();
		  @unlink($fileList['savepath'].'/'.$fileList['savename']);
          $Attach->where("pcnNo=$pcn_no and recordid=$fileid")->delete();

		 // echo $Attach->getLastSql();
		 // $this->ajaxReturn('{error:false}','JSON');
		   echo json_encode("['error'=>'Success']");
 }
  public function getData(){
	 $map="id >0"	;
	 $pcnstatus=$this->_get('pcnstatus');
     $voList = M("main");
	 $ps=$_GET["limit"];
	 $pn=$_GET["offset"];
     $searchtext=$_GET["search"];
 

     $count = $voList->where("internal_pn like '%$searchtext%' ||pcn_no like '%$searchtext%' ")->limit($pn,$ps)->count();
	 $list=$voList->where("internal_pn like '%$searchtext%' ||pcn_no like '%$searchtext%' ")->limit($pn,$ps)->order('id desc')->select();
	// $count = $voList->limit($pn,$ps)->count();
	// $list=$voList->limit($pn,$ps)->select();
	 $data["total"]=$count;
	 $data["rows"]=$list;
    // $this->ajaxReturn($data,'JSON');
	 // $this->ajaxReturn($list,'JSON');	
	 echo  json_encode($data);
	}
	public function getData_approval(){
	 $map="id >0";
	 $pcnstatus=$this->_get('pcnstatus');
     $voList = M("main");
	 $ps=$_GET["limit"];
	 $pn=$_GET["offset"];
     $s_pcn_no=$_GET['pcn_no']!=1?$_GET['pcn_no']:'';
     $s_cs_name=$_GET['cust_name']!=1?$_GET['cust_name']:'';
     $s_cs_pn=$_GET['cust_pn']!=1?$_GET['cust_pn']:'';
     //$s_internal_pn=$_GET['internal_pn']!=1?$_GET['internal_pn']:'';
     $s_internal_pn=$_GET['internal_pn']!=1?" and internal_pn like '%".$_GET['internal_pn']."%'":"";
     $s_pcn_type=$_GET['category']!=1?" and category like '%".$_GET['category']."%'":"";
     $s_curr_status=$_GET['curr_status']?$_GET['curr_status']:'';
     $s_exec_status=$_GET['exec_status']?" and exec_status like '%".$_GET['exec_status']."%'":'';
     $sql="pcn_no like '%".$s_pcn_no."%' and cust_name like '%".$s_cs_name."%' and cust_pn like '%".$s_cs_pn."%' 
           and curr_status like '%".$s_curr_status."%' and curr_status not like '%Rej%' $s_pcn_type $s_exec_status $s_internal_pn";//  and exec_status like '%".$s_exec_status."%'";

    // echo "alert('".$sql."')";     
     if ($this->_get('adv_search')){   
         $count = $voList->where($sql)->limit($pn,$ps)->count(); //"pcn_no like '%". $s_pcn_no ."%'"
	     $list=$voList->where($sql)->limit($pn,$ps)->order('id desc')->select();
         $data["total"]=$count;
	     $data["rows"]=$list;
         $this->ajaxReturn($data,'JSON');
        }
       
     $searchtext=$_GET["search"];
     if ($pcnstatus=='Reject'){
	     $count = $voList->where("curr_status like '%Reject%'")->limit($pn,$ps)->count();
	     $list=$voList->where("curr_status like '%Reject%'")->limit($pn,$ps)->order('id desc')->select();
	 }else{
	     $count = $voList->where("curr_status = '$pcnstatus'")->limit($pn,$ps)->count();
	     $list=$voList->where("curr_status = '$pcnstatus'")->limit($pn,$ps)->order('id desc')->select();
	  }
    
	// $count = $voList->limit($pn,$ps)->count();
	// $list=$voList->limit($pn,$ps)->select();
	 $data["total"]=$count;
	 $data["rows"]=$list;
     $this->ajaxReturn($data,'JSON');
	 // $this->ajaxReturn($list,'JSON');	
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
		$conn = @mysql_connect("10.120.1.243/webtools","root","") or die("connection failed");
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
  function showExt($ext) {
					$extPic = 'common.gif';
					if ($ext == 'pdf'){$extPic = 'pdf.gif';}
					if ($ext == 'rar') {$extPic = 'rar.gif';}
					if ($ext == 'txt') {$extPic = 'text.gif';}
					if ($ext == 'zip') {$extPic = 'zip.gif';}
					if ($ext == 'html') {$extPic = 'html.gif';}
					if ($ext == 'png') {$extPic = 'image.gif';}
					if ($ext == 'gif') {$extPic = 'gif.gif';}
					if ($ext == 'jpg') {$extPic = 'jpg.gif';}
					if ($ext == 'xls') {$extPic = 'xls.gif';}
					if ($ext == 'xlsx') {$extPic = 'xls.gif';}
					if ($ext == 'ppt') {$extPic = 'ppt.gif';}
					if ($ext =='doc') {$extPic ='msoffice.gif';}
					if ($ext =='docx') {$extPic ='msoffice.gif';}
					return  "<IMG SRC='__TMPL__assets/file_extension_image/".$extPic."' BORDER='0' alt='' align='absmiddle'>";														
				} 
 public function byte_format($input, $dec=0)
			{
			  $prefix_arr = array("B", "K", "M", "G", "T");
			  $value = round($input, $dec);
			  $i=0;
			  while ($value>1024)
			  {
				 $value /= 1024;
				 $i++;
			  }
			  $return_str = round($value, $dec).$prefix_arr[$i];
			  return $return_str;
			}

public function sendMail(){
            $mlist = $_REQUEST["mlist"];
            $pcn_no = $_REQUEST["pcn_no"];
            $nform = $_REQUEST["nform"]=='true'?0:1;
           // echo "fddsffdsfdsfds ".$nform;
           // return;
            if ($nform){
		        $this->auto_export_excel($pcn_no); 
                $pcnfile ="PCN". $pcn_no . ".xlsx";
                $filename1='Excelfile/'.$pcnfile; 
           }
		   // echo $Attach->getLastSql();
		   //  $this->ajaxReturn('{success:false}','JSON');
		    $Model = M("main"); 
	        $ls=$Model->where("pcn_no=$pcn_no")->find();
            $cust_name=    explode(";", $ls['cust_name']);            
          //  echo json_encode("['info'=>'".$attachment."']");
			//require_once "D:/wamp64/www/ThinkPHP/Extend/Vendor/PHPMailer/class.phpmailer.php";	
            require_once "/opt/apache/html/webtools/ThinkPHP/Extend/Vendor/PHPMailer/class.phpmailer.php";
			$from=$_SESSION['LoginUserName'].'@ttm.com';
			$title="PCN $pcn_no ". $cust_name[0]. ' to be approved';
			$to=$mlist;
			$cc='Qian.Hu;LY.Cheng;PE.GUD3;'.$_SESSION['LoginUserName'];
	       // $cc='sheng.zhang';
           // $cc='Qian.Hu, LY.Cheng;Lily.wang'.$_SESSION['LoginUserName'];
            $str='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
						<title></title>
						<style type="text/css">
							body{
								font-size:14px;
                                font-family:宋体;
							}
					    </style>
                  </head>
                  <body>';
			$content=$str.'Hi CS team,<br>';
            $content.='&nbsp;&nbsp;&nbsp;Pls forward attached PCN to customer for approval. Thanks!<br>';
            $content.='</body></html>';
			$mail = new PHPMailer();
			//$mail->SMTPDebug = 2;
			$mail->SMTPDebug  = 1;      // 启用SMTP调试功能
													   // 1 = errors and messages
													   // 2 = messages only
			$mail->CharSet ='UTF-8'; //设置采用gb2312中文编码
			$mail->IsSMTP(); //设置采用SMTP方式发送邮件
			$mail->Host ='aphkmail.viasystems.pri'; //设置邮件服务器的地址
			$mail->Port = 25; //设置邮件服务器的端口，默认为25
			$mail->From = $from; //设置发件人的邮箱地址
			$mail->FromName = $_SESSION['displayname']; //设置发件人的姓名
			//$mail->SMTPAuth = true; //设置SMTP是否需要密码验证，true表示需要
			$mail->Username = $from; //设置发送邮件的邮箱
			$mail->Password = ""; //设置邮箱的密码
			$mail->Subject = $title; //设置邮件的标题
			$mail->AltBody = "text/html"; // optional, comment out and test
			$mail->Body = $content; //设置邮件内容
			$mail->IsHTML(true); //设置内容是否为html类型
			$mail->WordWrap = 50; //设置每行的字符数
			$mail->AddReplyTo("pe.gud3@ttm.com",""); //设置回复的收件人的地址
			foreach (split(';',$to) as $key => $value) {
					$mail_to = $value;
					if ($mail_to != '') {
						$mail->AddAddress($mail_to . '@ttm.com',"");//设置收件的地址
					}
				}
			 foreach (split(';',$cc) as $key => $value) {
						$mail_cc = $value;
						if ($mail_cc != '') {
							$mail->Addcc($mail_cc . '@ttm.com',"");//设置收件的地址
						}
					}
              if (file_exists( $filename1)){
                  $mail->AddAttachment($filename1,$filename);	
              }
			  $Attach = M('attach');
			  $AttachFile=$Attach->where("pcnNo=$pcn_no and location_attach='spcn'")->select();			                    
		   	if(!empty($AttachFile)){ //判断一级是否为空
				 foreach($AttachFile as $key=>$value){   //循环读取                    
				    $mail->AddAttachment($value['savepath'].$value['savename'],$value['name']);	   
				 }
		     }   
							/*if ($attachment != '') //设置附件
							{
							$mail->AddAttachment($attachment,'test.xlsx');
							}*/
							if(!$mail->Send())
							{
								echo $mail->ErrorInfo;
							} else {
								echo 'Sending Mail successfully';
                                //echo $attach;
							}
		} 
	
  public function auto_export_excel($pcn_no){
	            ini_set("max_execution_time","-1"); 
				Vendor("PHPExcel.PHPExcel");
				 $pcn_no=$pcn_no;
				 $Model = M("main");
				 $ls=$Model->where("pcn_no=$pcn_no")->find();
				 $internal_pn = explode(";",$ls['internal_pn']);
				 $cust_pn =     explode(";",$ls['cust_pn']);
				 $cust_name=    explode(";", $ls['cust_name']);
				$objPHPExcel = new PHPExcel();
			    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
                $objPHPExcel = $objReader->load("Tpl/template/pcn_tmp_20200505.xlsx");
			    $objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->setCellValue('L9', $cust_name[0]);
				$objPHPExcel->getActiveSheet()->setCellValue('BB7', $pcn_no);
				//$objPHPExcel->getActiveSheet()->setCellValue('AS9', $cust_pn[0]);
				$objPHPExcel->getActiveSheet()->setCellValue('AS9', 'See below PN list');
				$objPHPExcel->getActiveSheet()->setCellValue('AS10', $ls['initiation_date']);
				$objPHPExcel->getActiveSheet()->setCellValue('L10', $ls['initiator_dept']);
				$objPHPExcel->getActiveSheet()->setCellValue('S15', $ls['before_change']);
				$objPHPExcel->getActiveSheet()->setCellValue('S18', $ls['after_change']);
				$objPHPExcel->getActiveSheet()->setCellValue('S21', $ls['reason_change']);
				//$objPHPExcel->getActiveSheet()->setCellValue('AR14', $internal_pn[0]);
				$objPHPExcel->getActiveSheet()->setCellValue('AR14', 'See below PN list');
				$objPHPExcel->getActiveSheet()->setCellValue('B36', 'Comments:'.$ls['affect_remark']);
				//$objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			    $objPHPExcel->getActiveSheet()->getStyle('C12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			    $objPHPExcel->getActiveSheet()->getStyle('C12')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				if ($ls['affect_shipping_schedule']==0){
			      //  $objPHPExcel->getActiveSheet()->setCellValue('E19',  "√ No     × Yes    (Remark:".$ls['reason_change'].")"); #FFFFFF 
				  $objPHPExcel->getActiveSheet()->getStyle( 'AK24')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                  $objPHPExcel->getActiveSheet()->getStyle( 'AK24')->getFill()->getStartColor()->setARGB('0000FF');
				   $objPHPExcel->getActiveSheet()->getStyle( 'AR24')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                  $objPHPExcel->getActiveSheet()->getStyle( 'AR24')->getFill()->getStartColor()->setARGB('FFFFFF');
				
                }else{
				  $objPHPExcel->getActiveSheet()->getStyle( 'AK24')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                  $objPHPExcel->getActiveSheet()->getStyle( 'AK24')->getFill()->getStartColor()->setARGB('FFFFFF');
				   $objPHPExcel->getActiveSheet()->getStyle( 'AR24')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                  $objPHPExcel->getActiveSheet()->getStyle( 'AR24')->getFill()->getStartColor()->setARGB('0000FF');
								}
				 $ec =70;
			     foreach($internal_pn as $key=>$val){
					 $ec=$ec+1;
					 $objPHPExcel->getActiveSheet()->setCellValue('B'.$ec, $cust_name[$key]);
					 $objPHPExcel->getActiveSheet()->setCellValue('T'.$ec, $cust_pn[$key]);
					 $objPHPExcel->getActiveSheet()->setCellValue('AS'.$ec, $val);
				 }
				  $objPHPExcel->getActiveSheet()->setCellValue('B32', $ls['pe_m']);
				  $objPHPExcel->getActiveSheet()->setCellValue('B34', 'Digitally Signed by '.$ls['pe_m']);
				  $objPHPExcel->getActiveSheet()->setCellValue('B35', $ls['pe_m_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('V32', $ls['pre_m']);
				  $objPHPExcel->getActiveSheet()->setCellValue('V34', 'Digitally Signed by '.$ls['pre_m']);
				  $objPHPExcel->getActiveSheet()->setCellValue('V35', $ls['pre_m_d']);
                  $objPHPExcel->getActiveSheet()->setCellValue('AQ32', $ls['qa_m']);
				  $objPHPExcel->getActiveSheet()->setCellValue('AQ34', 'Digitally Signed by '.$ls['qa_m']);
				  $objPHPExcel->getActiveSheet()->setCellValue('AQ35', $ls['qa_m_d']);

				  $objPHPExcel->getActiveSheet()->setCellValue('B38', $ls['engi_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('B40', 'Digitally Signed by '.$ls['engi_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('B41', $ls['engi_d_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('V38', $ls['prod_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('V40', 'Digitally Signed by '.$ls['prod_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('V41', $ls['prod_d_d']);
                  $objPHPExcel->getActiveSheet()->setCellValue('AQ38', $ls['qa_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('AQ40', 'Digitally Signed by '.$ls['qa_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('AQ41', $ls['qa_d_d']);
                  $filename ="PCN". $pcn_no . ".xlsx";
                   $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                   $objWriter->save("Excelfile/$filename");
                   return true;
				  
 }
/*window.AjaxUpload = function (button, options) {    
  this._settings = {       
       // Location of the server-side upload script       
       action: 'upload.php',       
       // File upload name       
       name: 'userfile',        
       // Additional data to send        
       data: {},        
       // Submit file as soon as it's selected        
       autoSubmit: true,        
       // The type of data that you're expecting back from the server.       
       // html and xml are detected automatically.        
       // Only useful when you are using json data as a response.                       
       // Set to "json" in that case.        
      responseType: false,        
       // Class applied to button when mouse is hovered  
      hoverClass: 'hover',        
       // Class applied to button when AU is disabled     
      disabledClass: 'disabled',        
       // When user selects a file, useful with autoSubmit disabled     
       // You can return false to cancel upload        
      onChange: function (file, extension) {     
        },        
       // Callback to fire before file is uploaded        
       // You can return false to cancel upload       
      onSubmit: function (file, extension) {  
        },        
       // Fired when file upload is completed        
       // WARNING! DO NOT USE "FALSE" STRING AS A RESPONSE!
       onComplete: function (file, response) {    
        }  
  };
  function () {    
  var options = {       
       action: prefix+'/upload-img',     //your url   
       responseType: 'json',        
       name: 'files',        
       onSubmit: function (file, extension) {           
        //do sth on submit
       },       
       onComplete: function (file, uploadResult) {       
        //do sth on complete
       },        
       onChange: function (file, extension) {        
       //do sth on change
       }   
    };    
      new AjaxUpload($("#btn"), options);
}*/
}