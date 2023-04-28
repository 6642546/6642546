proposer_ds = new Ext.data.Store({
       		 proxy: new Ext.data.HttpProxy({
            	 	url: 'action/get_people.php?query=proposer'
        	 }),
       		 reader: new Ext.data.JsonReader({},[{name: "rec_ftype", type: "string",mapping: 0}])
  	});
me_ds = new Ext.data.Store({
       		 proxy: new Ext.data.HttpProxy({
            	 	url: 'action/get_people.php?query=me'
        	 }),
       		 reader: new Ext.data.JsonReader({},[{name: "rec_ftype", type: "string",mapping: 0}])
  	});
pe_ds = new Ext.data.Store({
       		 proxy: new Ext.data.HttpProxy({
            	 	url: 'action/get_people.php?query=pe'
        	 }),
       		 reader: new Ext.data.JsonReader({},[{name: "rec_ftype", type: "string",mapping: 0}])
  	});
qa_ds = new Ext.data.Store({
       		 proxy: new Ext.data.HttpProxy({
            	 	url: 'action/get_people.php?query=qa'
        	 }),
       		 reader: new Ext.data.JsonReader({},[{name: "rec_ftype", type: "string",mapping: 0}])
  	});
all_ds = new Ext.data.Store({
       		 proxy: new Ext.data.HttpProxy({
            	 	url: 'action/get_people.php?query=all'
        	 }),
       		 reader: new Ext.data.JsonReader({},[{name: "rec_ftype", type: "string",mapping: 0}])
  	});


 function refresh_fun() {
  	    Ext.getCmp('keyword_search_recordno').setValue('');
		var getstore = Ext.getCmp('topic-grid').getStore();
		if (getstore.getCount() > 0) {
	    	getstore.removeAll();
		}
	    getstore.load({params: {start:0, limit: pagesize,jobkey:'',filter:filter}});
  }
  function ecr_filter(filter_txt) {
  	    Ext.getCmp('keyword_search_recordno').setValue('');
		Ext.getCmp('menu_filter').setText(filter_txt);
		var getstore = Ext.getCmp('topic-grid').getStore();

		if (getstore.getCount() > 0) {
	    	getstore.removeAll();
		}
	    getstore.load({params: {start:0, limit: pagesize}});
  }

function search_recordno() {
	var v = Ext.getCmp('keyword_search_recordno').getValue();
	v = v.replace(/(^\s*)|(\s*$)/g, "");

	var re =/^\d+$/;
	if (!re.test(v)) {
		alert("只能输入数字!");
		return;
	}
	filter = 'All';
	Ext.getCmp('menu_filter').setText("All");
	var getstore = Ext.getCmp('topic-grid').getStore();
	getstore.removeAll();
	getstore.load({params: {start:0, limit: pagesize,recordno:v}});
}

function statistics() {
	var labelWidth = 80;
	var abs_width_2 = 120;
	row1_chk = '';
	row2_chk = '';
	row3_chk = '';
	row4_chk = '';
	row5_chk = '';
	row6_chk = '';
	row1_datefrom  = '';
	row1_dateto    = '';
	row2_proposer  = '';
	row3_me        = '';
	row4_pe        = '';
	row5_qa        = '';
	row6_type      = '';
	toolaffaced    = '';

    var statistics_row1_date = new Ext.Panel({
	        layout:"column",
	        width:'100%',
        	frame: true,
        	labelWidth : labelWidth,
        	items:[{xtype:"panel",
        		    layout:"column",
        		    width:'5%',
        		    items:{
						xtype:"checkbox",
						fieldLabel:''
        		}},
        		{xtype:"panel",
        		    layout:"form",
        		    width:'45%',
        		    items:{
						xtype:"datefield",
						format:'Y-m-d',
						width:abs_width_2,
						fieldLabel: "申请日期从",
						id:"statistics_row1_date_from",
						name:"statistics_row1_date_from",
						readonly:false,
						value:''
        		}},{xtype:"panel",
        		    layout:"form",
        		    width:'45%',
        		    items:{
						xtype:"datefield",
						format:'Y-m-d',
						fieldLabel: "到",
						width:abs_width_2,
						id:"statistics_row1_date_to",
						name:"statistics_row1_date_to",
						editable:false,
						value:''
        		}}]
    });

    var statistics_row1 = new Ext.Panel({
	        layout:"form",
	        width:'100%',
        	frame: false,
        	items:[statistics_row1_date]
	});

    var statistics_row2_proposer = new Ext.Panel({
	        layout:"column",
	        width:'100%',
        	frame: true,
        	labelWidth : labelWidth,
        	items:[{xtype:"panel",
        		    layout:"column",
        		    width:'5%',
        		    items:{
						xtype:"checkbox",
						fieldLabel:''
        		}},
        		{xtype:"panel",
        		    layout:"form",
        		    width:'95%',
        		    items:{
						xtype:"combo",
						width:abs_width_2,
						fieldLabel: "申请人",
						id:"statistics_row2_proposer",
						name:"statistics_row2_proposer",
				        valueField:'rec_ftype',
				        displayField:'rec_ftype',
				        lazyRender:true,
				        triggerAction:'all',
				        mode:'remote',
				        editable:true,
						value:'',
						store: proposer_ds
        		}}]
    });

    var statistics_row2 = new Ext.Panel({
	        layout:"form",
	        width:'100%',
        	frame: false,
        	items:[statistics_row2_proposer]
	});

    var statistics_row3_me = new Ext.Panel({
	        layout:"column",
	        width:'100%',
        	frame: true,
        	labelWidth : labelWidth,
        	items:[{xtype:"panel",
        		    layout:"column",
        		    width:'5%',
        		    items:{
						xtype:"checkbox",
						fieldLabel:''
        		}},
        		{xtype:"panel",
        		    layout:"form",
        		    width:'95%',
        		    items:{
						xtype:"combo",
						width:abs_width_2,
						fieldLabel: "ME审批",
						id:"statistics_row3_me",
						name:"statistics_row3_me",
				        valueField:'rec_ftype',
				        displayField:'rec_ftype',
				        lazyRender:true,
				        triggerAction:'all',
				        mode:'remote',
				        editable:true,
						value:'',
						store: me_ds
        		}}]
    });
   var statistics_row3 = new Ext.Panel({
	        layout:"form",
	        width:'100%',
        	frame: false,
        	items:[statistics_row3_me]
	});
     var statistics_all_define = new Ext.Panel({
	        layout:"column",
	        width:'100%',
        	frame: true,
        	labelWidth : labelWidth,
        	items:[{xtype:"panel",
        		    layout:"column",
        		    width:'5%',
        		    items:{
						xtype:"checkbox",
						fieldLabel:''
        		}},
        		{xtype:"panel",
        		    layout:"form",
        		    width:'95%',
        		    items:{
						xtype:"combo",
						width:abs_width_2,
						fieldLabel: "审批人",
						id:"statistics_all",
						name:"statistics_all",
				        valueField:'rec_ftype',
				        displayField:'rec_ftype',
				        lazyRender:true,
				        triggerAction:'all',
				        mode:'remote',
				        editable:true,
						value:'',
						store: all_ds
        		}}]
    });

    var statistics_all = new Ext.Panel({
	        layout:"form",
	        width:'100%',
        	frame: false,
        	items:[statistics_all_define]
	});

    var statistics_row4_pe = new Ext.Panel({
	        layout:"column",
	        width:'100%',
        	frame: true,
        	labelWidth : labelWidth,
        	items:[{xtype:"panel",
        		    layout:"column",
        		    width:'5%',
        		    items:{
						xtype:"checkbox",
						fieldLabel:''
        		}},
        		{xtype:"panel",
        		    layout:"form",
        		    width:'95%',
        		    items:{
						xtype:"combo",
						width:abs_width_2,
						fieldLabel: "PE审批",
						id:"statistics_row4_pe",
						name:"statistics_row4_pe",
				        valueField:'rec_ftype',
				        displayField:'rec_ftype',
				        lazyRender:true,
				        triggerAction:'all',
				        mode:'remote',
				        editable:true,
						value:'',
						store: pe_ds
        		}}]
    });

    var statistics_row4 = new Ext.Panel({
	        layout:"form",
	        width:'100%',
        	frame: false,
        	items:[statistics_row4_pe]
	});

    var statistics_row5_qa = new Ext.Panel({
	        layout:"column",
	        width:'100%',
        	frame: true,
        	labelWidth : labelWidth,
        	items:[{xtype:"panel",
        		    layout:"column",
        		    width:'5%',
        		    items:{
						xtype:"checkbox",
						fieldLabel:''
        		}},
        		{xtype:"panel",
        		    layout:"form",
        		    width:'95%',
        		    items:{
						xtype:"combo",
						width:abs_width_2,
						fieldLabel: "QA审批",
						id:"statistics_row5_qa",
						name:"statistics_row5_qa",
				        valueField:'rec_ftype',
				        displayField:'rec_ftype',
				        lazyRender:true,
				        triggerAction:'all',
				        mode:'remote',
				        editable:true,
						value:'',
						store: qa_ds
        		}}]
    });
    var statistics_row5 = new Ext.Panel({
	        layout:"form",
	        width:'100%',
        	frame: false,
        	items:[statistics_row5_qa]
	});

    var statistics_row6_type = new Ext.Panel({
	        layout:"column",
	        width:'100%',
        	frame: true,
        	labelWidth : labelWidth,
        	items:[{xtype:"panel",
        		    layout:"column",
        		    width:'5%',
        		    items:{
						xtype:"checkbox",
						fieldLabel:''
        		}},
        		{xtype:"panel",
        		    layout:"form",
        		    width:'95%',
        		    items:{
						xtype:"combo",
						width:abs_width_2,
						fieldLabel: "TCN类型",
						id:"statistics_row6_type",
						name:"statistics_row6_type",
				        valueField:'rec_ftype',
				        displayField:'rec_ftype',
				        lazyRender:true,
				        triggerAction:'all',
				        mode:'local',
				        editable:false,
						value:'',
						store: new Ext.data.SimpleStore({
        					fields:["rec_ftype"],
        					data:[['正式'],['临时']]
  						})
        		}}]
    });

    var statistics_row6 = new Ext.Panel({
	        layout:"form",
	        width:'100%',
        	frame: false,
        	items:[statistics_row6_type]
	});




    var statistics_row7_type2 = new Ext.Panel({
	        layout:"form",
	        width:'100%',
        	frame: true,
        	items:[{
            xtype: 'radiogroup',
            id:'toolaffaced_radiogroup',
            fieldLabel: '影响生产工具',
            columns: [100, 100,100],
            vertical: true,
            items: [
                {boxLabel: 'All', name: 'toolaffaced', inputValue: 'All', checked: true},
                {boxLabel: 'Planning', name: 'toolaffaced', inputValue: 'Planning'},
                {boxLabel: 'CAM', name: 'toolaffaced', inputValue:'CAM'}]
        }]
    });


    var statistics_row7 = new Ext.Panel({
	        layout:"form",
	        width:'100%',
        	frame: false,
        	items:[statistics_row7_type2]
	});
	var statisticsform = new Ext.form.FormPanel({
		id: 'statisticsform',
		title: "",
		frame: false,
	//	items: [statistics_row1,statistics_row2,statistics_row3,statistics_row4,statistics_row5,statistics_row6,statistics_row7_type2],
		items: [statistics_row1,statistics_row2,statistics_all],
		width: 596,
		buttons: [{text: "确定",handler:function(e) {
			row1_chk = statistics_row1.findByType("checkbox")[0].getValue();
			row2_chk = statistics_row2.findByType("checkbox")[0].getValue();
			row3_chk = statistics_row3.findByType("checkbox")[0].getValue();
			row3_chk = statistics_all.findByType("checkbox")[0].getValue();
			
			row4_chk = statistics_row4.findByType("checkbox")[0].getValue();
			row5_chk = statistics_row5.findByType("checkbox")[0].getValue();
			row6_chk = statistics_row6.findByType("checkbox")[0].getValue();
        
			if (row1_chk == true) {
				row1_datefrom = statistics_row1_date.findByType("datefield")[0].getValue();
				if (row1_datefrom != '') {
				   row1_datefrom = new Date(row1_datefrom).format('Y-m-d');
				} else {
					alert("日期不能为空!");
					return;
				}
				row1_dateto = statistics_row1_date.findByType("datefield")[1].getValue();
				if (row1_dateto != '') {
				   row1_dateto = new Date(row1_dateto).format('Y-m-d');
				} else {
					alert("日期不能为空!");
					return;
				}
			}
			if (row2_chk == true) {
				row2_proposer = statistics_row2.findByType("combo")[0].getRawValue();
				if (row2_proposer == '') {
					alert("申请人不能为空!");
					return;
				}
			}
			/*if (row3_chk == true) {
				row3_me = statistics_row3.findByType("combo")[0].getRawValue();
				if (row3_me == '') {
					alert("ME审批不能为空!");
					return;
				}
			}*/
			if (row3_chk == true) {
				row3_me = statistics_all.findByType("combo")[0].getRawValue();
				if (row3_me == '') {
					alert("审批人不能为空!");
					return;
				}
			}
			if (row4_chk == true) {
				row4_pe = statistics_row4.findByType("combo")[0].getRawValue();
				if (row4_pe == '') {
					alert("PE审批不能为空!");
					return;
				}
			}
			if (row5_chk == true) {
				row5_qa = statistics_row5.findByType("combo")[0].getRawValue();
				if (row5_qa == '') {
					alert("QA审批不能为空!");
					return;
				}
			}
			if (row6_chk == true) {
				row6_type = statistics_row6.findByType("combo")[0].getRawValue();
				if (row6_type == '') {
					alert("TCN类型不能为空!");
					return;
				}
			}
			//toolaffaced = Ext.getCmp('toolaffaced_radiogroup').getValue();
			Ext.getCmp('statisticswin').close();
			filter = 'statistics';
			var getstore = Ext.getCmp('topic-grid').getStore();
		    getstore.removeAll();
		    row1_chk = String(row1_chk);
		    row2_chk = String(row2_chk);
		    row3_chk = String(row3_chk);
		    row4_chk = String(row4_chk);
		    row5_chk = String(row5_chk);
		    row6_chk = String(row6_chk);
		   getstore.load({params: {start:0, limit: pagesize,row1_chk:row1_chk,row1_datefrom:row1_datefrom,row1_dateto:row1_dateto,row2_chk:row2_chk,row2_proposer:row2_proposer,row3_chk:row3_chk,row3_me:row3_me,row4_chk:row4_chk,row4_pe:row4_pe,row5_chk:row5_chk}});
		 //   getstore.load({params: {start:0, limit: pagesize,row1_chk:row1_chk,row1_datefrom:row1_datefrom,row1_dateto:row1_dateto,row2_chk:row2_chk,row2_proposer:row2_proposer,row3_chk:row3_chk,row3_me:row3_me,row4_chk:row4_chk,row4_pe:row4_pe,row5_chk:row5_chk,row5_qa:row5_qa,row6_chk:row6_chk,row6_type:row6_type,toolaffaced:toolaffaced}});
		}},{text: "退出",handler: function() {
				Ext.getCmp('statisticswin').close();
			  }
		}]
	});
    var statisticswin = new Ext.Window({
    	id:'statisticswin',
    	name:'statisticswin',
		width:610,
		height:200,
		title:"统计查找",
		items:[statisticsform],
		resizable:false,
		draggable:true,
		closable:true
	}).show();
}


  function check_approve_status(records,who) {
	var sel_record_id 				= records[0].get("id");
  	var sel_record_request_form_author		= records[0].get("request_form_author");
	var sel_record_me_approve		= records[0].get("me_approve")==null?'':records[0].get("me_approve");	
	var sel_record_me_approve_time		= records[0].get("me_approve_time");
	var sel_record_me_approve_director		= records[0].get("me_approve_director")==null?'':records[0].get("me_approve_director");
	var sel_record_me_approve_director_date		= records[0].get("me_approve_director_date");

	var sel_record_pe_approve		= records[0].get("pe_approve")==null?'':records[0].get("pe_approve");
	var sel_record_pe_approve_time		= records[0].get("pe_approve_time");
    var sel_record_pe_approve_manager		= records[0].get("pe_approve_manager")==null?'':records[0].get("pe_approve_manager");
	var sel_record_pe_approve_manager_time		= records[0].get("pe_approve_manager_time");

 //PROD:
	var sel_record_approve_Prod		= records[0].get("approve_Prod")==null?'':records[0].get("approve_Prod");
	var sel_record_approve_Prod_time		= records[0].get("approve_Prod_time");
    var sel_record_approve_Prod_manager		= records[0].get("approve_Prod_manager")==null?'':records[0].get("approve_Prod_manager");
	var sel_record_approve_Prod_manager_time		= records[0].get("approve_Prod_manager_time");
//PPC:

	var sel_record_approve_PPC	= records[0].get("approve_PPC")==null?'': records[0].get("approve_PPC");
	var sel_record_approve_PPC_time		= records[0].get("approve_PPC_time");
    var sel_record_approve_PPC_manager		= records[0].get("approve_PPC_manager")==null?'':records[0].get("approve_PPC_manager");
	var sel_record_approve_PPC_manager_time		= records[0].get("approve_PPC_manager_time");	
	
//QA:
  	var sel_record_qa_approve		=records[0].get("qa_approve")==null?'':records[0].get("qa_approve");
	var sel_record_qa_approve_time		= records[0].get("qa_approve_time");
    var sel_record_qa_approve_manager		= records[0].get("qa_approve_manager")==null?'':records[0].get("qa_approve_manager");
	var sel_record_qa_approve_manager_time		= records[0].get("qa_approve_manager_time");	
 //制造总临监认可(当修改会增加成本时）
    var sel_record_approve_director_production	= records[0].get("approve_director_production")==null?'':records[0].get("approve_director_production");
    var sel_record_approve_director_production_time	= records[0].get("approve_director_production_time");
     var tool_cost = records[0].get("tooling_cost");
    //  var old_board_other= records[0].get("old_board_other");
    //  alert(records.data.pe_approve);
	var sel_record_pe_operator		=records[0].get("pe_operator")==null?'':records[0].get("pe_operator");
	var sel_record_pe_operation_datetime		= records[0].get("pe_operation_datetime");
	var sel_record_qae_operator		= records[0].get("qae_operator")==null?'':records[0].get("qae_operator");
	var sel_record_qae_operation_datetime		= records[0].get("qae_operation_datetime");
	var sel_record_follow		= records[0].get("follow_request");
	var sel_record_obsolete		= records[0].get("obsolete");	
	alert("dfsfdsfdsfdsfdsdfsdfsfds");
	if (who == 'modify') {
		if (sel_record_me_approve == '') {
			if (sel_record_request_form_author.toUpperCase() != usr_username.toUpperCase()) {
				//alert("只有用户"+sel_record_request_form_author+"才能修改");
				return false;
			} else {
			  return true;
			}
		} else {
			//alert("已经Approve,不能修改");
			return false;
		}
	}
	if (who == 'me') {
		if (sel_record_me_approve == '') {
			return true;
		} else {
			//alert("已过ME Approve");
			return false;
		}
	}
	if (who == 'me_director') {
		if (sel_record_me_approve != '' && sel_record_me_approve_director==''&& sel_record_obsolete!=1) {
			return true;
		} else {
			//alert("已过ME Approve");
			return false;
		}
	}	
	if (who == 'pe') {
		if (sel_record_me_approve != '' && sel_record_me_approve_director!=''&& sel_record_pe_approve==''&& sel_record_obsolete!=1) {
			return true;
		} else {
			return false;
		}
	}
   if (who == 'pe_manager') {
	  if (sel_record_me_approve != '' && sel_record_me_approve_director!=''&& sel_record_pe_approve!=''&&sel_record_pe_approve_manager==''&& sel_record_obsolete!=1 ) {
			return true;
		} else {
			return false;
		}
	}
   if (who == 'prod') {
		if (sel_record_me_approve != '' && sel_record_me_approve_director!=''&& sel_record_pe_approve!=''&&sel_record_pe_approve_manager!=''&&sel_record_approve_Prod==''&& sel_record_obsolete!=1) {
			return true;
		} else {
			return false;
		}
	}
   if (who == 'prod_manager') {
	  if (sel_record_me_approve != '' && sel_record_me_approve_director!=''&& sel_record_pe_approve!=''&&sel_record_pe_approve_manager!=''&&sel_record_approve_Prod!=''&&sel_record_approve_Prod_manager==''&& sel_record_obsolete!=1) {
			return true;
		} else {
			return false;
		}
	}
   if (who == 'prod_director') {
	   
	  if (sel_record_me_approve != '' && sel_record_me_approve_director!=''&& sel_record_pe_approve!=''&&sel_record_pe_approve_manager!=''&&sel_record_approve_Prod!=''&&sel_record_approve_Prod_manager!=''&& sel_record_obsolete!=1) {
			if (tool_cost==1)
			{ 
				true;
			}else{
			return false;
			}
		} 
	 }
  if (who == 'ppc') {
		if (tool_cost==1)
		{
		   if (sel_record_me_approve != '' && sel_record_me_approve_director!=''&& sel_record_pe_approve!=''&&sel_record_pe_approve_manager!=''&&sel_record_approve_Prod!=''&&sel_record_approve_Prod_manager!=''&&sel_record_approve_director_production!=''&&sel_record_approve_PPC==''&& sel_record_obsolete!=1) {
			   return true;
		     } else {
			 return false;
		    }

		}else{
	    	if (sel_record_me_approve != '' && sel_record_me_approve_director!=''&& sel_record_pe_approve!=''&&sel_record_pe_approve_manager!=''&&sel_record_approve_Prod!=''&&sel_record_approve_Prod_manager!=''&&sel_record_approve_PPC==''&& sel_record_obsolete!=1) {
			   return true;
		     } else {
			 return false;
		    }

		}
  }
   if (who == 'ppc_manager') {
	  if  (sel_record_me_approve !=''&&sel_record_me_approve_director!=''&&sel_record_pe_approve!=''&&sel_record_pe_approve_manager!=''&&sel_record_approve_Prod!=''&&sel_record_approve_PPC!=''&& sel_record_approve_PPC_manager==''&& sel_record_obsolete!=1) {
			return true;
		} else {
			return false;
		}
	 }
  if (who == 'qa') {
		if (sel_record_me_approve !=''&&sel_record_me_approve_director!=''&&sel_record_pe_approve!=''&&sel_record_pe_approve_manager!=''&&sel_record_approve_Prod!=''&&sel_record_approve_PPC!=''&& sel_record_approve_PPC_manager!=''&&sel_record_qa_approve==''&& sel_record_obsolete!=1) {
			return true;
		} else {
			return false;
		}
	}
  if (who == 'qa_manager') {
	  if  (sel_record_me_approve !=''&&sel_record_me_approve_director!=''&&sel_record_pe_approve!=''&&sel_record_pe_approve_manager!=''&&sel_record_approve_Prod!=''&&sel_record_approve_PPC!=''&& sel_record_approve_PPC_manager!=''&&sel_record_qa_approve!=''&&sel_record_qa_approve_manager==''&& sel_record_obsolete!=1) {
			return true;
		} else {
			return false;
		}
	 }
 if (who == 'pe_operation') {
	  if  (sel_record_me_approve !=''&&sel_record_me_approve_director!=''&&sel_record_pe_approve!=''&&sel_record_pe_approve_manager!=''&&sel_record_approve_Prod!=''&&sel_record_approve_PPC!=''&& sel_record_approve_PPC_manager!=''&&sel_record_qa_approve!=''&&sel_record_qa_approve_manager==''&&sel_record_pe_operator == ''&& sel_record_obsolete!=1) {
			return true;
		} else {
			return false;
		}
	}
  if (who == 'qae_operation') {
	  if  (sel_record_me_approve !=''&&sel_record_me_approve_director!=''&&sel_record_pe_approve!=''&&sel_record_pe_approve_manager!=''&&sel_record_approve_Prod!=''&&sel_record_approve_PPC!=''&& sel_record_approve_PPC_manager!=''&&sel_record_qa_approve!=''&&sel_record_qa_approve_manager==''&&sel_record_pe_operator != ''&&sel_record_obsolete != '1'&&sel_record_qae_operator == ''&& sel_record_obsolete!=1) {
			return true;
		} else {
			return false;
		}
	}
 }
  function addjob(tab_id,tab_iframe) {
  	y_pn_mrp_pn_arr = '';
    var jobliststore = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
            url: 'action_inplan/oracle_config.php'
        }),

        reader: new Ext.data.JsonReader({
            root: 'results',
            totalProperty: 'total',
            id: 'id'
        }, [{name: 'tooling_no', mapping:'tooling_no'},
        {id:"mrp_no",name: 'mrp_no', mapping: 'mrp_no'}
        ])

    });
            var cm = new Ext.grid.ColumnModel([{
            	id:"job_list",
                header: "Part Number",
                dataIndex: 'tooling_no',
                width: 150,
                defaultSortable: true
            },{
            	id:"mrp_list",
                header: "Rev",
                dataIndex: 'mrp_no',
                width: 150,
                defaultSortable: true
            }]);

			var pageToolbar = new Ext.PagingToolbar({
				store: jobliststore,
				pageSize: 20,
				displayInfo: true,
				displayMsg: "disply from {0} To {1},Total:{2}",
				emptyMsg: "<span style='color:red;font-style:italic;'>No data is loaded!</span>"
			});

            var grid = new Ext.grid.GridPanel({
                width: 380,
                height: 400,
                id:'job_list_grid',
                title:'JOB list',
                bbar: pageToolbar,
                store: jobliststore,
                cm: cm,
                autoExpandColumn: "job_list",
                sm: new Ext.grid.RowSelectionModel({selectRow:Ext.emptyFn}),
                listeners:{
					"rowclick" : function(grid,rowIndex,e) {
						var select_job = grid.getStore().getAt(rowIndex).data.tooling_no;
						paneljob_list.findById('filter_job').setValue(select_job);
                     },
                     "rowdblclick" : function(grid,rowIndex,e) {
                     	var select_job = grid.getStore().getAt(rowIndex).data.tooling_no;
						var select_rev = grid.getStore().getAt(rowIndex).data.mrp_no;
                     	if (select_job != '') {
	                     		//var str = 'requestform.php' + "?tooling_no=" + select_job + "&login_cusername=" + usr_cusername + "&login_usr_work_id=" + usr_work_id + "&login_userrole=" + usr_dept+"&tab_iframe="+tab_iframe;
                     			var str = 'requestform.php' + "?tooling_no=" + select_job +"&cp_rev=" +select_rev +"&login_cusername=" + usr_username + "&login_usr_work_id=" + usr_work_id +"&tab_iframe="+tab_iframe;
	                     		document.getElementById(tab_iframe).src=str;
	                     		grid.ownerCt.ownerCt.close();
                     	}
                     }
                }
            });

            jobliststore.load({params: {start:0, limit: 20,jobkey:''}});

    function search_inplan_job(key) {
           var key = key.replace(/^\s*/,"").replace(/\s*$/,"");
           var key = key.replace(/^\*/,"").replace(/\*$/,"");
           Ext.getCmp('filter_job').setValue(key);
           jobliststore.removeAll();
           jobliststore.load({params: {start:0, limit: 20, jobkey:key}});
    }
	var paneljob_list = new Ext.Panel({
        layout:"column",
        items:[{xtype:"panel",
        		columnWidth:.65,
        		layout:"form",
        		items:{
        	        xtype:"textfield",
					fieldLabel: "JOB",
					id:"filter_job",
					name: "filter_job",
					allowBlank: false,
					labelWidth:50,
					width:"100%",
					enableKeyEvents: true,
					listeners:{
						specialKey :function(field,e){
                   			 if (e.getKey() == Ext.EventObject.ENTER) {
                       			var key = field.getValue().toUpperCase();
                       			search_inplan_job(key);
                   			 }
                		}
					}
       		    }},

        	  {xtype:"panel",
        	   columnWidth:.35,
        	   bodyStyle:"padding-left:30px",
        	   items:{
        	        xtype:"button",
					id:"filter_job_go",
					name: "filter_job_go",
					text:'Go',
					disabled:true,
					listeners:{
					   "click": function() {
					   	        var add_job_value = Ext.getCmp('filter_job').getValue()
					   	        if (add_job_value != '') {
					   	        	add_job(add_job_value);
					   	        	Ext.getCmp('getjobs').close();
					   	        }
					   }
					}
        	   }
        	   }]
	});
	var getjobsform = new Ext.form.FormPanel({
		id: 'getjobsform',
		title: "Get JOB",
		frame: true,
		items: [paneljob_list,grid],
		width: 500
	});
    var getjobs = new Ext.Window({
    	id:'getjobs',
    	name:'getjobs',
		width:410,
		height:480,
		title:"",
		items:getjobsform,
		resizable:false,
		draggable:true,
		closable:true
	}).show();
   }
function init_tab_button() {
	Ext.getCmp('tab_me_approve').disable();
	Ext.getCmp('tab_me_director_approve').disable();
	Ext.getCmp('tab_pe_approve').disable();
    Ext.getCmp('tab_pe_approve_manager').disable();
	Ext.getCmp('tab_prod_approve').disable();
	Ext.getCmp('tab_prod_approve_manager').disable();
	Ext.getCmp('tab_prod_approve_director').disable();
	Ext.getCmp('tab_ppc_approve').disable();
	Ext.getCmp('tab_ppc_approve_manager').disable();
	Ext.getCmp('tab_qa_approve_manager').disable();
	Ext.getCmp('tab_qa_approve').disable();
//	Ext.getCmp('tab_pe_operation').disable();
//	Ext.getCmp('tab_qae_operation').disable();
	Ext.getCmp('tab_tcn_modify').hide();
	Ext.getCmp('tab').disable();
}


MainPanel = function(){
    	cur_row_no = '';
        cur_tooling_no = '';
    var thisclass = this;
    this.preview = new Ext.Panel({
        id: 'preview',
        region: 'south',
        cls:'preview',
                autoScroll:false,
                border:true,
                width:'100%',
    			height:'100%',

        //listeners: FeedViewer.LinkInterceptor,
        tbar: [{
            id:'tab_me_approve',
            text: 'PRE经理审批',
          //  iconCls: 'new-tab',
            disabled:true,
            scope: this,
            handler : function(){
		        Ext.Ajax.request({
					url: 'action/chk_status.php',
					params: {id:sel_id,which:'me_approve'},
					method: "post",
					success: function(response){
						var json = Ext.util.JSON.decode(response.responseText);
					    if (json.result != '') {
					    	alert("此记录已过 PRE经理审批,请刷新你的页面");
					    } else {
					    	thisclass.openTab_me_approve(sel_id);
					    }
					}
			    });
            }
        },{
            text: '->',
         //   iconCls: 'new-tab',
            scope: this
        },{
            id:'tab_me_director_approve',
            text: 'PRE总监审批',
       //     iconCls: 'new-tab',
            disabled:true,
            scope: this,
            handler : function(){
		        Ext.Ajax.request({
					url: 'action/chk_status.php',
					params: {id:sel_id,which:'me_approve_director'},
					method: "post",
					success: function(response){
						var json = Ext.util.JSON.decode(response.responseText);
					    if (json.result != '') {
					    	alert("此记录已过 PRE总监审批,请刷新你的页面");
					    } else {
					    	thisclass.openTab_me_approve_director(sel_id);
					    }
					}
			    });
            }
        },{
            text: '->',
          //  iconCls: 'new-tab',
            scope: this
        },{
            id:'tab_pe_approve',
            text: 'PE审批',
         //   iconCls: 'new-tab',
            disabled:true,
            scope: this,
            handler : function(e){
		        Ext.Ajax.request({
					url: 'action/chk_status.php',
					params: {id:sel_id,which:'pe_approve'},
					method: "post",
					success: function(response){
						var json = Ext.util.JSON.decode(response.responseText);
					    if (json.result != '') {
					    	alert("此记录已过 PE审批,请刷新你的页面");
					    } else {
					    	thisclass.openTab_pe_approve(sel_id);
					    }
					}
			    });
            }
        },{
            text: '->',
         //   iconCls: 'new-tab',
            scope: this
        },{
            id:'tab_pe_approve_manager',
            text: 'PE经理审批',
          //  iconCls: 'new-tab',
            disabled:true,
            scope: this,
            handler : function(e){
		        Ext.Ajax.request({
					url: 'action/chk_status.php',
					params: {id:sel_id,which:'pe_manager'},
					method: "post",
					success: function(response){
						var json = Ext.util.JSON.decode(response.responseText);
					    if (json.result != '') {
					    	alert("此记录已过 PE经理审批,请刷新你的页面");
					    } else {
					    	thisclass.openTab_pe_approve_manager(sel_id);
					    }
					}
			    });
            }
        },{
            text: '->',
         //   iconCls: 'new-tab',
            scope: this
        },{
            id:'tab_prod_approve',
            text: 'PROD 审批',
          //  iconCls: 'new-tab',
            disabled:true,
            scope: this,
            handler : function(e){
		        Ext.Ajax.request({
					url: 'action/chk_status.php',
					params: {id:sel_id,which:'prod'},
					method: "post",
					success: function(response){
						var json = Ext.util.JSON.decode(response.responseText);
					    if (json.result != '') {
					    	alert("此记录已过 PROD审批,请刷新你的页面");
					    } else {
					    	thisclass.openTab_prod_approve(sel_id);
					    }
					}
			    });
            }
        },{
            text: '->',
         //   iconCls: 'new-tab',
            scope: this
        },{
            id:'tab_prod_approve_manager',
            text: 'PROD经理审批',
           // iconCls: 'new-tab',
            disabled:true,
            scope: this,
            handler : function(e){
		        Ext.Ajax.request({
					url: 'action/chk_status.php',
					params: {id:sel_id,which:'prod_manager'},
					method: "post",
					success: function(response){
						var json = Ext.util.JSON.decode(response.responseText);
					    if (json.result != '') {
					    	alert("此记录已过 PROD经理已审批,请刷新你的页面");
					    } else {
					    	thisclass.openTab_prod_approve_manager(sel_id);
					    }
					}
			    });
            }
        },{
            text: '->',
         //   iconCls: 'new-tab',
            scope: this
        },{
            id:'tab_prod_approve_director',
            text: 'PROD总监审批',
           // iconCls: 'new-tab',
		
            disabled:true,
            scope: this,
            handler : function(e){
		        Ext.Ajax.request({
					url: 'action/chk_status.php',
					params: {id:sel_id,which:'prod_director'},
					method: "post",
					success: function(response){
						var json = Ext.util.JSON.decode(response.responseText);
					    if (json.result != '') {
					    	alert("此记录已过 PROD总监已审批,请刷新你的页面");
					    } else {
					    	thisclass.openTab_prod_approve_director(sel_id);
					    }
					}
			    });
            }
        },/*{
            text: '->',
         //   iconCls: 'new-tab',
            scope: this
        },{
            id:'tab_ppc_approve',
            text: 'PPC审批',
          //  iconCls: 'new-tab',
            disabled:true,
            scope: this,
            handler : function(e){
		        Ext.Ajax.request({
					url: 'action/chk_status.php',
					params: {id:sel_id,which:'ppc'},
					method: "post",
					success: function(response){
						var json = Ext.util.JSON.decode(response.responseText);
					    if (json.result != '') {
					    	alert("此记录已过 PPC已审批,请刷新你的页面");
					    } else {
					    	thisclass.openTab_ppc_approve(sel_id);
					    }
					}
			    });
            }
        },{
            text: '->',
          //  iconCls: 'new-tab',
            scope: this
        },{
            id:'tab_ppc_approve_manager',
            text: 'PPC经理审批',
         //   iconCls: 'new-tab',
            disabled:true,
            scope: this,
            handler : function(e){
		        Ext.Ajax.request({
					url: 'action/chk_status.php',
					params: {id:sel_id,which:'ppc_manager'},
					method: "post",
					success: function(response){
						var json = Ext.util.JSON.decode(response.responseText);
					    if (json.result != '') {
					    	alert("此记录已过 PPC经理已审批,请刷新你的页面");
					    } else {
					    	thisclass.openTab_ppc_approve_manager(sel_id);
					    }
					}
			    });
            }
        },{
            text: '->',
          //  iconCls: 'new-tab',
            scope: this
        },{
            id:'tab_qa_approve',
            text: 'QA审批',
         //   iconCls: 'new-tab',
            disabled:true,
            scope: this,
            handler : function(){
		        Ext.Ajax.request({
					url: 'action/chk_status.php',
					params: {id:sel_id,which:'qa_approve'},
					method: "post",
					success: function(response){
						var json = Ext.util.JSON.decode(response.responseText);
					    if (json.result != '') {
					    	alert("此记录已过 QA审批,请刷新你的页面");
					    } else {
					    	thisclass.openTab_qa_approve(sel_id);
					    }
					}
			    });
            }
        },{
            text: '->',
          //  iconCls: 'new-tab',
            scope: this
        },{
            id:'tab_qa_approve_manager',
            text: 'QA经理审批',
        //    iconCls: 'new-tab',
            disabled:true,
            scope: this,
            handler : function(){
		        Ext.Ajax.request({
					url: 'action/chk_status.php',
					params: {id:sel_id,which:'qa_manager'},
					method: "post",
					success: function(response){
						var json = Ext.util.JSON.decode(response.responseText);
					    if (json.result != '') {
					    	alert("此记录已过QA经理审批,请刷新你的页面");
					    } else {
					    	thisclass.openTab_qa_approve_manager(sel_id);
					    }
					}
			    });
            }
        },/*{
            text: '->',
         //   iconCls: 'new-tab',
            scope: this
        },{
            id:'tab_pe_operation',
            text: 'PE处理',
         //   iconCls: 'new-tab',
            disabled:true,
            scope: this,
            handler : function(){
		        Ext.Ajax.request({
					url: 'action/chk_status.php',
					params: {id:sel_id,which:'pe_operator'},
					method: "post",
					success: function(response){
						var json = Ext.util.JSON.decode(response.responseText);
					    if (json.result != '') {
					    	alert("此记录已过 PE处理,请刷新你的页面");
					    } else {
					    	update_record(sel_id,cur_tooling_no,cur_mrp_no,'pe_operation','待PE处理');
					    }
					}
			    });
            }
        },{
            text: '->',
         //   iconCls: 'new-tab',
            scope: this
        },{
            id:'tab_qae_operation',
            text: 'QAE处理',
         //   iconCls: 'new-tab',
            disabled:true,
            scope: this,
            handler : function(){
		        Ext.Ajax.request({
					url: 'action/chk_status.php',
					params: {id:sel_id,which:'qae_operator'},
					method: "post",
					success: function(response){
						var json = Ext.util.JSON.decode(response.responseText);
					    if (json.result != '') {
					    	alert("此记录已过 QAE处理,请刷新你的页面");
					    } else {
					    	update_record(sel_id,cur_tooling_no,cur_mrp_no,'qae_operation','待QAE处理');
					    }
					}
			    });
            }
        },*/
      /*  new Ext.Toolbar.Fill(),
        {
            id:'tab_tcn_request',
            text: 'TCN申请',
            iconCls: 'new-tab',
            disabled:true,
            handler : this.openTab_request,
            scope: this
        },
        {
            id:'tab_temp_tcn_request',
            text: '临时TCN申请',
            iconCls: 'new-tab',
            disabled:true,
            handler : this.openTab_request_temp,
            scope: this
        },
        {
            id:'tab_tcn_modify',
            text: 'TCN修改',
            iconCls: 'new-tab',
            disabled:false,
            hidden:true,
            handler : function(){
				this.openTab_modify_request(sel_id);
            },
            scope: this
        },
        '-',
        {
            id:'tab',
            text: '查看(View)',
            iconCls: 'new-tab',
            disabled:true,
            handler : this.openTab,
            scope: this
        }/*,
        '-',
        {
            id:'win',
            text: '关闭(Close)',
            iconCls: 'new-win',
            disabled:true,
            scope: this,
            handler : function(){
                this.preview.ownerCt.hide();
                this.preview.ownerCt.ownerCt.doLayout();
            }
        }*/],
        
        clear: function(){
            this.body.update('');
            var items = this.topToolbar.items;
            items.get('tab').disable();
            //items.get('win').disable();
        },
		/* listeners : {
           'render' : function(){
         var threeTbar=new Ext.Toolbar({
    	  items:[
		     {
            id:'tab_ppc_approve',
            text: 'PPC审批',
          //  iconCls: 'new-tab',
            disabled:true,
            scope: thisclass,
            handler : function(e){
		        Ext.Ajax.request({
					url: 'action/chk_status.php',
					params: {id:sel_id,which:'ppc'},
					method: "post",
					success: function(response){
						var json = Ext.util.JSON.decode(response.responseText);
					    if (json.result != '') {
					    	alert("此记录已过 PPC已审批,请刷新你的页面");
					    } else {
					    	thisclass.openTab_ppc_approve(sel_id);
					    }
					}
			    });
            }
        },{
            text: '->',
          //  iconCls: 'new-tab',
            scope: thisclass
        },{
            id:'tab_ppc_approve_manager',
            text: 'PPC经理审批',
         //   iconCls: 'new-tab',
            disabled:true,
            scope: thisclass,
            handler : function(e){
		        Ext.Ajax.request({
					url: 'action/chk_status.php',
					params: {id:sel_id,which:'ppc_manager'},
					method: "post",
					success: function(response){
						var json = Ext.util.JSON.decode(response.responseText);
					    if (json.result != '') {
					    	alert("此记录已过 PPC经理已审批,请刷新你的页面");
					    } else {
					    	thisclass.openTab_ppc_approve_manager(sel_id);
					    }
					}
			    });
            }
        },{
            text: '->',
          //  iconCls: 'new-tab',
            scope: thisclass
        },{
            id:'tab_qa_approve',
            text: 'QA审批',
         //   iconCls: 'new-tab',
            disabled:true,
            scope: thisclass,
            handler : function(){
		        Ext.Ajax.request({
					url: 'action/chk_status.php',
					params: {id:sel_id,which:'qa_approve'},
					method: "post",
					success: function(response){
						var json = Ext.util.JSON.decode(response.responseText);
					    if (json.result != '') {
					    	alert("此记录已过 QA审批,请刷新你的页面");
					    } else {
					    	thisclass.openTab_qa_approve(sel_id);
					    }
					}
			    });
            }
        },{
            text: '->',
          //  iconCls: 'new-tab',
            scope: thisclass
        },{
            id:'tab_qa_approve_manager',
            text: 'QA经理审批',
        //    iconCls: 'new-tab',
            disabled:true,
            scope: thisclass,
            handler : function(){
		        Ext.Ajax.request({
					url: 'action/chk_status.php',
					params: {id:sel_id,which:'qa_manager'},
					method: "post",
					success: function(response){
						var json = Ext.util.JSON.decode(response.responseText);
					    if (json.result != '') {
					    	alert("此记录已过QA经理审批,请刷新你的页面");
					    } else {
					    	thisclass.openTab_qa_approve_manager(sel_id);
					    }
					}
			    });
            }
        },

       {xtype:"tbfill"}, //后面的tools显示在右边
	   

	   {
            id:'tab_tcn_request',
            text: 'TCN申请',
            iconCls: 'new-tab',
            disabled:true,
            handler : thisclass.openTab_request,
			// handler : openTab_request,
			
            scope: thisclass
        },
        {
            id:'tab_temp_tcn_request',
            text: '临时TCN申请',
            iconCls: 'new-tab',
            disabled:true,
            handler : thisclass.openTab_request_temp,
            scope: thisclass
        },
        {
            id:'tab_tcn_modify',
            text: 'TCN修改',
            iconCls: 'new-tab',
            disabled:false,
            hidden:true,
            handler : function(){
				thisclass.openTab_modify_request(sel_id);
            },
            scope: thisclass
        },
        '-',
        {
            id:'tab',
            text: '查看(View)',
            iconCls: 'new-tab',
            disabled:true,
            handler : thisclass.openTab,
		  /* handler : function(){
				alert("dsfdfsfdsdfs");
            },
            scope: thisclass
        }

	   ]
     });
           // oneTbar.render(this.tbar); //add one tbar
          //  twoTbar.render(this.tbar); //add two tbar
            threeTbar.render(this.tbar); //add three tbar
           }
         } */
		 
    });

	this.filtermenu = new Ext.menu.Menu({
		shadow: 'frame',
		items: [{text: "待PRE经理审批(wait PRE Manager approve )",
				cls: "x-btn-text-icon",
				id: "ecr_filter_pre_me",
				disabled:false,
            	handler : function(e){
					var filter_txt = "待ME审批";
					filter = "pre_me_approve";
					ecr_filter(filter_txt);
            	},
            	scope: this
            },{text: "待PRE总监审批(wait PRE Director approve)",
				cls: "x-btn-text-icon",
				id: "ecr_filter_pre_me_director",
				disabled:false,
            	handler : function(e){
					var filter_txt = "待ME总监审批";
					filter = "pre_me_approve_director";
					ecr_filter(filter_txt);
            	},
            	scope: this
			},{text: "待PE审批(wait PE approve)",
				cls: "x-btn-text-icon",
				id: "ecr_filter_pre_pe",
				disabled:false,
            	handler : function(e){
					var filter_txt = "待PE审批";
					filter = "pre_pe_approve";
					ecr_filter(filter_txt);
            	},
            	scope: this
			},{text: "待PE经理审批(wait PE Manager approve)",
				cls: "x-btn-text-icon",
				id: "ecr_filter_pre_pe_manager",
				disabled:false,
            	handler : function(e){
					var filter_txt = "待PE经理审批";
					filter = "pre_pe_approve_manager";
					ecr_filter(filter_txt);
            	},
            	scope: this
			},{text: "待PROD审批(wait PROD approve)",
				cls: "x-btn-text-icon",
				id: "ecr_filter_pre_prod",
				disabled:false,
            	handler : function(e){
					var filter_txt = "待生产部审批";
					filter = "pre_prod_approve";
					ecr_filter(filter_txt);
            	},
            	scope: this
			},{text: "待PROD经理审批(wait PROD Manager approve)",
				cls: "x-btn-text-icon",
				id: "ecr_filter_pre_prod_manager",
				disabled:false,
            	handler : function(e){
					var filter_txt = "待生产经理审批";
					filter = "pre_prod_approve_manager";
					ecr_filter(filter_txt);
            	},
            	scope: this
			},{text: "待PROD总监审批(wait PROD Director approve)",
				cls: "x-btn-text-icon",
				id: "ecr_filter_pre_prod_Director",
				disabled:false,
            	handler : function(e){
					var filter_txt = "待生产总监审批";
					filter = "pre_prod_approve_director";
					ecr_filter(filter_txt);
            	},
            	scope: this
			},{text: "待PPC审批(wait PPC　approve)",
				cls: "x-btn-text-icon",
				id: "ecr_filter_pre_ppc",
				disabled:false,
            	handler : function(e){
					var filter_txt = "待PPC审批";
					filter = "pre_ppc_approve";
					ecr_filter(filter_txt);
            	},
            	scope: this
			},{text: "待PPC经理审批(wait PPC Manager approve)",
				cls: "x-btn-text-icon",
				id: "ecr_filter_pre_ppc_manager",
				disabled:false,
            	handler : function(e){
					var filter_txt = "待PPC经理审批";
					filter = "pre_ppc_approve_manager";
					ecr_filter(filter_txt);
            	},
            	scope: this
			},{text: "待QA审批(wait QA approve)",
				cls: "x-btn-text-icon",
				id: "ecr_filter_pre_qa",
				disabled:false,
            	handler : function(e){
					var filter_txt = "待QA审批";
					filter = "pre_qa_approve";
					ecr_filter(filter_txt);
            	},
            	scope: this
            },{text: "待QA经理审批(wait QA Manager approve)",
				cls: "x-btn-text-icon",
				id: "ecr_filter_pre_qa_manager",
				disabled:false,
            	handler : function(e){
					var filter_txt = "待QA经理审批";
					filter ="pre_qa_approve_manager";
					ecr_filter(filter_txt);
            	},
            	scope: this
            }/*,{text: "待PE处理",
				cls: "x-btn-text-icon",
				id: "ecr_filter_pre_pe_operation",
				disabled:false,
            	handler : function(e){
					var filter_txt = "待PE处理";
					filter = "pre_pe_operation";
					ecr_filter(filter_txt);
            	},
            	scope: this
            },{text: "待CAM处理",
					cls: "x-btn-text-icon",
					id: "id_ecr_cam",
					disabled:false,
					handler : function(){
						var filter_txt = "待CAM处理";
						filter = "CAM";
						ecr_filter(filter_txt);
					}
			},{text: "待MI处理",
					cls: "x-btn-text-icon",
					id: "id_ecr_planning",
					disabled:false,
					handler : function(){
						var filter_txt = "待MI处理";
						filter = "Planning";
						ecr_filter(filter_txt);
					}
			},{text: "待QAE处理",
				cls: "x-btn-text-icon",
				id: "ecr_filter_pre_qae_operation",
				disabled:false,
            	handler : function(e){
					var filter_txt = "待QAE处理";
					filter = "pre_qae_operation";
					ecr_filter(filter_txt);
            	},
            	scope: this
            }*/,
			{text: "All",
				cls: "x-btn-text-icon",
				id: "ecr_filter_all",
				disabled:false,
            	handler : function(){
					var filter_txt = "All";
					filter = "";
					Ext.getCmp('keyword_search_recordno').setValue('');
					ecr_filter(filter_txt)
            	},
            	scope: this
			}]
         });
     this.menu_statistics = new Ext.menu.Menu({
		shadow: 'frame',
		items: [{text: "查找",
				cls: "x-btn-text-icon",
            	handler:statistics,
            	scope: this
            },{text: "输出至Excel",
				cls: "x-btn-text-icon",
            	handler : function(e){
						var con = Ext.getCmp('topic-grid').getStore();
						var gridcontent = '';
						if (Ext.isIE6 || Ext.isIE7 || Ext.isSafari || Ext.isSafari2 || Ext.isSafari3||Ext.isIE8 ||Ext.isIE9) {
		    				var fd=Ext.get('frmDummy');
		    				if (!fd) {
		        				fd=Ext.DomHelper.append(Ext.getBody(),{tag:'form',method:'post',id:'frmDummy',action:'./action/exportexcel.php', target:'_blank',name:'frmDummy',cls:'x-hidden',cn:[{tag:'input',name:'exportContent',id:'exportContent',type:'hidden'}]},true);
		    				}
		    				fd.child('#exportContent').set({value:gridcontent});
		    				fd.dom.submit();
						} else {
		    				document.location = 'data:application/vnd.ms-excel;base64,'+Base64.encode(vExportContent);
						}
            	},
            	scope: this
            }]
        });
    this.filemenu = new Ext.menu.Menu({
		shadow: 'frame',
		items: [{text: "删除",
				icon: "icon/item_cross.png",
				cls: "x-btn-text-icon",
				id: "delete_record",
				disabled:true,
				handler: function(){
					var selModel = Ext.getCmp('topic-grid').getSelectionModel();
					if(selModel.hasSelection()){
						deleterecord(sel_id);
					} else {
						alert('没有选择记录');
					}
				}},
				{text: "添加/删除工序",
				icon: "icon/background_management.gif",
				cls: "x-btn-text-icon",
				id: "background_management",
				disabled:true,
				handler: function(){
					//background_management();
					add_process();
				}},{text: "用户管理",
					icon: "icon/user.gif",
					cls: "x-btn-text-icon",
					id: "user_management",
					disabled:true,
					handler : function(){
						this.openTab_user();
					},
					scope: this
				},{text: "测试",
					cls: "x-btn-text-icon",
					id: "add_test",
					disabled:false,
					handler :this.openTab_request_test,
					scope: this
				}]
			});
  this.grid = new FeedGrid(this, {
        tbar:[{
            split:true,
            cls: "x-btn-text-icon",
            icon: "icon/view.gif",
            text:'查看(Reading)',
            tooltip: {title:'查看(Reading)',text:''},
            handler: this.movePreview.createDelegate(this, []),
            menu:{
                id:'reading-menu',
                cls:'reading-menu',
                width:100,
                items: [{
                    text:'Bottom',
                    checked:true,
                    group:'rp-group',
                    checkHandler:this.movePreview,
                    scope:this,
                    iconCls:'preview-bottom'
                },{
                    text:'Right',
                    checked:false,
                    group:'rp-group',
                    checkHandler:this.movePreview,
                    scope:this,
                    iconCls:'preview-right'
                },{
                    text:'Hide',
                    checked:false,
                    group:'rp-group',
                    checkHandler:this.movePreview,
                    scope:this,
                    iconCls:'preview-hide'
                }]
            }
        },
        '-',
        {
                	id:'menu_admin',
                	cls: "x-btn-text-icon",
                	icon: "images/admin.gif",
					text: '管理',
					menu: this.filemenu
        },'-',{
                	id:'menu_filter',
                	cls: "x-btn-text-icon",
                	icon: "icon/filter.gif",
					text: '过滤(Filter)',
					menu: this.filtermenu
        },'-',{
                	id:'menu_statistics',
                	cls: "x-btn-text-icon",
                	icon: "icon/statistics.png",
					text: '统计',
					menu: this.menu_statistics

        },'-',{
			icon: "images/arrow_refresh.png",
			cls: "x-btn-text-icon",
			text: "刷新",
			width:300,
			handler: function(){
				refresh_fun();
			}
		},new Ext.Toolbar.Fill(),
		new Ext.Toolbar.TextItem("查询TCN No.:"),
		new Ext.form.TriggerField({
			id: "keyword_search_recordno",
			triggerClass: "x-form-search-trigger",
			emptyText: "",
			onTriggerClick: search_recordno,
			enableKeyEvents: true,
       		listeners :{
                	specialKey:function(field,e){
                  		if (e.getKey() == Ext.EventObject.ENTER) {
								search_recordno();
                  		}
                   	}
            }
		})]
    });

	var genesis_job = GetParameter('genesis_job');
	if (genesis_job != "") {
	  var reg = /^[y,Y,Z,z][0-9]{5}[n,x,y,N,X,Y][A-Z,a-z][0-9]+$/;
      var result =  reg.exec(genesis_job);
      if (result) {
		var job = genesis_job.substr(0,5);
		Ext.getCmp('keyword').setValue(job);
	    search_ynumber();
	    refresh_ecngrid(job,'');
	    Ext.getCmp('feed-tree').collapsed=true;
      }
	}
   MainPanel.superclass.constructor.call(this, {
        id:'main-tabs',
        activeTab:0,
        region:'center',
        margins:'0 5 5 0',
        resizeTabs:true,
        tabWidth:150,
        minTabWidth: 120,
        enableTabScroll: true,
        plugins: new Ext.ux.TabCloseMenu(),
        items: {
            id:'main-view',
            layout:'border',
            title:'TCN',
            hideMode:'offsets',
            items:[
    			this.grid,
           {
                id:'bottom-preview',
                layout:'fit',
                items:this.preview,
                height: 250,
                split: true,
                border:false,
                region:'south'

            }, {
                id:'right-preview',
                layout:'fit',
                border:false,
                region:'east',
                width:350,
                split: true,
                hidden:true
            }]
        }
    });
    this.gsm = this.grid.getSelectionModel();
    this.gsm.on('rowselect', function(sm, index, record){
    	cur_row_no     = index;
        cur_tooling_no = record.data.tooling_pn;
        cur_mrp_no     = record.data.merix_pn;
        cur_ecr_type   =record.data.ecr_type;
	
        sel_id = record.data.id;
        FeedViewer.getTemplate().overwrite(this.preview.body,record.data);
        FeedViewer.getTemplate();
        document.getElementById('tab_iframe').src = "loadform.php?id="+sel_id;
        var items = this.preview.topToolbar.items;
	//	 items.get('tab').enable();
		 Ext.getCmp('tab').enable(true);
	   //  Ext.getCmp('tab_ppc_approve').enable(true);
        var records = sm.getSelections(); 
	
		
      // items.get('tab_pe_operation').disable(true);
	   // items.get('tab_qae_operation').disable(true);
	   if (approve_action =='ALL')
	   {
    	items.get('tab_me_approve').enable(true);
     	items.get('tab_me_director_approve').enable(true);
    	items.get('tab_pe_approve').enable(true);
        items.get('tab_pe_approve_manager').enable(true);
	    items.get('tab_prod_approve').enable(true);
	    items.get('tab_prod_approve_manager').enable(true);
	    items.get('tab_prod_approve_director').enable(true);
	    Ext.getCmp('tab_ppc_approve').enable(true);
	    Ext.getCmp('tab_ppc_approve_manager').enable(true);
	    Ext.getCmp('tab_qa_approve_manager').enable(true);
	    Ext.getCmp('tab_qa_approve').enable(true);
       }else{
		items.get('tab_me_approve').disable(true);
     	items.get('tab_me_director_approve').disable(true);
    	items.get('tab_pe_approve').disable(true);
        items.get('tab_pe_approve_manager').disable(true);
	    items.get('tab_prod_approve').disable(true);
	    items.get('tab_prod_approve_manager').disable(true);
	    items.get('tab_prod_approve_director').disable(true);
	    Ext.getCmp('tab_ppc_approve').disable();
	    Ext.getCmp('tab_ppc_approve_manager').disable();
    	Ext.getCmp('tab_qa_approve_manager').disable();
    	Ext.getCmp('tab_qa_approve').disable();
		/*
		  items.get('tab_ppc_approve').disable(true);
	    items.get('tab_ppc_approve_manager').disable(true);
	    items.get('tab_qa_approve_manager').disable(true);
	    items.get('tab_qa_approve').disable(true);
		*/
	   }
	     
      if (login_username != '') {
	        if (approve_action == 'ME_approve') {
				if (check_approve_status(records,'me')) {
	        	   items.get('tab_me_approve').enable(true);
				}
	        }
           if (approve_action == 'ME_approve_director') {
				if (check_approve_status(records,'me_director')) {
	        	   items.get('tab_me_director_approve').enable(true);
				}
	        }
	        if (approve_action == 'PE_approve') {
	        	if (check_approve_status(records,'pe')) {
	        		if (cur_ecr_type == '正式') {
						var reg = /TCN/;
	        		} else {
	        			var reg = /TEMP/;
	        		}
	        	   	if (reg.exec(usr_authorization)) {
					  items.get('tab_pe_approve').enable(true);
   					}
	        	}
				
	        }	
		 if (approve_action == 'PE_approve_manager') {
			  
				if (check_approve_status(records,'pe_manager')) {
	        	    items.get('tab_pe_approve_manager').enable(true);
	        	}
	        }
            if (approve_action.toUpperCase() == "PROD_APPROVE") {
			  
				if (check_approve_status(records,'prod')) {
	        	    items.get('tab_prod_approve').enable(true);
	        	}
	        }
		 if (approve_action.toUpperCase() == "PROD_APPROVE_MANAGER") {
				if (check_approve_status(records,'prod_manager')) {
	        	    items.get('tab_prod_approve_manager').enable(true);
	        	}
	        }
	     if (approve_action.toUpperCase() == "PROD_APPROVE_DIRECTOR") {
			   
				if (check_approve_status(records,'prod_director')) {
	        	    items.get('tab_prod_approve_director').enable(true);
	        	}
	        }

	     if (approve_action.toUpperCase() == "PPC_APPROVE") {
			  
				if (check_approve_status(records,'ppc')) {
	        	   // items.get('tab_ppc_approve').enable(true);
				   Ext.getCmp('tab_ppc_approve').enable(true);
	        	}
	        }
		 if (approve_action.toUpperCase() == "PPC_APPROVE_MANAGER") {
			  
				if (check_approve_status(records,'ppc_manager')) {
	        	   // items.get('tab_ppc_approve_manager').enable(true);
					 Ext.getCmp('tab_ppc_approve_manager').enable(true);
	        	}
	        }
	        if (approve_action == 'QA_approve') {
	        	if (check_approve_status(records,'qa')) {
	        	    //items.get('tab_qa_approve').enable(true);
				    Ext.getCmp('tab_qa_approve').enable(true);
	        	}
	        }

         if (approve_action == 'QA_approve_manager') {
	        	if (check_approve_status(records,'qa_manager')) {
	        	   // items.get('tab_qa_approve_manager').enable(true);
					  Ext.getCmp('tab_qa_approve_manager').enable(true);
	        	}
	        }
        if (approve_action == 'pe_operation') {
	        if (check_approve_status(records,'pe_operation')) {
	        	items.get('tab_pe_operation').enable(true);
	        }
		}
		 if (approve_action == 'qae_operation') {
	        if (check_approve_status(records,'qae_operation')) {
	        	items.get('tab_qae_operation').enable(true);
	        }
		  }
	        if (check_approve_status(records,'modify')) {
	        	items.get('tab_tcn_modify').show(true);
	        }
		}
    }, this, {buffer:250});
    this.grid.on("rowdblclick", function(grid,rowIndex,colIndex,e) {
       return false;
    });
    this.grid.on("cellclick", function(grid,rowIndex,colIndex,e) {
       if (colIndex == 0) {
		  var row = grid.getSelectionModel().getSelected();
          if (row == null) {
          	init_tab_button();
          }
       }
    });
	this.grid.store.on('beforeload', function(e) {
	        if (filter == 'statistics') {
	            e.baseParams = {filter:filter,row1_chk:row1_chk,row1_datefrom:row1_datefrom,row1_dateto:row1_dateto,row2_chk:row2_chk,row2_proposer:row2_proposer,row3_chk:row3_chk,row3_me:row3_me,row4_chk:row4_chk,row4_pe:row4_pe,row5_chk:row5_chk,row5_qa:row5_qa,row6_chk:row6_chk,row6_type:row6_type,toolaffaced:toolaffaced};
	        } else {
	        	e.baseParams = {filter:filter};
	        }
           init_tab_button();
	});
    this.grid.store.on('load', function(s,records) {
        var girdcount=0;
        s.each(function(r){
        	if(r.get('me_approve')==''){
        		Ext.getCmp('topic-grid').getView().getCell(girdcount,9).style.backgroundColor='#FF0000';
            	Ext.getCmp('topic-grid').getView().getCell(girdcount,10).style.backgroundColor='#FF0000';
            } else if(r.get('me_approve_director')==''){
                Ext.getCmp('topic-grid').getView().getCell(girdcount,11).style.backgroundColor='#FFD700';
            	Ext.getCmp('topic-grid').getView().getCell(girdcount,12).style.backgroundColor='#FFD700';
            } else if(r.get('pe_approve')==''){
                Ext.getCmp('topic-grid').getView().getCell(girdcount,13).style.backgroundColor='#DB70DB';
            	Ext.getCmp('topic-grid').getView().getCell(girdcount,14).style.backgroundColor='#DB70DB';
            } else if(r.get('pe_approve_manager')==''){
                Ext.getCmp('topic-grid').getView().getCell(girdcount,15).style.backgroundColor='#BA55D3';
            	Ext.getCmp('topic-grid').getView().getCell(girdcount,16).style.backgroundColor='#BA55D3';
            } else if(r.get('approve_Prod')==''){
                Ext.getCmp('topic-grid').getView().getCell(girdcount,17).style.backgroundColor='#CD5555';
            	Ext.getCmp('topic-grid').getView().getCell(girdcount,18).style.backgroundColor='#CD5555';
            } else if(r.get('approve_Prod_manager')==''){
                Ext.getCmp('topic-grid').getView().getCell(girdcount,19).style.backgroundColor='#FF6EB4';
            	Ext.getCmp('topic-grid').getView().getCell(girdcount,20).style.backgroundColor='#FF6EB4';
            } else if(r.get('approve_director_production')==''){
                Ext.getCmp('topic-grid').getView().getCell(girdcount,21).style.backgroundColor='#CCFF80';
            	Ext.getCmp('topic-grid').getView().getCell(girdcount,22).style.backgroundColor='#CCFF80';
            } else if(r.get('approve_PPC')==''){
                Ext.getCmp('topic-grid').getView().getCell(girdcount,23).style.backgroundColor='#FF5809';
            	Ext.getCmp('topic-grid').getView().getCell(girdcount,24).style.backgroundColor='#FF5809';
            } else if(r.get('approve_PPC_manager')==''){
                Ext.getCmp('topic-grid').getView().getCell(girdcount,25).style.backgroundColor='#C07AB8';
            	Ext.getCmp('topic-grid').getView().getCell(girdcount,26).style.backgroundColor='#C07AB8';
            } else if(r.get('qa_approve')==''){
                Ext.getCmp('topic-grid').getView().getCell(girdcount,27).style.backgroundColor='#9999CC';
            	Ext.getCmp('topic-grid').getView().getCell(girdcount,28).style.backgroundColor='#9999CC';
            } else if(r.get('qa_approve_manager')==''){
            	Ext.getCmp('topic-grid').getView().getCell(girdcount,29).style.backgroundColor='#A6FFFF';
            	Ext.getCmp('topic-grid').getView().getCell(girdcount,30).style.backgroundColor='#A6FFFF';
            }
            if (r.get('ecr_no') == GetParameter('tcn_no')) {
				Ext.getCmp('topic-grid').getSelectionModel().selectRange(girdcount,girdcount);
            }
            girdcount=girdcount+1;

        });
    });
    this.grid.on("rowcontextmenu", function(grid, rowIndex, e) {
		e.preventDefault();
		if (rowIndex < 0) { return; }
		var selModel = Ext.getCmp('topic-grid').getSelectionModel();
		if(selModel.hasSelection()){
			var records 				= selModel.getSelections();
			var get_id         			= records[0].get("id");
			var get_tooling_pn 			= records[0].get("tooling_pn");
			var get_me_approve 			= records[0].get("me_approve");
			var get_pe_approve 			= records[0].get("pe_approve");
			var get_qa_approve 			= records[0].get("qa_approve");
			var get_pe_operator			= records[0].get("pe_operator");
			var get_qae_operator		= records[0].get("qae_operator");
			var get_follow_request 		= records[0].get("follow_request");
     		var get_me_approve_disabled   = true;
			var get_pe_approve_disabled   = true;
			var get_qa_approve_disabled   = true;
			var get_pe_operation_disabled = true;
			var get_qae_operation_disabled = true;

			if (usr_cusername != '') {
				if (get_follow_request == 'follow_no') {
					if (get_qa_approve != '/' && (get_qa_approve == usr_cusername || usr_cusername == 'admin')) {
					  get_qa_approve_disabled = false;
					}
					if (get_pe_approve != '/' && (get_pe_approve == usr_cusername || usr_cusername == 'admin')) {
					  get_pe_approve_disabled = false;
					}
					if (get_me_approve != '/' && (get_me_approve == usr_cusername || usr_cusername == 'admin')) {
					  get_me_approve_disabled = false;
					}
				} else {
					if (get_me_approve.toUpperCase() == usr_cusername.toUpperCase() || usr_cusername == 'admin') {
						if (get_pe_approve == '' && get_qa_approve == '') {
						  get_me_approve_disabled = false;
						}
					}
					if (get_pe_approve.toUpperCase() == usr_cusername.toUpperCase() || usr_cusername == 'admin') {
						if (get_qa_approve == '') {
							get_pe_approve_disabled = false;
						}
					}
					if ((get_qa_approve.toUpperCase() == usr_username.toUpperCase() || usr_cusername == 'admin') && get_qa_approve != '' ) {
						if (get_pe_operator == '') {
						  get_qa_approve_disabled = false;
						}
					}
					if ((get_pe_operator.toUpperCase() == usr_username.toUpperCase() || usr_cusername == 'admin') && get_pe_operator != '') {
						if (get_qae_operator == '') {
						  get_pe_operation_disabled = false;
						}
					}
					if ((get_qae_operator.toUpperCase() == usr_username.toUpperCase() || usr_cusername == 'admin') && get_qae_operator != '') {
						get_qae_operation_disabled = false;
					}
				}
		    }
            var treeMenu = new Ext.menu.Menu({
                id:'grid-ctx',
                items: [{
					xtype: "",
					id:"id_empty_me",
					text: "清空ME审批",
					pressed: false,
					disabled:get_me_approve_disabled,
                    handler: function(e){
								Ext.Ajax.request({
									url: 'action/empty_approve.php',
									params: {get_id:get_id,get_tooling_pn:get_tooling_pn,empty_type:'me',follow_request:get_follow_request},
									method: "post",
									success: function(response){
										refresh_fun();
										var json = Ext.util.JSON.decode(response.responseText);
										alert(json.message);
									},
									failure: function(){
										var json = Ext.util.JSON.decode(response.responseText);
										alert(json.message);
									}
								});
                    }
                },{
					xtype: "",
					id:"id_empty_pe",
					text: "清空PE审批",
					pressed: false,
					disabled:get_pe_approve_disabled,
                    handler: function(e){
								Ext.Ajax.request({
									url: 'action/empty_approve.php',
									params: {get_id:get_id,get_tooling_pn:get_tooling_pn,empty_type:'pe',follow_request:get_follow_request},
									method: "post",
									success: function(response){
										refresh_fun();
										var json = Ext.util.JSON.decode(response.responseText);
										alert(json.message);
									},
									failure: function(){
										var json = Ext.util.JSON.decode(response.responseText);
										alert(json.message);
									}
								});
                    }
                },{
					xtype: "",
					id:"id_empty_qa",
					text: "清空QA审批",
					pressed: false,
					disabled:get_qa_approve_disabled,
                    handler: function(e){
								Ext.Ajax.request({
									url: 'action/empty_approve.php',
									params: {get_id:get_id,get_tooling_pn:get_tooling_pn,empty_type:'qa',follow_request:get_follow_request},
									method: "post",
									success: function(response){
										refresh_fun();
										var json = Ext.util.JSON.decode(response.responseText);
										alert(json.message);
									},
									failure: function(){
										var json = Ext.util.JSON.decode(response.responseText);
										alert(json.message);
									}
								});
                    }
                },{
					xtype: "",
					id:"id_empty_pe_operation",
					text: "清空PE处理",
					pressed: false,
					disabled:get_pe_operation_disabled,
                    handler: function(e){
								Ext.Ajax.request({
									url: 'action/empty_approve.php',
									params: {get_id:get_id,get_tooling_pn:get_tooling_pn,empty_type:'pe_operation'},
									method: "post",
									success: function(response){
										refresh_fun();
										var json = Ext.util.JSON.decode(response.responseText);
										Ext.MessageBox.alert("Return",json.message);
									},
									failure: function(){
										var json = Ext.util.JSON.decode(response.responseText);
										Ext.MessageBox.alert("Return",json.message);
									}
								});
                    }
                },{
					xtype: "",
					id:"id_empty_qae_operation",
					text: "清空QAE处理",
					pressed: false,
					disabled:get_qae_operation_disabled,
                    handler: function(e){
								Ext.Ajax.request({
									url: 'action/empty_approve.php',
									params: {get_id:get_id,get_tooling_pn:get_tooling_pn,empty_type:'qae_operation'},
									method: "post",
									success: function(response){
										refresh_fun();
										var json = Ext.util.JSON.decode(response.responseText);
										Ext.MessageBox.alert("Return",json.message);
									},
									failure: function(){
										var json = Ext.util.JSON.decode(response.responseText);
										Ext.MessageBox.alert("Return",json.message);
									}
								});
                    }
                }
           ]});
           treeMenu.showAt(e.getXY());
		}
	})
};
Ext.extend(MainPanel, Ext.TabPanel, {
    loadFeed : function(feed){
    	Ext.getCmp('main-view').setTitle(feed);
    },
    movePreview : function(m, pressed){
        if(!m){
            var items = Ext.menu.MenuMgr.get('reading-menu').items.items;
            var b = items[0], r = items[1], h = items[2];
            if(b.checked){
                r.setChecked(true);
            }else if(r.checked){
                h.setChecked(true);
            }else if(h.checked){
                b.setChecked(true);
            }
            return;
        }
        if(pressed){
            var preview = this.preview;
            var right = Ext.getCmp('right-preview');
            var bot = Ext.getCmp('bottom-preview');
            var btn = this.grid.getTopToolbar().items.get(2);
            switch(m.text){
                case 'Bottom':
                    right.hide();
                    bot.add(preview);
                    bot.show();
                    bot.ownerCt.doLayout();
                    btn.setIconClass('preview-bottom');
                    break;
                case 'Right':
                    bot.hide();
                    right.add(preview);
                    right.show();
                    right.ownerCt.doLayout();
                    btn.setIconClass('preview-right');
                    break;
                case 'Hide':
                    preview.ownerCt.hide();
                    preview.ownerCt.ownerCt.doLayout();
                    btn.setIconClass('preview-hide');
                    break;
            }
        }
    },
    openTab : function(record){
    	if (this.gsm.getSelected() == null) {
    		alert('请选择要查看的记录');
    		return;
    	}
        record = (record && record.data) ? record : this.gsm.getSelected();
        tab_iframe_name = 'tab_iframe';
        var d = record.data;
        var sel_id = d.id;
        var sel_tooling_pn = d.merix_pn;
        var sel_freezing = d.freezing;
        var getTemplate = FeedViewer.getTemplate();
        getTemplate.html= "<iframe id='tab_iframe' name='tab_iframe' scrolling='auto' frameborder='1' width='100%' height='100%' src='loadform.php?id="+sel_id+"&tooling_pn="+sel_tooling_pn+"' allowTransparency=true></iframe>";
        var linenum = cur_row_no + 1;
        var id = cur_tooling_no + "_" + linenum;
        var tab;
        if(!(tab = this.getItem(id))){
            tab = new Ext.Panel({
                id: id,
                cls:'preview single-preview',
                title: "View",
                //html: getTemplate.apply(d),
                html:getTemplate.html,
                closable:true,
                autoScroll:false,
                border:true,
                width:'100%',
    			height:'100%'
            });
            this.add(tab);
        }
        this.setActiveTab(tab);
    },
   openTab_request : function(){
        var tab;
        if(!(tab = this.getItem(id))){
        	tab_iframe_name = 'tab_iframe_apply';
        	addjob(id,tab_iframe_name);
            tab = new Ext.Panel({
                id: id,
                cls:'preview single-preview',
                title: "TCN申请",
                tabTip: "TCN申请",
                frame: false,
                width:'100%',
                height:'100%',
                html:"<iframe id='tab_iframe_apply' scrolling='auto' frameborder='1' width='100%' height='100%' src='loading.html' allowTransparency=true></iframe>",
                closable:true,
                border:true
            });
            this.add(tab);
        }
		this.setActiveTab(tab);
    },
    openTab_request_temp : function(){
        var tab;
        if(!(tab = this.getItem(id))){
         	tab_iframe_name = 'tab_iframe_apply_temp';
        	addjob(id,tab_iframe_name);
            tab = new Ext.Panel({
                id: id,
                cls:'preview single-preview',
                title: "临时TCN申请",
                tabTip: "临时TCN申请",
                frame: false,
                width:'100%',
                height:'100%',
                html:"<iframe id='tab_iframe_apply_temp' scrolling='auto' frameborder='1' width='100%' height='100%' src='loading.html' allowTransparency=true></iframe>",
                closable:true,
                border:true
            });
            this.add(tab);
        }
			this.setActiveTab(tab);
    },
    openTab_request_test : function(){
        var tab;
        if(!(tab = this.getItem(id))){
         	tab_iframe_name = 'tab_iframe_apply_test';
        	addjob(id,tab_iframe_name);
            tab = new Ext.Panel({
                id: id,
                cls:'preview single-preview',
                title: "测试-TCN申请",
                tabTip: "测试-TCN申请",
                frame: false,
                width:'100%',
                height:'100%',
                html:"<iframe id='tab_iframe_apply_test' scrolling='auto' frameborder='1' width='100%' height='100%' src='loading.html' allowTransparency=true></iframe>",
                closable:true,
                border:true
            });
            this.add(tab);
        }
			this.setActiveTab(tab);
    },
    openTab_modify_request : function(sel_id){
        var tab;
        tab_iframe_name = 'tab_iframe_modify';
        if(!(tab = this.getItem(id))){
            tab = new Ext.Panel({
                id: id,
                cls:'preview single-preview',
                title: "TCN修改",
                tabTip: "TCN修改",
                frame: false,
                width:'100%',
                height:'100%',
                html:"<iframe id='tab_iframe_modify' scrolling='auto' frameborder='1' width='100%' height='100%' src='me_modify.php?id="+ sel_id + "&login_username=" + usr_username + "&login_cusername=" + usr_cusername + "&login_usr_work_id=" + usr_work_id + "&login_userrole=" + usr_dept+"'"+" allowTransparency=true></iframe>",
                closable:true,
                border:true
            });
            this.add(tab);
        }
			this.setActiveTab(tab);
    },
	openTab_pe_approve : function(sel_id){
        var tab;
        tab_iframe_name = 'tab_iframe_pe';
        if(!(tab = this.getItem(id))){
            tab = new Ext.Panel({
                id: id,
                cls:'preview single-preview',
                title: "PE审批",
                tabTip: "PE审批",
                frame: false,
                width:'100%',
                height:'100%',
                html:"<iframe id='tab_iframe_pe' scrolling='auto' frameborder='1' width='100%' height='100%' src='pe_approve.php?id="+ sel_id + "&login_username=" + usr_username + "&login_cusername=" + usr_cusername + "&login_usr_work_id=" + usr_work_id + "&login_userrole=" + usr_dept+"'"+" allowTransparency=true></iframe>",
                closable:true,
                border:true
            });
            this.add(tab);
        }
			this.setActiveTab(tab);
    },
   openTab_pe_approve_manager : function(sel_id){
        var tab;
        tab_iframe_name = 'tab_iframe_pe_manager';
        if(!(tab = this.getItem(id))){
            tab = new Ext.Panel({
                id: id,
                cls:'preview single-preview',
                title: "PE经理审批",
                tabTip: "PE经理审批",
                frame: false,
                width:'100%',
                height:'100%',
                html:"<iframe id='tab_iframe_pe_manager' scrolling='auto' frameborder='1' width='100%' height='100%' src='pe_approve.php?id="+ sel_id + "&login_username=" + usr_username + "&login_cusername=" + usr_cusername + "&login_usr_work_id=" + usr_work_id + "&login_userrole=" + usr_dept+"'"+" allowTransparency=true></iframe>",
                closable:true,
                border:true
            });
            this.add(tab);
        }
			this.setActiveTab(tab);
    },
    openTab_prod_approve : function(sel_id){
        var tab;
        tab_iframe_name = 'tab_iframe_prod';
        if(!(tab = this.getItem(id))){
            tab = new Ext.Panel({
                id: id,
                cls:'preview single-preview',
                title: "生产部审批",
                tabTip: "生产部审批",
                frame: false,
                width:'100%',
                height:'100%',
                html:"<iframe id='tab_iframe_prod' scrolling='auto' frameborder='1' width='100%' height='100%' src='prod_approve.php?id="+ sel_id + "&login_username=" + usr_username + "&login_cusername=" + usr_cusername + "&login_usr_work_id=" + usr_work_id + "&login_userrole=" + usr_dept+"'"+" allowTransparency=true></iframe>",
                closable:true,
                border:true
            });
            this.add(tab);
         }
			this.setActiveTab(tab);
       },
      openTab_prod_approve_manager : function(sel_id){
        var tab;
        tab_iframe_name = 'tab_iframe_prod_manager';
        if(!(tab = this.getItem(id))){
            tab = new Ext.Panel({
                id: id,
                cls:'preview single-preview',
                title: "生产经理审批",
                tabTip: "生产经理审批",
                frame: false,
                width:'100%',
                height:'100%',
                html:"<iframe id='tab_iframe_prod_manager' scrolling='auto' frameborder='1' width='100%' height='100%' src='prod_approve.php?id="+ sel_id + "&login_username=" + usr_username + "&login_cusername=" + usr_cusername + "&login_usr_work_id=" + usr_work_id + "&login_userrole=" + usr_dept+"'"+" allowTransparency=true></iframe>",
                closable:true,
                border:true
            });
            this.add(tab);
         }
			this.setActiveTab(tab);
       },
	  openTab_prod_approve_director : function(sel_id){
        var tab;
        tab_iframe_name = 'tab_iframe_prod_director';
        if(!(tab = this.getItem(id))){
            tab = new Ext.Panel({
                id: id,
                cls:'preview single-preview',
                title: "生产总监审批",
                tabTip: "生产总监审批",
                frame: false,
                width:'100%',
                height:'100%',
                html:"<iframe id='tab_iframe_prod_manager' scrolling='auto' frameborder='1' width='100%' height='100%' src='prod_approve.php?id="+ sel_id + "&login_username=" + usr_username + "&login_cusername=" + usr_cusername + "&login_usr_work_id=" + usr_work_id + "&login_userrole=" + usr_dept+"'"+" allowTransparency=true></iframe>",
                closable:true,
                border:true
            });
            this.add(tab);
         }
			this.setActiveTab(tab);
       },
      openTab_ppc_approve : function(sel_id){
        var tab;
        tab_iframe_name = 'tab_iframe_ppc';
        if(!(tab = this.getItem(id))){
            tab = new Ext.Panel({
                id: id,
                cls:'preview single-preview',
                title: "PPC审批",
                tabTip: "PPC审批",
                frame: false,
                width:'100%',
                height:'100%',
                //html: FeedViewer.getTemplate().apply(),
                html:"<iframe id='tab_iframe_me' scrolling='auto' frameborder='1' width='100%' height='100%' src='ppc_approve.php?id="+ sel_id + "&login_username=" + usr_username + "&login_cusername=" + usr_cusername + "&login_usr_work_id=" + usr_work_id + "&login_userrole=" + usr_dept+"'"+" allowTransparency=true></iframe>",
                closable:true,
                //listeners: FeedViewer.LinkInterceptor,
                border:true
            });
            this.add(tab);
        }
			this.setActiveTab(tab);
	  },
	  openTab_ppc_approve_manager : function(sel_id){
        var tab;
        tab_iframe_name = 'tab_iframe_ppc_manager';
        if(!(tab = this.getItem(id))){
            tab = new Ext.Panel({
                id: id,
                cls:'preview single-preview',
                title: "PPC经理审批",
                tabTip: "PPC经理审批",
                frame: false,
                width:'100%',
                height:'100%',
                //html: FeedViewer.getTemplate().apply(),
                html:"<iframe id='tab_iframe_me' scrolling='auto' frameborder='1' width='100%' height='100%' src='ppc_approve.php?id="+ sel_id + "&login_username=" + usr_username + "&login_cusername=" + usr_cusername + "&login_usr_work_id=" + usr_work_id + "&login_userrole=" + usr_dept+"'"+" allowTransparency=true></iframe>",
                closable:true,
                //listeners: FeedViewer.LinkInterceptor,
                border:true
            });
            this.add(tab);
        }
			this.setActiveTab(tab);
	},
	openTab_me_approve : function(sel_id){
        var tab;
        tab_iframe_name = 'tab_iframe_me';
        if(!(tab = this.getItem(id))){
            tab = new Ext.Panel({
                id: id,
                cls:'preview single-preview',
                title: "PRE经理审批",
                tabTip: "PRE经理审批",
                frame: false,
                width:'100%',
                height:'100%',
                //html: FeedViewer.getTemplate().apply(),
                html:"<iframe id='tab_iframe_me' scrolling='auto' frameborder='1' width='100%' height='100%' src='me_approve.php?id="+ sel_id + "&login_username=" + usr_username + "&login_cusername=" + usr_cusername + "&login_usr_work_id=" + usr_work_id + "&login_userrole=" + usr_dept+"'"+" allowTransparency=true></iframe>",
                closable:true,
                //listeners: FeedViewer.LinkInterceptor,
                border:true
            });
            this.add(tab);
        }
			this.setActiveTab(tab);
	},
		openTab_me_approve_director : function(sel_id){
        var tab;
        tab_iframe_name = 'tab_iframe_me_director';
        if(!(tab = this.getItem(id))){
            tab = new Ext.Panel({
                id: id,
                cls:'preview single-preview',
                title: "PRE总监审批",
                tabTip: "PRE总监审批",
                frame: false,
                width:'100%',
                height:'100%',
                //html: FeedViewer.getTemplate().apply(),
                html:"<iframe id='tab_iframe_me_director' scrolling='auto' frameborder='1' width='100%' height='100%' src='me_approve.php?id="+ sel_id + "&login_username=" + usr_username + "&login_cusername=" + usr_cusername + "&login_usr_work_id=" + usr_work_id + "&login_userrole=" + usr_dept+"'"+" allowTransparency=true></iframe>",
                closable:true,
                //listeners: FeedViewer.LinkInterceptor,
                border:true
            });
            this.add(tab);
        }
			this.setActiveTab(tab);
	},
  openTab_qa_approve : function(sel_id){
        var tab;
        tab_iframe_name = 'tab_iframe_qa';
        if(!(tab = this.getItem(id))){
            tab = new Ext.Panel({
                id: id,
                cls:'preview single-preview',
                title: "QA审批",
                tabTip: "QA审批",
                frame: false,
                width:'100%',
                height:'100%',
                //html: FeedViewer.getTemplate().apply(),
                html:"<iframe id='tab_iframe_qa' scrolling='auto' frameborder='1' width='100%' height='100%' src='qa_approve.php?id="+ sel_id + "&login_username=" + usr_username + "&login_cusername=" + usr_cusername + "&login_usr_work_id=" + usr_work_id + "&login_userrole=" + usr_dept+"'"+" allowTransparency=true></iframe>",
                closable:true,
                //listeners: FeedViewer.LinkInterceptor,
                border:true
            });
            this.add(tab);
        }
			this.setActiveTab(tab);
	},
	openTab_qa_approve_manager : function(sel_id){
        var tab;
        tab_iframe_name = 'tab_iframe_qa';
        if(!(tab = this.getItem(id))){
            tab = new Ext.Panel({
                id: id,
                cls:'preview single-preview',
                title: "QA经理审批",
                tabTip: "QA经理审批",
                frame: false,
                width:'100%',
                height:'100%',
                //html: FeedViewer.getTemplate().apply(),
                html:"<iframe id='tab_iframe_qa' scrolling='auto' frameborder='1' width='100%' height='100%' src='qa_approve.php?id="+ sel_id + "&login_username=" + usr_username + "&login_cusername=" + usr_cusername + "&login_usr_work_id=" + usr_work_id + "&login_userrole=" + usr_dept+"'"+" allowTransparency=true></iframe>",
                closable:true,
                //listeners: FeedViewer.LinkInterceptor,
                border:true
            });
            this.add(tab);
        }
			this.setActiveTab(tab);
	},
    openTab_user : function(){
	       var tab;
	        if(!(tab = this.getItem(id))){
	            tab = new Ext.Panel({
	                id: 'id_user_managerment',
	                cls:'preview single-preview',
	                title: "用户管理",
	                tabTip: "用户管理",
	                frame: false,
	                width:'100%',
	                height:'100%',
	                autoLoad:{url:'./user_management.html',scripts:true},
	                //html:"<iframe id='tab_iframe_user' scrolling='auto' frameborder='1' width='90%' height='90%' src='./user_management.html' allowTransparency=true></iframe>",
	                closable:true,
	                border:true
	            });
	            this.add(tab);
	        }
	        this.setActiveTab(tab);
    },
  openAll : function(){
        this.beginUpdate();
        this.grid.store.data.each(this.openTab, this);
        this.endUpdate();
    }
});





