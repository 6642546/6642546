<?php
class IndexAction extends CommonAction {
  public function index(){
	    // var_dump($_SESSION['access_rules']);
		//  var_dump($this->getData());
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
      $this->display();
 }
	public function edit(){
	     $pcn_no= $this->_get('pcn_no');
		 $Model = M("main"); 
	     $ls=$Model->where("pcn_no=$pcn_no")->find();
		 $internal_pn = explode(";",$ls['internal_pn']);
         $cust_pn =     explode(";",$ls['cust_pn']);
         $cust_name=    explode(";", $ls['cust_name']);
		 $tableDetail="";
		/* $actions='<a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a>
					 <a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>
					 <a class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>';*/
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
			 $AttachFile=$Attach->where("pcnNo=$pcn_no")->select();
			 if(!empty($AttachFile)){ //判断一级是否为空
				 foreach($AttachFile as $key=>$value){   //循环读取
					 $initialPreview[]="http://localhost/PCN/uploads/".$value['name'];
					 $initialPreviewConfig[]=array("type"=>$value['category'],
												   "size"=>$value['size'],
												   "caption"=>$value['name'],
												   "url"=>"__URL__/del_file",
												   "key"=>$value['recordid'], 
						                           "filename"=>$value['savename'] );
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
		  $this->assign("initialPreview",$initialPreview);
		  $this->assign("initialPreviewConfig",$initialPreviewConfig);
		  $this->assign("userName",$_SESSION['displayname']);
		  $this->assign('vo',$ls);
		  $this->assign("tableDetail",$tableDetail);
		  $this->assign("mlist",$prem1);
		  $this->display();
	 }
	public function approval(){
	     $pcn_no= $this->_get('pcn_no');
		 $Model = M("main"); 
	     $ls=$Model->where("pcn_no=$pcn_no")->find();
		 $internal_pn = explode(";",$ls['internal_pn']);
         $cust_pn =     explode(";",$ls['cust_pn']);
         $cust_name=    explode(";", $ls['cust_name']);
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
			 $AttachFile=$Attach->where("pcnNo=$pcn_no")->select();
			 if(!empty($AttachFile)){ //判断一级是否为空
				 foreach($AttachFile as $key=>$value){   //循环读取
					 $initialPreview[]=urldecode("http://localhost/PCN/uploads/".$value['savename']);
					 $initialPreviewConfig[]=array("type"=>$value['category'],
												   "size"=>$value['size'],
												   "caption"=>$value['name'],
												   //"url"=>"__URL__/del_file",
												   "key"=>$value['recordid'],
												   "filename"=> ($value['savename']));
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
							 $timeline.= '<li">
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>Sending Customer</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s") .'</small></p> 							  
									</div>
									<div class="tl-body">
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
					         $timeline.='<li><div >'.date("M Y",strtotime($ls['s_d_d'])).'</div></li>';
					     	 }	  
							 $timeline.= '<li>
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>Sending Customer</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['s_c_d'])).'</small></p> 							  
									</div>
									<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="s_c_d" id="qa_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['s_c_d'].'" readonly>
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
		  $this->display();
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
			 $AttachFile=$Attach->where("pcnNo=$pcn_no")->select();
			 if(!empty($AttachFile)){ //判断一级是否为空
				 foreach($AttachFile as $key=>$value){   //循环读取
					 $initialPreview[]=urldecode("http://localhost/PCN/uploads/".$value['savename']);
					 $initialPreviewConfig[]=array("type"=>$value['category'],
												   "size"=>$value['size'],
												   "caption"=>$value['name'],
												   //"url"=>"__URL__/del_file",
												   "key"=>$value['recordid'],
													"filename"=> urldecode($value['savename'])  );
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
          if($ls['curr_status']=='PE' ||strpos($ls['curr_status'],'PE/Reject') !== false){
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
									 <input type="text" class="form-control" name="pe_m_r" id="pe_m_r" placeholder="'.L('affect_remark').'" style="outline: 0;border-width: 0 0 1px; border-color: light-green" readonly>
									 <input type="text" class="form-control" name="curr_status" id="curr_status"  style="outline: 0;border-width: 0 0 1px; border-color: light-green"  value="PRE"  hidden >
                                 
							</div>
						  </div>
						</li>';
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
									 <input type="text" class="form-control" name="pre_m_r" id="pre_m_r" placeholder="'.L('affect_remark').'" style="outline: 0;border-width: 0 0 1px; border-color: light-green" readonly>
									 <input type="text" class="form-control" name="curr_status" id="curr_status"  style="outline: 0;border-width: 0 0 1px; border-color: light-green"  value="QA"  hidden >
                                 
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
									 <input type="text" class="form-control" name="qa_m_r" id="qa_m_r" placeholder="'.L('affect_remark').'" style="outline: 0;border-width: 0 0 1px; border-color: light-green" readonly>
									 <input type="text" class="form-control" name="curr_status" id="curr_status"  style="outline: 0;border-width: 0 0 1px; border-color: light-green"  value="ENGD"  hidden >
                                 
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
									 <input type="text" class="form-control" name="engi_d_r" id="geni_d_r" placeholder="'.L('affect_remark').'" style="outline: 0;border-width: 0 0 1px; border-color: light-green" readonly>
									 <input type="text" class="form-control" name="curr_status" id="curr_status"  style="outline: 0;border-width: 0 0 1px; border-color: light-green"  value="MFGD"  hidden >
                                 
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
									 <input type="text" class="form-control" name="prod_d_r" id="prod_d_r" placeholder="'.L('affect_remark').'" style="outline: 0;border-width: 0 0 1px; border-color: light-green" readonly>
									 <input type="text" class="form-control" name="curr_status" id="curr_status"  style="outline: 0;border-width: 0 0 1px; border-color: light-green"  value="QAD"  hidden >
                                 
							</div>
						  </div>
						</li>';
			  }else if(($ls['curr_status']=='QAD'||$ls['curr_status']=='SPCN'||$ls['curr_status']=='CCOMP'||$ls['curr_status']=='PEE') || strpos($ls['curr_status'],'Reject') !== false){

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
								 if(date("M Y",strtotime($ls['qa_d_d']))<>date("M Y")) {
									 $timeline.='<li><div class="tldate">'.date("M Y").'</div></li>';
								 }			  
							 $timeline.= '<li">
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>Sending Customer</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s") .'</small></p> 							  
									</div>
									<div class="tl-body">
											 <input type="text" class="form-control"  aria-lable="Singal" name="s_c_o" id="s_c_o" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['s_c_o'].'" readonly>
											 <input type="text" class="form-control" name="s_c_r" id="s_c_r" placeholder="'.L('affect_remark').'" style="outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["s_c_r"].'"  readonly>	
											
										 
									</div>
								  </div>
								</li>';
				       
				   }else if($ls['curr_status']=='CCOMP' ||strpos($ls['curr_status'],'CCOMP/Reject') !== false){
				 
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
					         $timeline.='<li><div >'.date("M Y",strtotime($ls['s_d_d'])).'</div></li>';
					     	 }	  
							 $timeline.= '<li>
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>Sending Customer</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['s_c_d'])).'</small></p> 							  
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
				  }else if($ls['curr_status']=='PEE'||strpos($ls['curr_status'],'PEE/Reject') !== false){
				 
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
										 <input type="text" class="form-control"  aria-lable="Singal" name="qa_d" id="qa_d" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['prod_d'].'" readonly>
										 <input type="text" class="form-control" name="qa_d_r" id="prod_d_r" placeholder="'.L('affect_remark').'" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["qa_d_r"].'"  readonly>								 
								</div>
								  </div>
								</li>';
						  if(date("M Y",strtotime($ls['qa_d_d']))<>date("M Y",strtotime($ls['s_c_d']))) {
					         $timeline.='<li><div >'.date("M Y",strtotime($ls['s_d_d'])).'</div></li>';
					     	 }	  
							 $timeline.= '<li>
								  <div class="tl-circ"></div>
								  <div class="timeline-panel">
									<div class="tl-heading">
									  <h4>Sending Customer</h4>
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['s_c_d'])).'</small></p> 							  
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
									  <p><small class="text-muted"><i class="fas fa-clock"></i>'. date("Y-m-d H:i:s",strtotime($ls['r_c_d'])) .'</small></p> 							  
									</div>
									<div class="tl-body">								
											 <input type="text" class="form-control"  aria-lable="Singal" name="r_c_o" id="r_c_o" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['r_c_o'].'" readonly>
											 <input type="text" class="form-control" name="r_c_r" id="r_c_r" placeholder="'.L('affect_remark').'" style="outline: 0;border-width: 0 0 1px; border-color: light-green" value="'.$ls["r_c_r"].'"  readonly>		
											 
										 
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
				 }

			 }

		
          $timeline.='</ul>';
		  $this->assign("timeline",$timeline);
		  $this->assign("initialPreview",$initialPreview);
		  $this->assign("initialPreviewConfig",$initialPreviewConfig);
		  $this->assign("userName",$_SESSION['displayname']);
		  $this->assign('vo',$ls);
		  $this->assign("tableDetail",$tableDetail);
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
			                            } 									
									$tableDetail.="<td>$exec_user[$key]</td>
										<td>$exec_date[$key]</td>
										<td>$exec_remark[$key]</td>
										<td>$actions</td>
									   </tr>";
			 }
			 $Attach = M('attach');
			 $AttachFile=$Attach->where("pcnNo=$pcn_no")->select();
			 if(!empty($AttachFile)){ //判断一级是否为空
				 foreach($AttachFile as $key=>$value){   //循环读取
					 $initialPreview[]=urldecode("http://localhost/PCN/uploads/".$value['savename']);
					 $initialPreviewConfig[]=array("type"=>$value['category'],
												   "size"=>$value['size'],
												   "caption"=>$value['savename'],
												   //"url"=>"__URL__/del_file",
												   "key"=>$value['recordid'] );
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
								<div class="tl-body">   
										 <input type="text" class="form-control"  aria-lable="Singal" name="s_c_o" id="s_c_o" style="background-color:white;outline: 0;border-width: 0 0 1px; border-color: light-green" value="'. $ls['qa_d'].'" readonly>
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
                $objPHPExcel = $objReader->load("Tpl/template/pcn_tmp_200420.xlsx");
			    $objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->setCellValue('L9', $cust_name[0]);
				$objPHPExcel->getActiveSheet()->setCellValue('BB7', $pcn_no);
				$objPHPExcel->getActiveSheet()->setCellValue('AS9', $cust_pn[0]);
				$objPHPExcel->getActiveSheet()->setCellValue('AS10', $ls['initiation_date']);
				$objPHPExcel->getActiveSheet()->setCellValue('L10', $ls['initiator_dept']);
				$objPHPExcel->getActiveSheet()->setCellValue('S15', $ls['before_change']);
				$objPHPExcel->getActiveSheet()->setCellValue('S18', $ls['after_change']);
				$objPHPExcel->getActiveSheet()->setCellValue('S21', $ls['reason_change']);
				$objPHPExcel->getActiveSheet()->setCellValue('AR14', $internal_pn[0]);
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
				 $ec =64;
			     foreach($internal_pn as $key=>$val){
					 $ec=$ec+1;
					 $objPHPExcel->getActiveSheet()->setCellValue('B'.$ec, $cust_name[$key]);
					 $objPHPExcel->getActiveSheet()->setCellValue('T'.$ec, $cust_pn[$key]);
					 $objPHPExcel->getActiveSheet()->setCellValue('AS'.$ec, $val);
				 }
				  $objPHPExcel->getActiveSheet()->setCellValue('B32', $ls['pe_m'].'/'.$ls['pe_m_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('V32', $ls['pre_m'].'/'.$ls['pre_m_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('AQ32', $ls['qa_m'].'/'.$ls['qa_m_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('B35',   $ls['engi_d'].'/'.$ls['engi_d_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('V35', $ls['prod_d'].'/'.$ls['prod_d_d']);
				  $objPHPExcel->getActiveSheet()->setCellValue('AQ35', $ls['qa_d'].'/'.$ls['qa_d_d']);
               // $filename ="List_". date('Y_m_d',time()) . ".xls";
			    $filename ="List_". date('Y_m_d',time()) . ".xlsx";
				//ob_end_clean();
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename='.$filename);
                header('Cache-Control: max-age=0');
              //  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
                exit;
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
  
 public function update(){
          $main=M('main');
		  $data=$_POST;
		  unset($data['__hash__']);
		  $data['pcn_no']=str_replace("No.","",$data['pcn_no']);
		  $pcn_no=$data['pcn_no'];
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
		$targetDir ='D:\wamp64\www\PCN/Uploads';//DIRECTORY_SEPARATOR 
		//$targetDir ='/opt/apache/html/webtools/PCN/Uploads';
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
			//if(move_uploaded_file($file, $targetFile)) {
			if(move_uploaded_file($file, $tmpfname)) {
				// get list of all chunks uploaded so far to server
				$chunks = glob("{$targetDir}/{$fileName}_*"); 
				// check uploaded chunks so far (do not combine files if only one chunk received)
				$allChunksUploaded = $totalChunks > 1 && count($chunks) == $totalChunks;
				if ($allChunksUploaded) {           // all chunks were uploaded
					$outFile = $targetDir.'/'.$tmpfname1;
					// combines all file chunks to one file
					 $this->combineChunks($chunks, $outFile);
				} 
				// if you wish to generate a thumbnail image for the file
			   //  $path=$targetDir;
				// $targetUrl = $this->getThumbnailUrl($targetDir, $fileName);
			 	// separate link for the full blown image file
				 $zoomUrl = urldecode('http://localhost/PCN/Uploads/'.$tmpfname1);
				 if (($totalChunks>1 && $index==1) || $totalChunks==1){
				 $Attach = M('attach');              
				 $data['name']     =  $sfileName;
				 $data['type']   = $_FILES[$fileBlob]['type']; // mime_content_type($targetFile);
				 $data['size']     =   $fileSize;
				 $data['extension']	=   $ext;
				 $data['savepath']	=  $targetDir ;
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
					'initialPreview' => urldecode('http://localhost/PCN/uploads/' . $fileName), // the thumbnail preview data (e.g. image)
					//'initialPreview' => 'http://10.120.1.243/webtools/PCN/uploads/' . $fileName
					'initialPreviewConfig' => [
						[
							'type' =>$type1,      // check previewTypes (set it to 'other' if you want no content preview)
							'caption' => $sfileName, // caption
							'key' => $fileId,       // keys for deleting/reorganizing preview
							'fileId' => $fileId,    // file identifier
							'size' => $fileSize,    // file size
							'zoomData' => $zoomUrl, // separate larger zoom data
							'url' =>urldecode('http://localhost/PCN/index.php/Index/del_file'), // separate larger zoom data
                            'filename' =>$tmpfname1, // separate larger zoom data
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
		return urldecode('http://localhost/PCN/Uploads/thumbs/' . $fileName);
	} else {
		return urldecode('http://localhost/PCN/Uploads/' . $fileName); // return the original file
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
	
 

     $count = $voList->where("internal_pn like '%$searchtext%' ")->limit($pn,$ps)->count();
	 $list=$voList->where("internal_pn like '%$searchtext%' ")->limit($pn,$ps)->order('id desc')->select();
	// $count = $voList->limit($pn,$ps)->count();
	// $list=$voList->limit($pn,$ps)->select();
	 $data["total"]=$count;
	 $data["rows"]=$list;
     $this->ajaxReturn($data,'JSON');
	 // $this->ajaxReturn($list,'JSON');	
	// echo  json_encode($data);
	}
	public function getData_approval(){
	 $map="id >0"	;
	 $pcnstatus=$this->_get('pcnstatus');
     $voList = M("main");
	 $ps=$_GET["limit"];
	 $pn=$_GET["offset"];
     $searchtext=$_GET["search"];
 

     $count = $voList->where("curr_status = '$pcnstatus' and internal_pn like '%$searchtext%' ")->limit($pn,$ps)->count();
	 $list=$voList->where("curr_status = '$pcnstatus' and internal_pn like '%$searchtext%'  ")->limit($pn,$ps)->order('id desc')->select();
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