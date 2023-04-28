<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
 
  <title>Administration</title>

  <!-- Bootstrap core CSS --> 
  <link rel="stylesheet" href="__TMPL__assets/dist/css/bootstrap.min.css">
  <!-- Custom styles for this template -->
  <link href="__TMPL__assets/css/simple-sidebar.css" rel="stylesheet">
  <!--link href="__TMPL__assets/font-awesome/css/font-awesome.min.css" rel="stylesheet"-->
  <link href="__TMPL__assets/fontawesome/css/all.css" rel="stylesheet">
  <link href="__TMPL__assets/css/pcn.css" rel="stylesheet">
  <link href="__TMPL__bootstrap-table1.14.1.css" rel="stylesheet">
  <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
   <script src="__TMPL__assets/js/ie10-viewport-bug-workaround.js"></script>
  <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->

  
  <style>
    table tbody tr td{
        white-space:nowrap ;
        overflow:hidden;
    }
	.reject{
	   text-decoration:line-through;
	   color:red;
	}

  </style>
</head>

<body >

   <div class="d-flex" id="wrapper">
    <!-- Sidebar -->
      <div class="bg-light border-right" id="sidebar-wrapper">
		  <div class="sidebar-heading" style="background-image: url('__TMPL__logo.png');background-repeat: no-repeat;background-size:100% 100%;">.</div>
		  <div class="list-group list-group-flush">
		  <a href="#" class="list-group-item list-group-item-action bg-light list-group-item-light" onclick='action_pcn("setting")'><?php echo (L("setting")); ?></a>
			<a href="__APP__/Public/logout" class="list-group-item list-group-item-action bg-light" ><?php echo (L("logout")); ?></a>
          </div>
     </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">
      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">      
        <span class="navbar-toggler-icon" id="menu-toggle"></span><!--&#128263;-->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse " id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
            <li class="nav-item active">
              <a class="nav-link" href="__APP__"><i class="fa fa-fw fa-home"></i><?php echo (L("home")); ?> <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#"><i class="fas fa-user" style="font-size: 18px;"></i><?php echo ($userName); ?></a>
            </li>
			<!--li class="nav-item">
              <a class="nav-link" href="__APP__?l=en-us"><?php echo (L("en-us")); ?></a>
            </li>
			<li class="nav-item">
              <a class="nav-link" href="__APP__?l=zh-cn"><?php echo (L("cn-zh")); ?></a>
            </li-->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-cog" style="font-size: 18px;"></i><?php echo (L("set")); ?>
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="__APP__?l=en-us"><?php echo (L("en-us")); ?></a>
                <a class="dropdown-item" href="__APP__?l=zh-cn"><?php echo (L("cn-zh")); ?></a>
               
              </div>
            </li>
          </ul>
        </div>

      </nav>	
	 
		<!--hr style='margin-top:-3px;'-->
			  <div class="container-fluid" style='margin-left:2px; position:relative;border-bottom: 2px solid #ff0000;'>
			     <div id="toolbar">
					  <div class="btn-toolbar" role="toolbar">
							  <div class="btn-group">
								<button type="button" class="btn btn-default tico" data-toggle="tooltip" data-placement="top"  title="New" onclick='add_recd()'>
								  <i class="fa fa-plus-circle"></i>
								</button>
								<button type="button" class="btn btn-default tico" data-toggle="tooltip" data-placement="top"  title="Edit" onclick='edit_recd()'>
								 <i class="fa fa-edit" ></i> 
								</button>
								<button type="button" class="btn btn-default tico" data-toggle="tooltip" data-placement="top"  title="Del" onclick='del_recd()'>
								 <a href='#'> <i class="fa fa-trash-alt"></i></a>
								</button>						
								<button type="button" class="btn btn-default tico" data-toggle="tooltip" data-placement="top"  title="Refresh" onclick="refresh_recd()">
								 <i class="fa fa-sync-alt"></i>
								</button>
							  </div>				  
						</div>
				 </div>
			     <table id='tradeList' class="table" >
                 <thead>
					  <tr>
						<th data-checkbox="true"></th>
					   <th data-field="id" data-sortable="true">ID</th>
					  </tr>
			   </thead>
			   </table>
			 
			  </div>
		       <div class="footer-copyright text-center py-3">© 2020 Copyright:
                   <a href="#">Pcn Work Flow 1.00</a>
               </div>
				<!-- Central Modal Small -->
				<div class="modal fade" id="centralModalSm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
				  aria-hidden="true">
			  <!-- Change class .modal-sm to change the size of the modal -->
				  <div class="modal-dialog  modal-notify modal-info" role="document">
					<div class="modal-content">
					  <div class="modal-header">
						<h6 class="modal-title w-100" id="myModalLabel"><i class="fas fa-info-circle" style="font-size: 24px;color:red;"></i>&nbsp;信息提示</h6>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						  <span aria-hidden="true">&times;</span>
						</button>
					  </div>
					  <div class="modal-body">
						<div class="alert alert-warning" role="alert">
						    请勾选择一条待操作的的记录!
						</div>
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-info btn-sm" data-dismiss="modal">确定</button>
					
					  </div>
					</div>
				  </div>
				</div>
				<!-- Central Modal Small -->
			
    </div>
    <!-- /#wrapper -->
  				
  <!-- Bootstrap core JavaScript -->
  <script src="__TMPL__assets/js/jquery-3.4.1.js"></script>
  <script src="__TMPL__assets/js/popper.min.js"></script>
  <script src="__TMPL__assets/dist/js/bootstrap.min.js"></script>
  <script src="__TMPL__bootstrap-table1.14.1.js"></script>
  <!-- Menu Toggle Script -->

</body>
 <script>
     var TableInit = function () {
        var oTableInit = new Object();
        oTableInit.Init = function () {
            $('#tradeList').bootstrapTable('destroy').bootstrapTable({
                url: '__URL__/getUserRule',         //请求后台的URL（*）
                method: 'get',                      //请求方式（*）
                toolbar: '#toolbar',                //工具按钮用哪个容器
                striped: true,                      //是否显示行间隔色
                cache: false,                       //是否使用缓存，默认为true，所以一般情况下需要设置一下这个属性（*）
                paginationHAlign:"right",
                paginationDetailHAlign:"left",
                pagination: true,                   //是否显示分页（*）
                sortable: true,                     //是否启用排序
                sortOrder: "asc",                   //排序方式
                queryParams: oTableInit.queryParams,//传递参数（*）
				contentType: "application/x-www-form-urlencoded",
				queryParamsType: "limit",  //设置为undefined可以获取pageNumber，pageSize，searchText，sortName，sortOrder   //设置为limit可以获取limit, offset, search, sort, order  
                sidePagination: "server",           //分页方式：client客户端分页，server服务端分页（*）
                pageNumber:1,                       //初始化加载第一页，默认第一页
                pageSize: 10,                       //每页的记录行数（*）
                pageList: [10, 25, 50, 100],        //可供选择的每页的行数（*）
                strictSearch: true,
                clickToSelect:false,                //是否启用点击选中行
               // height: 400,                        //行高，如果没有设置height属性，表格自动根据记录条数觉得表格高度
                uniqueId: "id",                     //每一行的唯一标识，一般为主键列
                cardView: false,                    //是否显示详细视图
                detailView: false,                   //是否显示父子表
				search: true, 
				searchOnEnterKey:false,
				trimOnSearch:true,
				//showRefresh: true, 
				showToggle:true,
				showColumns:true,
				showToggle:true,
			    showfullscreen:true, 
				singleSelect:true,
				//singleselect:true,
				//checkbox: true,
				// showExport: true, 
				// exportDataType:$(this).val(),
                // exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf'],
				//showFooter:true,				
				 // detailView:true,
				 rowStyle: rowStyle ,
				 classes: "table table-bordered table-hover table-sm", 
				 //theadClasses:"thead-light",
		    	 detailFormatter:detailFormatter,
				//detailViewByClick:true,
				 onDblClickRow:function(row,field){
			     // alert('dfssdfsddfs');
				 },
                columns: [{
                    title: "data-checkbox='true'"},
				  {
                    field: 'id',
                    title: "<?php echo (L("id")); ?>",
					width:'3%'
                 }, {
                    field: 'user_name',
                    title: "<?php echo (L("user_name")); ?>",
					width:'8%'
                },/* {
                    field: 'user_pwd',
                    title: "<?php echo (L("user_pwd")); ?>"
                }, */{
                    field: 'role',
                    title: "<?php echo (L("role")); ?>",
					width:'6%'
                }, {
                    field: 'access_rule',
                    title: "<?php echo (L("access_rule")); ?>"
                }, {
                    field: 'last_time',
                    title: "<?php echo (L("last_time")); ?>",
					width:'10%'
                },{
                    field: 'local_ip',
                    title: "<?php echo (L("local_ip")); ?>",
					width:'8%'
                }, {
                    field: 'status',
                    title: "<?php echo (L("status")); ?>",
					width:'4%'
					//formatter : currStatus

                }/*, {
          field: 'operate',
          title: 'Action',
          align: 'center',
          clickToSelect: false,
          events: window.operateEvents,
          formatter: operateFormatter
        }*/
				]
            });
        };
    
      oTableInit.queryParams = function (params) {
            var temp = {   //这里的键的名字和控制器的变量名必须一直，这边改动，控制器也需要改成一样的
                limit: params.limit,   //页面大小
                offset: params.offset,  //页码
                search:params.search,
                orderid: $("#orderid").val(),
                };
            return temp;
        };
        oTableInit.detailFormatter=function (index, row, element){
			return  index ; 
			
			};

        return oTableInit;
    }
	
		 function footerStyle(column) {
			return {
			  css: { 'font-weight': 'normal' },
			  classes: 'my-class'
			}
		  }
		function detailFormatter(index, row, element) { return row.title; }
		function rowStyle(row, index) {
			var classes = [
			  'bg-blue',
			  'bg-green',
			  'bg-orange',
			  'bg-yellow',
			  'bg-red'
			]
              
			if (row.status==0) {
			  return {
				 classes: 'reject'
			  }
			}
			return {
			 /* css: {
				color: 'red' //text-decoration:line-through

			  }*/
			  // classes: 'reject'
			}
		  }

          function operateFormatter(value, row, index) {
			   if (row.curr_status=='PEE')
			   {
                   return [
				  '<a class="PEExcute" href="javascript:void(0)" title="PE Excute">',
				  '<i class="fas fa-user-cog" style="font-size: 18px;"></i>',
				  '</a>  ' ,
				   '<a class="view" href="javascript:void(0)" title="View">',
				  '<i class="fas fa-eye" style="font-size: 18px;"></i></i>',
				  '</a>  ' 
				].join('')
			   }else if(row.curr_status.search('Reject')!=-1){
			      
				   return [
				   '<a class="view" href="javascript:void(0)" title="View">',
				  '<i class="fas fa-eye" style="font-size: 18px;"></i></i>',
				  '</a>  ' 
				].join('')
			   
			   }else{
				return [
				  '<a class="approved" href="javascript:void(0)" title="Approval">',
				  '<i class="fas fa-thumbs-up" style="font-size: 18px;"></i>',
				  '</a>  ',
				   '<a class="view" href="javascript:void(0)" title="View">',
				  '<i class="fas fa-eye" style="font-size: 18px;"></i></i>',
				  '</a>  ' 
				].join('')
			  }
            }
			  window.operateEvents = {
				'click .approved': function (e, value, row, index) {
				//  alert('You click like action, row value: ' + row.curr_status);//JSON.stringify(row))
				   location.href ="__URL__/approval/pcn_no/"+row.pcn_no;
				},
			  'click .view': function (e, value, row, index) {
					//alert('ffddffd');
				  location.href ="__URL__/view_detail/pcn_no/"+row.pcn_no;
				  },
			   'click .PEExcute': function (e, value, row, index) {
					//alert('ffddffd');
				  location.href ="__URL__/pe_exec/pcn_no/"+row.pcn_no;
				  },
				}
			 function currStatus(value,row, index) {
				/*var c = '<a class="green-color" href="#"  οnclick="info(\''
					+ row.id
					+ '\')">查看</a> ';
				var e = '<a class="green-color" href="#"  οnclick="input(\''
						+ row.id
						+ '\')">编辑</a> ';
				var d = '<a class="red-color" href="#"  οnclick="del(\''
						+ row.id
						+ '\')">删除</a> ';*/
              switch(value) {				
				  case 'PE':
					cstatus='Waiting For PE Manager Approval';
					break;
				  case 'PRE':
					cstatus='Waiting For PRE Manager Approval';
					break;
				  case 'QA':
					cstatus='Waiting For QA Manager Approval';
					break;
				  case 'ENGD':
					cstatus='Waiting For PRE Director Approval';
					break;
				  case 'MFGD':
					cstatus='Waiting For MFG Director Approval';
					break;
				  case 'QAD':
					cstatus='Waiting For QA Director Approval';
					break;
				  case 'SPCN':
					cstatus='Waiting For Sending Customer';
					break;
				  case 'CCOMP':
					cstatus='Pending Customer Replying';
					break;
				  case 'PEE':
					cstatus='Pending Executed PTL';
					break;              
				  default:
				    var dept=value.split('/');
					switch(dept[0]) {				
						  case 'PE':
							cstatus='PE Manager Reject';
							break;
						  case 'PRE':
							cstatus='PRE Manager Reject';
							break;
						  case 'QA':
							cstatus='QA Manager Reject';
							break;
						  case 'ENGD':
							cstatus='PRE Director Reject';
							break;
						  case 'MFGD':
							cstatus='MFG Director Reject';
							break;
						  case 'QAD':
							cstatus='QA Director Reject';
							break;
						  case 'SPCN':
							cstatus='Cancel Sending Customer';
							break;
						  case 'CCOMP':
							cstatus='Customer Reject';
							break;
						  case 'PEE':
							cstatus='PE Cancel Excuted';
							break;  
					}
				}

				return cstatus;
			}

 $(function(){
   $('[data-toggle="tooltip"]').tooltip(); 
   $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
    add_recd=function(){ location.href ="__URL__/add";}
    edit_recd=function(){
	    
	     if ($('#tradeList').bootstrapTable('getSelections').length==0)
	     {
			// alert("请选中一条记录！");

			$('.modal').modal('show');
	     }else{
		   var id=($('#tradeList').bootstrapTable('getSelections')[0].id);
		   location.href ="__URL__/edit/id/"+id;
		 }
	   
	   }//{ alert('getSelections: ' + JSON.stringify($('#tradeList').bootstrapTable('getSelections')))}
	  del_recd=function(){
	    
	     if ($('#tradeList').bootstrapTable('getSelections').length==0)
	     {
			$('.modal').modal('show');
	     }else{
		   var id=($('#tradeList').bootstrapTable('getSelections')[0].id);
		   location.href ="__URL__/del/id/"+id;
		 }
	   
	   }
	  refresh_recd=function(){
	     $('#tradeList').bootstrapTable('refreshOptions', {
               url: '__URL__/getUserRule'
             })	   
	   }
	  export_recd=function(){
		  if ($('#tradeList').bootstrapTable('getSelections').length==0)
			 {
				$('.modal').modal('show');
			 }else{
			   var pcn_no=($('#tradeList').bootstrapTable('getSelections')[0].pcn_no);
			   location.href ="__URL__/export_excel/pcn_no/"+pcn_no;
			 }
	  	  }
      action_pcn=function(pcna){
	        if (pcna!='LOGOUT'){	
                 location.href ="__URL__/mailsetting";
			 }else{
			  //alert('sdfdfsfds');
			 
			 }
	 }
   //1.初始化Table
     var oTable = new TableInit();
     oTable.Init();
    //2.初始化Button的点击事件
        /* var oButtonInit = new ButtonInit();
        oButtonInit.Init(); */
	});


</script>

</html>