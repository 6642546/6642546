$(document).ready(function(){
	$(".login").show();
	prepareGrid();
	var iID=setInterval(function(){if ($('#buildgrid').length>0)
	{
		$('#buildgrid').datagrid('reload');
	} }, 60000);
	$('#dispatch_g').combobox({
				url:'buildability/getgroups.php',
				valueField:'id',
				textField:'text'
			});
	$('#datepicker1').datebox({   
			formatter: function(date){ 
						var y = date.getFullYear();
							var m = date.getMonth()+1;
							var d = date.getDate();
							return y+'-'+(m<10?'0'+m:m)+'-'+(d<10?'0'+d:d);
							//};

			},   
			parser: function(s){ 
				if(s) {
					var a = s.split('-');
					if (a[0] && a[1] && a[2])
					{
						var d = new Date(parseInt(a[0]),parseInt(a[1])-1,parseInt(a[2]));
						return d;
					} else {
						return new Date();
					
					}
					
					}else {
					return new Date();
					}
			},
			onSelect:function(date){
				var y = date.getFullYear();
				var m = date.getMonth()+1;
				var d = date.getDate();
				var fromdate= y+'-'+(m<10?'0'+m:m)+'-'+(d<10?'0'+d:d);
				var todate=$('#datepicker2').datebox("getValue");
				if (fromdate && todate)
				{
					if (fromdate>todate)
					{
						alert('You must specify a valid date or date range.');
						$("#datepicker1").datebox("clear");
					}
				}
			}
		});
	$('#datepicker2').datebox({   
		formatter: function(date){ 
						var y = date.getFullYear();
							var m = date.getMonth()+1;
							var d = date.getDate();
							return y+'-'+(m<10?'0'+m:m)+'-'+(d<10?'0'+d:d);
							//};

			},   
			parser: function(s){ 
				if(s) {
					var a = s.split('-');
					if (a[0] && a[1] && a[2])
					{
						var d = new Date(parseInt(a[0]),parseInt(a[1])-1,parseInt(a[2]));
						return d;
					} else {
						return new Date();
					
					}
					
					}else {
					return new Date();
					}
			},
			onSelect:function(date){
				var y = date.getFullYear();
				var m = date.getMonth()+1;
				var d = date.getDate();
				var todate= y+'-'+(m<10?'0'+m:m)+'-'+(d<10?'0'+d:d);
				var fromdate=$('#datepicker1').datebox("getValue");
				if (fromdate && todate)
				{
					if (fromdate>todate)
					{
						alert('You must specify a valid date or date range.');
						$("#datepicker2").datebox("clear");
					}
				}
			} 
	});

	$("#search_btn_b").bind("click",
		function() {
		doquery();
	}
	);

	$("#reset_btn").bind("click",
		function() {
		clear_criteria();
	}
	);

});

function prepareGrid(){
	$('#buildgrid').datagrid({
				title:'',
				//iconCls:'icon-search',
				width:950,
				height:445,
				nowrap: true,
				striped: true,
				collapsible:false,
				url:'buildability/getlist.php?site='+$("#site_text").html(),
				sortName: 'MANUFACTURING_DUE_DATE',
				pageSize:12,
				pageList:[10,12,20,30,40,50,100],
				sortOrder: 'asc',
				remoteSort: false,
				idField:'id',
				columns:[
			        [{checkbox:true},
					{field:'id',hidden:true},
					{field:'part_number',title:'Part Number',width:77,align:'left',sortable:true},
					{field:'cust_pn',title:'Customer P/N',width:120,align:'left',sortable:true},
					{field:'trun_time',title:'Turn Time',width:68,sortable:true},
					{field:'hc',title:'HC',width:33,sortable:true},
					//{field:'pc',title:'PC',width:35,sortable:true},
					{field:'cust_id',title:'Cust ID',width:70,sortable:true},
					{field:'tlg_wc',title:'TLG WC',width:55,sortable:true},
					{field:'mfg_wc',title:'MFG WC',width:55,sortable:true},
					//{field:'ctl',title:'CTL',width:40,sortable:true},
					{field:'pt',title:'PT',width:80,sortable:true},
					{field:'due_date',title:'Due Date',width:72,sortable:true},
					//{field:'product_tech',title:'Product Tech',width:85,sortable:true},
					{field:'status',title:'Status',width:265}]
				],
				pagination:true,
				rownumbers:false,
				singleSelect:false,
				onLoadSuccess:function(data){
							var pg = $("#buildgrid").datagrid("getPager");
							if(pg)
							{
							if (data.total == 0)
							 {
							   $(pg).pagination({
								 "displayMsg":"No part numbers to display"
							   });
							} else {
								$(pg).pagination({
								 "displayMsg":"Displaying {from} to {to} of {total} items"
							   });
							}
							}
							$("#advanced_search").css("float","right");
				},
				onClickRow: function(rowindex, rowData)
				{
					//procMe(rowData.part_number,rowData.status,rowData.pt,rowData.id);
				},
				onDblClickRow:function(rowIndex, rowData)
				{
					procMe(rowData.part_number,rowData.status,rowData.pt,rowData.id);
				},
				toolbar:[{
					id:'btnadd',
					text:'Create New Buildability',
					iconCls:'icon-add',
					handler:function(){
						if ($("#loggedUserName").html().length==0)
						{
							$(".login").trigger("click");
							return false;
						}
						$("#input_pn").val("");
						$("#iw_xact_color").combobox('setValue',"None");
						$("#iw_warp_spec").combobox('setValue',"Class II");
						$("#iw_surface_cu_spec").val("");
						$('#input_window :checkbox').each(
							function(){
								$(this).attr("checked",false);
							
							}
						
						);
						
						$('#input_window').window('open');
						//$("#input_pn").combobox('clear');
						//getPns();
						


						$('#input_window').find('input:first').focus();
					}
				},{
					id:'btncut',
					text:'Remove Buildability',
					iconCls:'icon-remove',
					handler:function(){
						if ($("#loggedUserName").html().length==0)
						{
							$(".login").trigger("click");
							return false;
						}
						if ($("#userRole").html()!='Admin' && $("#userRole").html()!='PT')
						{
							alert("Only Admin or PT can delete Buildabilities.");
							return false;
						}
						var selected = $('#buildgrid').datagrid('getSelections');
						if (selected.length>0){
						if (confirm('Do you want to delete Buildability for selected items?'))
						{
							for (var i=0;i<selected.length ; i++)
							{
								delete_buildability(selected[i].part_number,selected[i].id);
								var index = $('#buildgrid').datagrid('getRowIndex',selected[i]);
								$('#buildgrid').datagrid('deleteRow',index);
							}
							$('body').unmask();
							alert('Buildability deleted successfully.'); 
						} 
						}
						else{
							alert('Please chick the check box to select one line first.');
							return false;
						}
					}
				},{
						id:'advanced_search',
						text:'Advanced Search',
						//iconCls:'icon-search',
						handler:function(){
							$('#search_window').window('open');
						
					}
				}]
			});
			//do the search.
			//doquery();
};

function procMe(part_number, status,pt,id){
	$('body').mask('Loading Buildability details for ' + part_number + ' ...');
	var _part_number =$.trim(part_number);
		if (part_number)
		{
			window.location.href='index.php?site='+$("#site_text").html()+'&action=buildability&part_number='+_part_number+'&id='+id+'&pt='+pt;
		}
};

function doquery()
{
	var part_number = $("#part_number").attr("value");
	part_number = $.trim(part_number);
	if (part_number.indexOf('-')==3)
	{
		part_number = part_number.replace('-','');	
	}
	var date_from = $("#datepicker1").datebox('getValue');
	var date_to = $("#datepicker2").datebox('getValue');
	var group_name = $("#dispatch_g").combobox('getText');
		$('#buildgrid').datagrid('options').url="buildability/getlist.php?site="+$("#site_text").html();
		var queryParams = $('#buildgrid').datagrid('options').queryParams;
			queryParams.site = $("#site_text").html();
			queryParams.part_number = part_number;
			queryParams.date_from = date_from;
			queryParams.date_to = date_to;
			queryParams.group_name = group_name;
			queryParams.status = $(".grid :radio:checked").val();
		$('#buildgrid').datagrid('reload');   
};

function close_input(){
	$('#input_window').window('close');
	
};

function creat_new(){
	var cur_username = $("#loggedUserName").html();
	var _part_number = $("#input_pn").val();//combobox('getText');
	var iw_xact_color = $("#iw_xact_color").combobox('getText');
	var iw_warp_spec = $("#iw_warp_spec").combobox('getText');
	//var iw_dictated_drills = $("#iw_dictated_drills").combobox('getText');
	//var iw_slot_length = $("#iw_slot_length").combobox('getText');
	var iw_surface_cu_spec = $("#iw_surface_cu_spec").val();

	if (!_part_number)
	{
		alert('Please input the Part Number.');
		return false;
	}
	if (iw_xact_color =='None')
	{
		alert('Please choose the Xact values status color.');
		return false;
	}
	if (iw_warp_spec =='')
	{
		alert('Please choose the Warp Spec.');
		return false;
	}
	//if ($("#iw_overrun_value").attr("checked") && !$("#overrun_value").val())
	//{
	//	alert('Please input the score over run value.');
	//	return false;
	//}
	_part_number = $.trim(_part_number);
	if (_part_number.indexOf('-')==3)
	{
		_part_number = _part_number.replace('-','');	
	}


	// config the input attri here.
	var arrayData = iw_xact_color;
	var arrayTitle = 'Xact values status color';
	arrayData += ','+iw_warp_spec;
	arrayTitle +=','+'Wrap spec';

	arrayData += ','+iw_surface_cu_spec;
	arrayTitle +=','+'Surface Cu spec';

	if ($("#iw_sotlt").attr("checked"))
	{
		arrayData += ',Yes';
		arrayTitle +=','+'Score offset tolerance less than +/- .003 inches';
	} else {
		arrayData += ',No';
		arrayTitle +=','+'Score offset tolerance less than +/- .003 inches';
	}

	if ($("#iw_rswlt").attr("checked"))
	{
		arrayData += ',Yes';
		arrayTitle +=','+'Remaining score web less than .006 inches';
	} else {
		arrayData += ',No';
		arrayTitle +=','+'Remaining score web less than .006 inches';
	}

	if ($("#iw_rwtlt").attr("checked"))
	{
		arrayData += ',Yes';
		arrayTitle +=','+'Remaining web tolerance less than +/- .003 inches';
	} else {
		arrayData += ',No';
		arrayTitle +=','+'Remaining web tolerance less than +/- .003 inches';
	}

	if ($("#iw_setetlt").attr("checked"))
	{
		arrayData += ',Yes';
		arrayTitle +=','+'Score edge to edge tolerance less than +/- .005 inches';
	} else {
		arrayData += ',No';
		arrayTitle +=','+'Score edge to edge tolerance less than +/- .005 inches';
	}

	if ($("#iw_dtsetlt").attr("checked"))
	{
		arrayData += ',Yes';
		arrayTitle +=','+'Datum to score edge tolerance less than +/- .005 inches';
	} else {
		arrayData += ',No';
		arrayTitle +=','+'Datum to score edge tolerance less than +/- .005 inches';
	}
	if ($("#iw_atlt").attr("checked"))
	{
		arrayData += ',Yes';
		arrayTitle +=','+'Angle tolerance less than +/- .5 degrees';
	} else {
		arrayData += ',No';
		arrayTitle +=','+'Angle tolerance less than +/- .5 degrees';
	}

	if ($("#iw_rmttlt").attr("checked"))
	{
		arrayData += ',Yes';
		arrayTitle +=','+'Remaining mill material thickness tolerance less than +/- .007 inches';
	} else {
		arrayData += ',No';
		arrayTitle +=','+'Remaining mill material thickness tolerance less than +/- .007 inches';
	}

	if ($("#iw_betme").attr("checked"))
	{
		arrayData += ',Yes';
		arrayTitle +=','+'Board edge to mill edge tolerance less than +/- .005 inches';
	} else {
		arrayData += ',No';
		arrayTitle +=','+'Board edge to mill edge tolerance less than +/- .005 inches';
	}

	if ($("#iw_zaxis").attr("checked"))
	{
		arrayData += ',Yes';
		arrayTitle +=','+'Z-axis cleanrance to Cu feature less than .007 inches';
	} else {
		arrayData += ',No';
		arrayTitle +=','+'Z-axis cleanrance to Cu feature less than .007 inches';
	}

	if ($("#overrun_value").val()!='')
	{
		arrayData += ','+$("#overrun_value").val();
		arrayTitle +=','+'Enter score overrun value from score calculator';
	} else {
		arrayData += ',0';
		arrayTitle +=','+'Enter score overrun value from score calculator';
	}

	if ($("#iw_sm_smd").attr("checked"))
	{
		arrayData += ',Yes';
		arrayTitle +=','+'Are soldermask SMD annular ring check rules violated';
	} else {
		arrayData += ',No';
		arrayTitle +=','+'Are soldermask SMD annular ring check rules violated';
	}

	if ($("#iw_dictated_drills").attr("checked"))
	{
		arrayData += ',Yes';
		arrayTitle +=','+'Dictated drills';
	} else {
		arrayData += ',No';
		arrayTitle +=','+'Dictated drills';
	}

	if ($("#iw_slot_length").attr("checked"))
	{
		arrayData += ',Yes';
		arrayTitle +=','+'Is the slot length &lt; 2 X bit diameter';
	} else {
		arrayData += ',No';
		arrayTitle +=','+'Is the slot length &lt; 2 X bit diameter';
	}

	if ($("#iw_overlapping").attr("checked"))
	{
		arrayData += ',Yes';
		arrayTitle +=','+'Are there overlapping holes requiring burr removal';
	} else {
		arrayData += ',No';
		arrayTitle +=','+'Are there overlapping holes requiring burr removal';
	}

	if ($("#iw_sm_mm").attr("checked"))
	{
		arrayData += ',Yes';
		arrayTitle +=','+'Multi-layer/multicolor SM or nomen';
	} else {
		arrayData += ',No';
		arrayTitle +=','+'Multi-layer/multicolor SM or nomen';
	}

	$('body').mask("Checking "+_part_number+" in COLT...");
						$.ajax({ 
						type: "POST", 
						url: "buildability/check_pn.php?part_number="+_part_number, 
						data:{site:$("#site_text").html()},
						dataType: "json", 
						success: function(msg) { 
							var Result = msg.success;
							if (Result == false) {
							   $('body').unmask();
							   alert(msg.message);        
							 }
							else 
							if (Result == true) {
								do_creat(cur_username,_part_number,msg.due_date,msg.turn_time,msg.hc,msg.cust_id,msg.tlg_wc,msg.mfg_wc,msg.pt,msg.cust_pn,msg.cust_name,arrayData,arrayTitle);
								return;
							}
						   } 
						});
};


function do_creat(cur_username,_part_number,due_date,turn_time,hc,cust_id,tlg_wc,mfg_wc,pt,cust_pn,cust_name,arrayData,arrayTitle){
	if (confirm('Do you want to create Buildability for '+_part_number+'?'))
		{
					$('body').mask("Creating Buildability for "+_part_number+"...");
						$.ajax({ 
						type: "POST", 
						url: "buildability/create_buildability.php?part_number="+_part_number+'&user_name='+cur_username+'&site='+$("#site_text").html(),
						data:{due_date:due_date,turn_time:turn_time,hc:hc,cust_id:cust_id,tlg_wc:tlg_wc,mfg_wc:mfg_wc,pt:pt,cust_pn:cust_pn,cust_name:cust_name,arrayData:arrayData,arrayTitle:arrayTitle},
						dataType: "json", 
						success: function(msg) { 
							var Result = msg.success;
							if (Result == false) {
							   $('body').unmask();
							   alert(msg.message);        
							 }
							else 
							if (Result == true) {
								close_input();
								$('body').unmask();
								alert(msg.message); 
								$('body').mask("Loading Buildability Details for "+_part_number+"...");
								window.location.href='index.php?site='+$("#site_text").html()+'&action=buildability&part_number='+_part_number+'&id='+msg.id+'&pt='+msg.pt;
								return;
							}
						   } 
						});
				} else 
				$('body').unmask();
};

function delete_buildability(part_number,id){
					$('body').mask("Deleting Buildability for "+part_number+"...");
						$.ajax({ 
						type: "POST", 
						url: 'buildability/delete_buildability.php?part_number='+part_number+'&part_number_id='+id, 
						data:{site:$("#site_text").html()},
						dataType: "json", 
						success: function(msg) { 
							var Result = msg.success;
							if (Result == false) {
							   $('body').unmask();
							   alert(msg.message);        
							 }
							else 
							if (Result == true) {
							}
						   } 
						});

};

function enter_create_new(event){
	if (event.keyCode==13)
	{
		creat_new();
	}
};

function clear_criteria(){
	$("#part_number").val("");
	$("#dispatch_g").combobox("clear");
	$("#datepicker1").datebox("clear");
	$("#datepicker2").datebox("clear");
	$("input[name='status'][value='O']").attr("checked",true); 

};

function getPns(){
		//$('#input_window').mask("Loading part numbers, please wait...");
		$('#input_pn').combobox( {
			//width:150,
			//listWidth:150,
			//listHeight:100,
			url:'buildability/role_data.json',
			valuefield:'id',
			textField:'text',
			onLoadSuccess:function(){
				//$('#input_window').unmask();
			},
			onLoadError:function(){
				//$('#input_window').unmask();
			}
		});
};

/*$("#iw_overrun_value").live("click",
		function() {
			if ($("#iw_overrun_value").attr("checked"))
			{
				$("#overrun_value").css("display","inline");
			} 
			else {
				$("#overrun_value").css("display","none");
			}
			
	}
	);*/

$("#adv_search_btn").live("click",
		function() {
			prepareSearchGrid();
			
	}
);

function prepareSearchGrid(){
	$('#adv_search_grid').datagrid({
				title:'',
				//iconCls:'icon-search',
				width:564,
				height:300,
				nowrap: true,
				striped: true,
				collapsible:false,
				//url:'buildability/getlist.php?site='+$("#site_text").html(),
				sortName: 'part_number',
				pageSize:12,
				pageList:[10,12,20,30,40,50,100],
				sortOrder: 'asc',
				remoteSort: false,
				idField:'id',
				columns:[
			        [{checkbox:true},
					{field:'id',hidden:true},
					{field:'part_number',title:'Part Number',width:77,align:'left',sortable:true},
					{field:'due_date',title:'Due Date',width:72,sortable:true},
					{field:'status',title:'Status',width:265}]
				],
				pagination:true,
				rownumbers:false,
				singleSelect:false,
				onLoadSuccess:function(data){
							var pg = $("#adv_search_grid").datagrid("getPager");
							if(pg)
							{
							if (data.total == 0)
							 {
							   $(pg).pagination({
								 "displayMsg":"No part numbers to display"
							   });
							} else {
								$(pg).pagination({
								 "displayMsg":"Displaying {from} to {to} of {total} items"
							   });
							}
							}
							$("#advanced_search").css("float","right");
				},
				onClickRow: function(rowindex, rowData)
				{
					//procMe(rowData.part_number,rowData.status,rowData.pt,rowData.id);
				},
				onDblClickRow:function(rowIndex, rowData)
				{
					//procMe(rowData.part_number,rowData.status,rowData.pt,rowData.id);
					window.open('index.php?site='+ $("#site_text").html()+'&action=buildability&part_number='+rowData.part_number+'&id='+rowData.id);
				}
			});
			doSearchquery();
};

function doSearchquery()
{
	var keyword = $("#adv_search_input").attr("value");
	keyword = $.trim(keyword);
	
	$('#adv_search_grid').datagrid('options').url="buildability/getsearchlist.php?site="+$("#site_text").html();
		var queryParams = $('#adv_search_grid').datagrid('options').queryParams;
			queryParams.site = $("#site_text").html();
			queryParams.keyword = keyword;
			queryParams.criteria =$("#note").attr("checked");
			queryParams.colt_criteria =$("#colt").attr("checked");
			queryParams.entry_criteria =$("#entry").attr("checked");
			queryParams.colt_feild =$("#colt_feild").find("option:selected").val();  //$("colt_feild").val();
			queryParams.entry_feild =$("#entry_feild").find("option:selected").val();  //$("colt_feild").val(); 
			queryParams.feild_relation =$("#feild_relation").find("option:selected").val();//$("feild_relation").val(); 
			queryParams.feild_relation_1 =$("#feild_relation_1").find("option:selected").val();

		$('#adv_search_grid').datagrid('reload');   
};

function close_search(){
	$('#search_window').window('close');
}