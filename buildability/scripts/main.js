$(document).ready(function(){  
	var editor;
	if ($("#item_create_date").html()=='')
	{
		alert('The part number ' + $("#part_number").html() + ' has been removed from Buildability.');
		var ul = window.location.href;
		var ul = ul.replace(/&part_number=([^&]*)/g, '');
		var ul = ul.replace(/&id=([^&]*)/g, '');
		var ul = ul.replace(/&pt=([^&]*)/g, ''); 
		window.location.href = ul;
	}

	if ($("#html2pdf").html()!='')
	{
		$(".topnav").hide();
		$(".brand").hide();
		$(".ad-banner").hide();
		$(".ad-text").hide();
		$(".clearfix").hide();
		$(".nav").hide();
		$(".top_link").hide();
		$("#footer").hide();
		$(".comment_title").hide();
		$(".comment").css("border-top","0px");
		$(".plating_attr_edit").hide();
		$(".title_b").parent().parent().toggleClass("expanded_b");
		$(".title_b").parent().parent().find(".micon").show();
	}



	$(".login").show();
	$(".my_items").selectbox();
	UserRole();
	//$( "input:submit").button();
	if ($("#userRole").html() != 'PT' && $("#userRole").html() != 'Admin')
	{
		$(".attr_edit").hide();
		//$("#run_logic").hide();
		//$("#email_button").hide();
	}
	
	$(".title_b").bind("click",
	function() {
		if ($(this).parent().parent().hasClass('expanded_b'))
		{
			$(this).parent().parent().toggleClass("expanded_b");
			$(this).parent().parent().find(".micon").hide();
		} else {
			$(this).parent().parent().toggleClass("expanded_b");
			$(this).parent().parent().find(".micon").show();
		}


		if ($(this).parent().parent().find(".show_text").length>0)
		{
			$(this).parent().parent().find(".show_text").remove();
		}
		
    }
	);
	$(".easyui-linkbutton").bind("click",
	function()  {
		if ($(this).attr("id") =="comment_add")
		{
			//check login
			if ($("#loggedUserName").html().length==0)
			{
				$(".login").trigger("click");
				return false;
			}
			//check close
			if ($(".radio_close").find(":radio").attr("checked"))
			{
				alert('This buildability is "completed" and no updates can be made.');
				return false;
			}

			$("#content").html("");
			if ($(this).parent().parent().parent().parent().find(".show_text").length>0)
			{
				$(this).parent().parent().parent().parent().find(".show_text").remove();
			}
			else {
				$(this).parent().parent().parent().parent().append('<div class="show_text" > '+
																   '<textarea id="content" name="content" style="width:935px;height:400px;visibility:hidden;"></textarea> '+ 
																   '<div class="add_submit_btn"><span style="float:right;color:#444;margin-right:5px;">OK</span></div>'+
																   '</div>');
				//$("#content").xheditor({tools:'full',skin:'default',width:800,height:200,upImgUrl:"buildability/upload.php",upImgExt:"jpg,jpeg,gif,png"}); 
				editor = KindEditor.create('textarea[name="content"]', {
						langType : 'en',
						resizeType : 1
				});
				
			}
		}
		if ($(this).attr("id") =="gn_comment_add")
		{
			//check login
			if ($("#loggedUserName").html().length==0)
			{
				$(".login").trigger("click");
				return false;
			}
			//check close
			if ($(".radio_close").find(":radio").attr("checked"))
			{
				alert('This buildability is "completed" and no updates can be made.');
				return false;
			}

			$("#content").html("");
			if ($(this).parent().parent().parent().parent().find(".show_text").length>0)
			{
				$(this).parent().parent().parent().parent().find(".show_text").remove();
			}
			else {
				$(this).parent().parent().parent().find(".box_1").append('<div class="show_text" style="padding-left:5px;padding-bottom:5px;" > '+
																   '<textarea id="content" name="content" style="width:935px;height:400px;visibility:hidden;">'+$.trim($(this).parent().parent().parent().find(".hot_box").html())+'</textarea> '+ 
																   '<div class="gn_add_submit_btn"><span style="float:right;color:#444;margin-right:5px;">OK</span></div>'+
																   '</div>');
				//$("#content").xheditor({tools:'full',skin:'default',width:800,height:200,upImgUrl:"buildability/upload.php",upImgExt:"jpg,jpeg,gif,png"});  
				//$('#content').spellAsYouType();
				editor = KindEditor.create('textarea[name="content"]', {
						langType : 'en',
						resizeType:1
				});
			}

		}
		
    }
	);

	$(".add_submit_btn").live("click",
	function() {
		editor.sync();
		if ($(this).parent().find("#content").val() == '')
		{
			$(this).parent().parent().find(".show_text").remove();
		}
		//check login
			if ($("#loggedUserName").html().length==0)
			{
				$(".login").trigger("click");
				return false;
			}
			//check close
			if ($(".radio_close").find(":radio").attr("checked"))
			{
				alert('This buildability is "completed" and no updates can be made.');
				return false;
			}
		var date = new Date(); 
		
		//insert the note to database:
		$.ajax({ 
                    type: "POST", 
                    url: "buildability/create_note.php?part_number="+$("#part_number").html()+'&section_name='+$(this).parent().parent().parent().parent().parent().parent().find(".title_b").html(),
					data:{note: $(this).parent().find("#content").val(),user_name: $("#loggedUserName").html(),part_number_id:$("#pn_id").html(),site:$("#site_text").html()},
                    dataType: "json", 
                    success: function(msg) { 
						var Result = msg.success;
                        if (Result == false) {
                           alert(msg.message);
						   return;
                         }
                        else 
                        if (Result == true) {
							
                        }
                       } 
         });

		//change html doc
		var note_index =new Number($(this).parent().parent().find("#comments").find('h4:last').html()) + 1;
		var new_comment = '<div class="comment_note"> ' +
						  '<div class="comment_note_title"><h5 style="float:left;">#</h5><h4 style="float:left;">'+note_index+'</h4><h3 style="float:left;">User Name:'+ $("#loggedUserName").html()+'</h3>'+
						  '<div title="Delete note" style="float:right;" class="note_del_btn"></div>'+
						  '<div title="Edit note" style="float:right;" class="note_edit_btn"></div>'+
						  '<h3 style="float:right;">Time:'+date.pattern("yyyy-MM-dd hh:mm:ss")+'</h3></div> '+
						  '<div class="this_note">'+
						  $(this).parent().find("#content").val()+
						  '</div></div>';
		$(this).parent().parent().find("#comments").append(new_comment);
		$(this).parent().parent().find(".show_text").remove();
		//if ($(this).parent().parent().parent().parent().parent().parent().parent().hasClass('content_textarea_h'))
		//{
		//	$(this).parent().parent().parent().parent().parent().parent().parent().toggleClass("content_textarea");
		//}
    }
	);

	$(".gn_add_submit_btn").live("click",
	function() {
		editor.sync();
		if ($(this).parent().find("#content").val() == '')
		{
			$(this).parent().parent().find(".show_text").remove();
			return;
		}
		//check login
			if ($("#loggedUserName").html().length==0)
			{
				$(".login").trigger("click");
				return false;
			}
			//check close
			if ($(".radio_close").find(":radio").attr("checked"))
			{
				alert('This buildability is "completed" and no updates can be made.');
				return false;
			}
		var date = new Date(); 
		
		//insert the note to database:
		$.ajax({ 
                    type: "POST", 
                    url: "buildability/create_note.php?part_number="+$("#part_number").html()+'&section_name=General',
					data:{note: $(this).parent().find("#content").val(),user_name: $("#loggedUserName").html(),part_number_id:$("#pn_id").html()},
                    dataType: "json", 
                    success: function(msg) { 
						var Result = msg.success;
                        if (Result == false) {
                           alert(msg.message);
						   return;
                         }
                        else 
                        if (Result == true) {
							
                        }
                       } 
         });

		//change html doc
		var new_comment = '<div> ' +
						  $(this).parent().find("#content").val()+
						  '</div>';
		$(this).parent().parent().parent().find(".hot_box").html(new_comment);
		$(this).parent().parent().find(".show_text").remove();
		//if ($(this).parent().parent().parent().parent().parent().parent().parent().hasClass('content_textarea_h'))
		//{
		//	$(this).parent().parent().parent().parent().parent().parent().parent().toggleClass("content_textarea");
		//}
    }
	);

	$(".note_del_btn").live("click",
		function() {
		//check login
			if ($("#loggedUserName").html().length==0)
			{
				$(".login").trigger("click");
				return false;
			}
			//check close
			if ($(".radio_close").find(":radio").attr("checked"))
			{
				alert('This buildability is "completed" and no updates can be made.');
				return false;
			}
			var cur_user = $(this).parent().parent().find("h3").html();
			//alert(cur_user + ' - - '+$("#loggedUserName").html() );
			if (cur_user != 'User Name:'+$("#loggedUserName").html())
			{
				alert('You only can delete your own notes.');
				return false;
			}
		if ( confirm('Are you sure want to delete this note?'))
		{
			//delete note from database
			$.ajax({ 
						type: "POST", 
						url: "buildability/delete_note.php?part_number_id="+$("#pn_id").html()+'&section_name='+$(this).parent().parent().parent().parent().parent().parent().parent().parent().find(".title_b").html(),
						data:{note_number:$(this).parent().parent().find(".note_number").html(),site:$("#site_text").html()},
						dataType: "json", 
						success: function(msg) { 
							var Result = msg.success;
							if (Result == false) {
							   alert(msg.message);
							   return;
							 }
							else 
							if (Result == true) {
							
							}
						   } 
			  });


			$(this).parent().parent().remove();
		}
		
	}
	);

	$(".note_edit_btn").live("click",
		function() {
			//check login
			if ($("#loggedUserName").html().length==0)
			{
				$(".login").trigger("click");
				return false;
			}
			//check close
			if ($(".radio_close").find(":radio").attr("checked"))
			{
				alert('This buildability is "completed" and no updates can be made.');
				return false;
			}
			var cur_user = $(this).parent().parent().find("h3").html();
			if (cur_user != 'User Name:'+$("#loggedUserName").html())
			{
				alert('You only can edit your own notes.');
				return false;
			}
			if ($(this).parent().parent().find("#e_content").length==0)
			{
				$(this).parent().parent().append('<div>'+
									         '<textarea id="e_content" name="e_content" style="width:99%;height:400px;margin-left:2px;margin-right:2px;">'+$.trim($(this).parent().parent().find(".this_note").html())+"</textarea>" +
										     '<div class="note_submit_btn"><span style="float:right;color:#444;margin-right:5px;">OK</span></div>'+
											 '</div>');	
				//$("#e_content").xheditor({tools:'full',skin:'default',width:800,height:200,upImgUrl:"buildability/upload.php",upImgExt:"jpg,jpeg,gif,png"});
				editor = KindEditor.create('textarea[name="e_content"]', {
						langType : 'en',
						resizeType:1
				});
				//$('#e_content').spellAsYouType();
			} else {
				$(this).parent().parent().find("#e_content").parent().remove();
			}
				
	}
	);

	$(".note_submit_btn").live("click",
		function() {
			editor.sync();
			if ($(this).parent().find("#e_content").val() == '')
			{
				$(this).parent().find("#e_content").parent().remove();
				return;
			}
			//check login
			if ($("#loggedUserName").html().length==0)
			{
				$(".login").trigger("click");
				return false;
			}
			//check close
			if ($(".radio_close").find(":radio").attr("checked"))
			{
				alert('This buildability is "completed" and no updates can be made.');
				return false;
			}

			
			//update note in database.
				$.ajax({ 
						type: "POST", 
						url: "buildability/modify_note.php?part_number_id="+$("#pn_id").html()+'&section_name='+$(this).parent().parent().parent().parent().parent().parent().parent().parent().find(".title_b").html(),
						data:{note: $(this).parent().find("#e_content").val(),note_number:$(this).parent().parent().find(".note_number").html(),site:$("#site_text").html()},
						dataType: "json", 
						success: function(msg) { 
							var Result = msg.success;
							if (Result == false) {
							   alert(msg.message);
							   return;
							 }
							else 
							if (Result == true) {
							
							}
						   } 
			  });

			//
			if ($(this).parent().parent().find(".this_note").length>0)
			{
				$(this).parent().parent().find(".this_note").html($(this).parent().find("#e_content").val());
				$(this).parent().find("#e_content").parent().remove();
			}
			
				
	}
	);

	$("#cancel").bind("click",
		function() {
			$('#sign_up_window').window('close');
	}
	);

	$("#log_in").bind("click",
		function() {
			if ($("#user_name").val()=='' || $("#pass_word").val()=='')
			{
				alert('Please input User Name and Password.');
				return false;
			}
			$('#sign_up_window').mask("Processing login...");
			$.ajax({ 
                    type: "POST", 
                    url: "buildability/login_chk.php?uname="+$("#user_name").val()+'&remember='+$("#remember_login").attr("checked"), 
                    data:{chknumber: $("#pass_word").val()},
					dataType: "json", 
                    success: function(msg) { 
						var Result = msg.success;
                        if (Result == false) {
						   $('#sign_up_window').unmask();
                           alert(msg.message);        
                         }
                        else 
                        if (Result == true) {
							$('#sign_up_window').unmask();
							$('#sign_up_window').window('close');
							$(".login").hide();
							$("#loggedUserName").html(msg.message);
							$("#userRole").html(msg.role);
							$(".Manage").css("display","block");
							UserRole();
							if ($("#userRole").html() == 'PT')
							{
								$(".attr_edit").show();
								//$("#email_button").show();
							}
	
							easyloader.load('easyui-menubutton'); 
							//$(".topnavright").append("<div class=\"logout\">Log Out"+"</div>");
						}
                       } 
              });
	}
	);


	$("#logout").live("click",
		function() {
			if (confirm("Are you sure want to log out?"))
			{
				$.ajax({ 
                    type: "POST", 
                    url: "buildability/logout.php", 
                    dataType: "json", 
                    success: function(msg) { 
						var Result = msg.success;
                        if (Result == false) {
                           alert(msg.message);        
                         }
                        else 
                        if (Result == true) {
                            $(".logout").remove();
							$(".login").remove();
							$(".topnavright").append('<div class=\"login\">Login</div>');
							$("#loggedUserName").html("");
							$(".Manage").css("display","none");
							$(".attr_edit").hide();
							//$("#email_button").hide();
							}
                       } 
              });
			}
			
	}
	);


	$(".redio_red,.redio_green,.redio_gray,.redio_yellow").bind("click",
		function() {
			//return false;
			//check login
			if ($("#loggedUserName").html().length==0)
			{
				$(".login").trigger("click");
				return false;
			}
			if ($(this).hasClass("_selected"))
			{
				return false;
			}
			//check close
			if ($(".radio_close").find(":radio").attr("checked"))
			{
				alert('This buildability is "completed" and no updates can be made.');
				return false;
			}
			var role = $("#userRole").html();
			var status = $(this).find("label").html();
			if (status == 'Not Needed' && role !='PT' && role !='Admin' )
			{
				$(this).parent().find("._selected").find(":radio").attr("checked","true");
				alert('Only system itself or PT can change the status to No Needed.');
				return false;
			}
			
			if (role)
			{
				if (role == 'Engineer')
				{
					if (status != 'Eng Complete' && status != 'Review Required')
					{
						alert('You can not change the status to '+status+'.');
						return false;
					}
				}
			}
			/*var comment_window = '<div id="comment_window" class="easyui-window" closed="true" modal="true" title="Add comment..." collapsible="false" minimizable="false" maximizable="false" style="width:300px;height:150px;">'+
										'<div class="easyui-layout" fit="true">'+
										'<div region="center" border="false" style="padding:10px;background:#fff;border:1px solid #ccc;">'+
										'<div>'+
										'		<textarea id="comment_content"></textarea>'+
										'</div>'+
										'</div>'+
										'<div region="south" border="false" style="text-align:right;height:30px;line-height:30px;">'+
										'	<a class="easyui-linkbutton" iconCls="icon-ok" href="javascript:void(0)" onclick="creat_new()">OK</a>'+
										'	<a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="close_input()">No</a>'+
										'</div>'+
										'</div>'+
							'</div>';
				$('body').mask("Processing login...");
				$(this).append(comment_window);
				$("#comment_content").xheditor({tools:'full',skin:'default',width:200,height:200});
				$.parser.parse($(this));
				$('#comment_window').window('open');
				$('body').unmask();*/
				var this_dom = $(this);
				var comment_dialog = '<div id="com_dig"><div id="comment_dialog" style="padding:5px;width:350px;height:200px;"><textarea class="xheditor {skin:\'default\'}" id="comment_content" style="width:323px;height:115px;"></textarea></div></div>';
				
				//$("#comment_content").addClass("xheditor-simple");
				//$("#comment_content").xheditor({tools:'full',skin:'default',width:800,height:200,upImgUrl:"buildability/upload.php",upImgExt:"jpg,jpeg,gif,png"});  
				$(this).append(comment_dialog);
				//$.parser.parse($(this).parent());
				$('#comment_dialog').dialog({
								title:'Add comment...',
								iconCls:'icon-save',
								modal:true,
								closable:false,
								onClose:function(){
									$('#comment_dialog').remove();
									$('#com_dig').remove();
								},
								onOpen:function(){
									$('#comment_content').focus();
								},
								buttons:[{
									text:'Ok',
									iconCls:'icon-ok',
									handler:function(){
										if (!$('#comment_content').val())
										{
											alert('Plese input some comments before change the status.');
											return;
										}
										if (confirm("Are you sure want to change the status to "+status+"?"))
											{
												
												if (change_status(this_dom.parent().parent().parent().parent().parent().parent().parent().parent().find(".title_b").html(),status))
												{
												}
												//add note:
												$.ajax({ 
															type: "POST", 
															url: "buildability/create_note.php?part_number="+$("#part_number").html()+'&section_name='+this_dom.parent().parent().parent().parent().parent().parent().parent().find(".title_b").html(),
															data:{note: $('#comment_content').val(),user_name: $("#loggedUserName").html(),part_number_id:$("#pn_id").html(),site:$("#site_text").html()},
															dataType: "json", 
															success: function(msg) { 
																var Result = msg.success;
																if (Result == false) {
																   alert(msg.message);
																   return;
																 }
																else 
																if (Result == true) {
																	
																}
															   } 
												 });
												var date = new Date(); 
												//change html doc
												var note_index =new Number(this_dom.parent().parent().parent().parent().parent().find("#comments").find('h4:last').html()) + 1;
												var new_comment = '<div class="comment_note"> ' +
																	  '<div class="comment_note_title"><h5 style="float:left;">#</h5><h4 style="float:left;">'+note_index+'</h4><h3 style="float:left;">User Name:'+ $("#loggedUserName").html()+'</h3>'+
																	  '<div title="Delete note" style="float:right;" class="note_del_btn"></div>'+
																	  '<div title="Edit note" style="float:right;" class="note_edit_btn"></div>'+
																	  '<h3 style="float:right;">Time:'+date.pattern("yyyy-MM-dd hh:mm:ss")+'</h3></div> '+
																	  '<div class="this_note">'+
																	  $('#comment_content').val()+
																	  '</div></div>';
												
												this_dom.parent().parent().parent().parent().parent().find("#comments").append(new_comment);
												 
												this_dom.parent().children().each(function(){
														if ($(this).hasClass("_selected"))
														{
															$(this).removeClass("_selected");
														}
														}
													);
													
													this_dom.addClass("_selected");
													this_dom.find(":radio").attr("checked","true");
													this_dom.parent().parent().parent().find(".update_uname").html($("#loggedUserName").html());
													this_dom.parent().parent().parent().find(".update_upass").html(date.pattern("yyyy-MM-dd hh:mm:ss"));
													$('#comment_dialog').dialog('close');

											} else
												this_dom.parent().find("._selected").find(":radio").attr("checked","true");
									}
								},{
									text:'No',
									iconCls:'icon-cancel',
									handler:function(){
										$('#comment_dialog').dialog('close');
										this_dom.parent().find("._selected").find(":radio").attr("checked","true");
									}
								}]
							});
			
	}
	);

	$(".radio_open,.radio_close").bind("click",
		function() {
			//check login
			if ($("#loggedUserName").html().length==0)
			{
				$(".login").trigger("click");
				return false;
			}
			if ($(this).hasClass("_selected"))
			{
				return false;
			}
			var status = $(this).find("label").html();
			if (status == 'Open')
			{
				alert('Please create another Buildability for this Part Number.');
				$(this).parent().find("._selected").find(":radio").attr("checked","true");
				return false;
			}
			var curdom = $(this);

			$.ajax({ 
                    type: "POST", 
                    url: "buildability/check_complete.php?part_number_id="+$("#pn_id").html(),
					data:{site:$("#site_text").html()},
					dataType: "json", 
                    success: function(msg) { 
						var Result = msg.success;
                        if (Result == false) {
							
						   curdom.parent().find("._selected").find(":radio").attr("checked","true");
                           alert('Error:Can not change the status to Complete.'+'\nReason:'+msg.message + ' exist.');  
						   return false;
                         }
                        else 
                        if (Result == true) {
							if (confirm("Are you sure want to change the status to "+status+"?"))
							{
								change_open_status(status);
								curdom.parent().children().each(function(){
										if (curdom.hasClass("_selected"))
										{
											curdom.removeClass("_selected");
										}
										}
									);
								curdom.addClass("_selected");
								curdom.find(":radio").attr("checked","true");
							} else
								curdom.parent().find("._selected").find(":radio").attr("checked","true");
							}
						} 
              });
			
	}
	);


	function change_open_status(status){
			$.ajax({ 
                    type: "POST", 
                    url: "buildability/close_open.php?part_number="+$("#part_number").html(),
					data:{status:status,user_name: $("#loggedUserName").html(),part_number_id:$("#pn_id").html(),site:$("#site_text").html()},
					dataType: "json", 
                    success: function(msg) { 
						var Result = msg.success;
                        if (Result == false) {
                           alert(msg.message);  
						   return false;
                         }
                        else 
                        if (Result == true) {
							return true;
                        }
                       } 
              });

	
	};

	Date.prototype.pattern=function(fmt) {     
		var o = {     
		"M+" : this.getMonth()+1,   
		"d+" : this.getDate(),      
		"h+" : this.getHours()%12 == 0 ? 12 : this.getHours()%12,     
		"H+" : this.getHours()<10 ? '0'+this.getHours():this.getHours(),     
		"m+" : this.getMinutes()<10 ? '0'+this.getMinutes():this.getMinutes(),     
		"s+" : this.getSeconds()<10 ? '0'+this.getSeconds():this.getSeconds(),    
		"q+" : Math.floor((this.getMonth()+3)/3),     
		"S" : this.getMilliseconds()    
		};     
		var week = {     
		"0" : "\u65e5",     
		"1" : "\u4e00",     
		"2" : "\u4e8c",     
		"3" : "\u4e09",     
		"4" : "\u56db",     
		"5" : "\u4e94",     
		"6" : "\u516d"    
		};     
		if(/(y+)/.test(fmt)){     
			fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));     
		}     
		if(/(E+)/.test(fmt)){     
			fmt=fmt.replace(RegExp.$1, ((RegExp.$1.length>1) ? (RegExp.$1.length>2 ? "\u661f\u671f" : "\u5468") : "")+week[this.getDay()+""]);     
		}     
		for(var k in o){     
			if(new RegExp("("+ k +")").test(fmt)){     
				fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));     
			}     
		}     
		return fmt;     
	};
		
});

$(".login").live("click",
	function() {
		$('#sign_up_window').window('open');
		$('#sign_up_window').find('input:first').val("");
		$('#sign_up_window').find('#pass_word').val("");
		$('#sign_up_window').find('input:first').focus();
    }
	);

$("#add_member").live("click",
	function() {
		$("#add_mem_uname").combobox('clear');
		$("#add_mem_dispname").val("");
		$("#add_mem_email").val("");
		$('#add_member_window').window('open');
		//$('#add_member_window').find('input:first').focus();
		loaduserdata();
    }
);

$("#config_email").live("click",
	function() {
		//$("#add_mem_uname").combobox('clear');
		$('#config_email_window').window('open');
		loademailuserdata();

    }
);

function sign_up_focus_next(event){
	if (event.keyCode==13)
	{
		$('#sign_up_window').find('input:next').focus();
	}
	
}

function enter_login(event){
	if (event.keyCode==13)
	{
		$("#log_in").trigger("click");
	}
}

$("#add_member_no").live("click",
	function() {
		$('#add_member_window').window('close');
    }
);

$("#add_member_ok").live("click",
	function() {
		var user_name = $("#add_mem_uname").combobox('getText');
		var disp_name = $("#add_mem_dispname").val();
		var email = $("#add_mem_email").val();
		var role = $("#role").combobox('getText');
		var obso = $("#obsolete").combobox('getValue');
		if (!user_name)
		{
			alert("User Name cannot be empty.");
			return false;
		}
		if (!disp_name)
		{
			alert("Display Name cannot be empty.");
			return false;
		}
		if (!email )
		{
			alert("Email cannot be empty.");
			return false;
		}
		$('body').mask("Adding member : "+user_name+"...");
		$.ajax({ 
                    type: "POST", 
                    url: "buildability/add_member.php",
					data:{user_name:user_name,display_name:disp_name,email:email,role:role,obsolete:obso,site:$("#site_text").html()},
					dataType: "json", 
                    success: function(msg) { 
						var Result = msg.success;
                        if (Result == false) {
						   $('body').unmask();
                           alert(msg.message);  
						   return false;
                         }
                        else 
                        if (Result == true) {
							 $('body').unmask();
							 $('#add_member_window').window('close');
							 alert(msg.message); 
                        }
                       } 
              });
    }
);

$("#send_mail").live("click",
	function() {
		var to_address = "kyle.jiang@viasystems.com";
		$('#sign_up_window').mask("Processing, please wait...");
		$.ajax({ 
                    type: "POST", 
                    url: "buildability/get_admin_email.php",
					data:{site:$("#site_text").html()},
					dataType: "json", 
                    success: function(msg) { 
						var Result = msg.success;
                        if (Result == false) {
						   $('#sign_up_window').unmask();
                           alert(msg.message);
						    window.location.href="mailto:"+to_address+"?subject=Please create a Buildability account for me.";
						   return false;
                         }
                        else 
                        if (Result == true) {
							$('#sign_up_window').unmask();
							if (msg.message)
							{
							   window.location.href="mailto:"+msg.message+"?subject=Please create a Buildability account for me.";
							}
							
                        }
                       } 
              });
		
	}
);
function loaduserdata(){
			$('#add_mem_uname').combobox({
				url:'buildability/getusers.php',
				valueField:'id',
				textField:'text'
			});
};

function loademailuserdata(){
	$('#email_cc_list').combobox({
				url:'buildability/getusers.php',
				valueField:'id',
				textField:'text'
			});
	$('#email_new_list').combobox({
				url:'buildability/getusers.php',
				valueField:'id',
				textField:'text'
			});
	$('#email_del_list').combobox({
				url:'buildability/getusers.php',
				valueField:'id',
				textField:'text'
			});
	$('#email_close_list').combobox({
				url:'buildability/getusers.php',
				valueField:'id',
				textField:'text'
			});
	$('#email_status_list').combobox({
				url:'buildability/getusers.php',
				valueField:'id',
				textField:'text'
			});


	// get 
	$('#config_email_window').mask("Processing, please wait...");
		$.ajax({ 
                    type: "POST", 
                    url: "buildability/get_config.php",
					data:{site:$("#site_text").html(),config_type:'email'},
					dataType: "json", 
                    success: function(msg) { 
						var Result = msg.success;
                        if (Result == false) {
						   $('#config_email_window').unmask();
                           alert(msg.message);
						   return false;
                         }
                        else 
                        if (Result == true) {
							$('#config_email_window').unmask();
							$("#email_cc_list").combobox('setValue',msg.email_cc_list);
							$("#email_new_list").combobox('setValue',msg.email_new_list);
							$("#email_del_list").combobox('setValue',msg.email_del_list);
							$("#email_close_list").combobox('setValue',msg.email_close_list);
							$("#email_status_list").combobox('setValue',msg.email_status_list);							
                        }
                       } 
              });
	
};

function getuserdata(){
	var user_name = $("#add_mem_uname").combobox('getText');
	if (!user_name)
	{
		alert('Please input user name to search first.');
		return false;
	}
	
		$('#add_member_window').mask("Processing, please wait...");
		$.ajax({ 
                    type: "POST", 
                    url: "buildability/getuserdata.php?user_name=" +user_name,
					dataType: "json", 
                    success: function(msg) { 
						var Result = msg.success;
                        if (Result == false) {
						   $('#add_mem_uname').unmask();
						   $('#add_member_window').unmask();
                           alert(msg.message);
						   return false;
                         }
                        else 
                        if (Result == true) {
							$('#add_member_window').unmask();
							$("#add_mem_dispname").val(msg.dispname);
							$("#add_mem_email").val(msg.email);
							$("#role").combobox('setValue',msg.role);
							
                        }
                       } 
              });


};

function UserRole(){
		var role = $("#userRole").html();
		if (role)
		{
			if (role =='Admin')
			{
				$("#add_member").css("display","block");
				$("#config_email").css("display","block");
			} else {
				$("#add_member").css("display","none");
				$("#config_email").css("display","none");
			}

		} else {
			$("#add_member").css("display","none");
			$("#config_email").css("display","none");
		}
};

$("#con_email_no").live("click",
		function() {
			$('#config_email_window').window('close');
	}
);

$("#con_email_ok").live("click",
	function(){
		var email_cc_list = $("#email_cc_list").combobox('getText');
		var email_new_list = $("#email_new_list").combobox('getText');
		var email_del_list = $("#email_del_list").combobox('getText');
		var email_close_list = $("#email_close_list").combobox('getText');
		var email_status_list = $("#email_status_list").combobox('getText');
		
		$('#config_email_window').mask("Processing, please wait...");
		$.ajax({ 
                    type: "POST", 
                    url: "buildability/save_email_config.php",
					data:{site:$("#site_text").html(),email_cc_list:email_cc_list,email_new_list:email_new_list,email_del_list:email_del_list,
						email_close_list:email_close_list,email_status_list:email_status_list},
					dataType: "json", 
                    success: function(msg) { 
						var Result = msg.success;
                        if (Result == false) {
						   $('#config_email_window').unmask();
                           alert(msg.message);  
						   return false;
                         }
                        else 
                        if (Result == true) {
							 $('#config_email_window').unmask();
							 alert(msg.message); 
							 $('#config_email_window').window('close');
                        }
                       } 
              });
    }
);

$(".attr_edit").live("click",
	function(){
		//check login
			if ($("#loggedUserName").html().length==0)
			{
				$(".login").trigger("click");
				return false;
			}
			//check close
			if ($(".radio_close").find(":radio").attr("checked"))
			{
				alert('This buildability is "completed" and no updates can be made.');
				return false;
			}
			var role = $("#userRole").html();
			if (role)
			{
				if (role != 'PT' && role !='Admin')
				{
					alert('You can not enter the data except PT.');
					return false;
				}
			}
		var title = $(this).parent().parent().find(".td_title").html();
		if (title == 'Are soldermask SMD annular ring, SMDs clearance bridge, or SM slivers Genesis check rules violated')
		{
			title ='Are soldermask SMD annular ring check rules violated';
		}
		var value = $(this).parent().parent().find(".td_value").html();

		//$('#input_data_title').html(title+':');
		//$('#input_data_window').window('open');
		//$('#input_data_window').find('input:first').val(value);
		//$('#input_data_window').find('input:first').focus();
		var title_id = title.replace(/\s/g, "_");
			title_id  = title_id.replace(/\+\/\-/g,"_");
			title_id  = title_id.replace(/\./g,"_");
			title_id  = title_id.replace(/&lt;/g,"_");
			title_id  = title_id.replace(/\,/g,"_");
			title_id  = title_id.replace(/\//g,"_");
			

		$('#'+title_id).hide();
		$('#'+title_id+'_1').hide();

		$(this).hide();
		if (title == 'Xact values status color')
		{
			$(this).parent().prepend('<select class="attr_new_value" style="width:155px;">'+
										'<option value="None">None</option>'+
										'<option value="Green">Green</option>'+
										'<option value="Yellow">Yellow</option>'+
										'<option value="Red">Red</option>'+
									'</select>'+'<img class="attr_edit_ok" src="images/ok.png"  alt="Save..." ></img><img class="attr_edit_no" src="images/cancel.png"  alt="Cancel..."></img>');
			//$.parser.parse($(this).parent());
			$(this).parent().find('.attr_new_value').attr('value',value);
		} else if ( title == 'Dictated Drills' || title == 'Is the slot length &lt; 2 X bit diameter' || title == 'Are there overlapping holes requiring burr removal'
				|| title =='SMD annular ring' || title == 'SMDs clearance bridge' || title =='SM slivers' 
				|| title=='Score offset tolerance less than +/- .003 inches'
				|| title=='Remaining score web less than .006 inches'
				|| title=='Remaining web tolerance less than +/- .003 inches'
				|| title=='Score edge to edge tolerance less than +/- .005 inches'
				|| title=='Datum to score edge tolerance less than +/- .005 inches'
				|| title=='Angle tolerance less than +/- .5 degrees'
				|| title=='Remaining material thickness tolerance less than +/- .007 inches'
				|| title=='Board edge to mill edge tolerance less than +/- .005 inches'
				|| title=='Z-axis cleanrance to Cu feature less than .007 inches'
				|| title=='Are soldermask SMD annular ring check rules violated'
				|| title=='Multi-layer/multicolor SM or nomen')
		{
			$(this).parent().prepend('<select class="attr_new_value" style="width:155px;">'+
										'<option value="Yes">Yes</option>'+
										'<option value="No">No</option>'+
									'</select>'+'<img class="attr_edit_ok" src="images/ok.png"  alt="Save..." ></img><img class="attr_edit_no" src="images/cancel.png"  alt="Cancel..."></img>');
			//$.parser.parse($(this).parent());
			$(this).parent().find('.attr_new_value').attr('value',value);
		} else if (title == 'Wrap spec' )
		{
			$(this).parent().prepend('<span class="attr_new_value"><select class="easyui-combobox" editable="false" listHeight="5" style="width:155px;">'+
										'<option value="Class II">Class II</option>'+
										'<option value="Class III">Class III</option>'+
										'<option value="Waived">Waived</option>'+
										'<option value="Other (see notes)">Other (see notes)</option>'+
									'</select></span>'+'<img class="attr_edit_ok" src="images/ok.png"  alt="Save..." ></img><img class="attr_edit_no" src="images/cancel.png"  alt="Cancel..."></img>');
			$.parser.parse($(this).parent());
			$(this).parent().find('.easyui-combobox').combobox('setValue',value);
		} else if (title == 'Enter score overrun value from score calculator')
		{
			$(this).parent().prepend('<span class="attr_new_value"><input class="easyui-numberbox" required="true"  style="width:155px;">'+
									 '</input></span>'+'<img class="attr_edit_ok" src="images/ok.png"  alt="Save..." ></img><img class="attr_edit_no" src="images/cancel.png"  alt="Cancel..."></img>');
			$.parser.parse($(this).parent());
			$(this).parent().find('.easyui-numberbox').val(value);
			
		}
		else
		{
			$(this).parent().prepend('<input class="attr_new_value" type="text"></input><img class="attr_edit_ok" src="images/ok.png"  alt="Save..." ></img><img class="attr_edit_no" src="images/cancel.png"  alt="Cancel..."></img>');
			$(this).parent().find('.attr_new_value').attr('value',value);
		}
}
);


$(".plating_attr_edit").live("click",
	function(){
		//check login
			if ($("#loggedUserName").html().length==0)
			{
				$(".login").trigger("click");
				return false;
			}
			//check close
			if ($(".radio_close").find(":radio").attr("checked"))
			{
				alert('This buildability is "completed" and no updates can be made.');
				return false;
			}
			var role = $("#userRole").html();
			if (role)
			{
				if (role != 'PT' && role != 'Engineer' && role !='Admin')
				{
					alert('You can not enter the data except PT or Engineer.');
					return false;
				}
			}
		var plating_program_name = $(this).parent().parent().find("TD:first").html();
		var override = $(this).parent().find("SPAN:first").html()
		plating_program_name = plating_program_name.replace('/','_');
		//alert(override);
		//return false;

		$(this).hide();
		$(this).parent().find("SPAN:first").hide();
		
			//$(this).parent().prepend('<span class="plating_attr_new_value"><input class="easyui-combobox" editable="false" listHeight="5" style="width:155px; valueField="id" textField="text" >'+
			//						'</input></span>'+'<img class="plating_attr_edit_ok" src="images/ok.png"  alt="Save..." ></img><img class="plating_attr_edit_no" src="images/cancel.png"  alt="Cancel..."></img>');
			//$.parser.parse($(this).parent());
			
			//$(this).parent().find('.easyui-combobox').combobox({
			//	url:'buildability/get_plating_values.php?plating_program_name='+plating_program_name+'&site='+$("#site_text").html(),
			//	valueField:'id',
			//	textField:'text'
			//});
			//$(this).parent().find('.easyui-combobox').combobox('setValue',override);
		//url="buildability/get_plating_values.php?plating_program_name='+plating_program_name+'&site='+$("#site_text").html()
		if (plating_program_name =='Front etch comp' || plating_program_name =='Back etch comp' )
		{
			$(this).parent().prepend('<span class="plating_attr_new_value"><input type="text" style="width:200px;">'+
									'</input></span>'+'<img class="plating_attr_edit_ok" src="images/ok.png"  alt="Save..." ></img><img class="plating_attr_edit_no" src="images/cancel.png"  alt="Cancel..."></img>');

		} else {
			$(this).parent().prepend('<span class="plating_attr_new_value"><select class="mycombo_grid" editable= "true" style="width:200px;">'+
									'</select></span>'+'<img class="plating_attr_edit_ok" src="images/ok.png"  alt="Save..." ></img><img class="plating_attr_edit_no" src="images/cancel.png"  alt="Cancel..."></img>');
			//$.parser.parse($(this).parent());
			var cur_this = $(this);
			$(this).parent().find('.mycombo_grid').combogrid({
				panelWidth:320,
			    value:override,
				idField:'id',
				textField:'text',
				sortName:'discription',
				sortOrder: 'asc',
				url:'buildability/get_plating_values.php?plating_program_name='+plating_program_name+'&site='+$("#site_text").html(),
				columns:[[
					{field:'text',title:'Discription',width:100,sortable:true},
					{field:'id',title:'ID',width:30,hidden:true},
					{field:'cu',title:'Cu',width:30},
					{field:'ni',title:'Ni',width:30},
					{field:'sn',title:'SN',width:30},
					{field:'au',title:'AU',width:30}
				]],
				onSelect:function(index,row){
					var i=0;
					cur_this.parent().parent().find("TD").each(function(){
						i= i+1;
						if (i == 4)
						{
							$(this).html(row.cu);
						} else if (i==5)
						{
							$(this).html(row.ni);
						} else if (i==6)
						{
							$(this).html(row.sn);
						} else if (i==7)
						{
							$(this).html(row.au);
						}
					}
					)
				}
			});
		
		}

		
}
);

$('.plating_attr_edit_no').live("click",
function (){
	$(this).parent().parent().find('.plating_attr_edit').show();

	var attr_new_value = $(this).parent().parent().find('.plating_attr_new_value');
	var attr_edit_ok = $(this).parent().parent().find('.plating_attr_edit_ok');
	var attr_edit_no = $(this).parent().parent().find('.plating_attr_edit_no');

	 attr_new_value.remove();
	 $(this).parent().find("SPAN:first").show();
	 attr_edit_ok.remove();
	 attr_edit_no.remove();				 
	
}
);

$('.plating_attr_edit_ok').live("click",
function (){
	var new_value = "";
	var new_id="";
	if ($(this).parent().find('.mycombo_grid').length)
	{
		new_value = $(this).parent().find('.mycombo_grid').combogrid('getText');
		new_id = $(this).parent().find('.mycombo_grid').combogrid('getValue');
	} else {
		new_value = $(this).parent().find('input').val();
	}
	
	//alert(new_value);
	var plating_program_name = $(this).parent().parent().find("TD:first").html();
	var sub_part =$(this).parent().parent().parent().find(".cur_part_number").html();
	if (sub_part == $("#part_number").html())
	{
		sub_part = '';
	}

	$.ajax({ 
                    type: "POST", 
                    url: "buildability/save_plating_data.php",
					data:{plating_program_name:plating_program_name,sub_part:sub_part,site:$("#site_text").html(),new_value:new_value,new_id:new_id,part_number_id:$("#pn_id").html()},
					dataType: "json", 
                    success: function(msg) { 
						var Result = msg.success;
                        if (Result == false) {
                           alert(msg.message);  
						   return false;
                         }
                        else 
                        if (Result == true) {
							 
                        }
                       } 
              });

	$(this).parent().parent().find('.plating_attr_edit').show();

	var attr_new_value = $(this).parent().parent().find('.plating_attr_new_value');
	var attr_edit_ok = $(this).parent().parent().find('.plating_attr_edit_ok');
	var attr_edit_no = $(this).parent().parent().find('.plating_attr_edit_no');

	 attr_new_value.remove();
	 $(this).parent().find("SPAN:first").html(new_value);
	 $(this).parent().find("SPAN:first").show();
	 attr_edit_ok.remove();
	 attr_edit_no.remove();				 
	
}
);

$('.attr_edit_ok').live("click",
function (){
		var title = $(this).parent().parent().find('.td_title').html();
		if (title == 'Are soldermask SMD annular ring, SMDs clearance bridge, or SM slivers Genesis check rules violated')
		{
			title ='Are soldermask SMD annular ring check rules violated';
		}
		if (title == 'Surface Cu spec (parent and/or sub)?')
		{
			title ='Surface Cu spec';
		}
		if ($(this).parent().parent().find('select').hasClass('easyui-combobox'))
		{
			var value = $(this).parent().parent().find('.easyui-combobox').combobox('getText');	
		} else if ($(this).parent().parent().find('input').hasClass('easyui-numberbox'))
		{
			var value = $(this).parent().parent().find('.attr_new_value').find('.easyui-numberbox').val();
		}
		else{
			var value = $(this).parent().parent().find('.attr_new_value').val();
		}
		

		var attr_edit = $(this).parent().parent().find('.attr_edit');
		var id=title.replace(/\s/g, "_");
			id  = id.replace(/\+\/\-/g,"_");
			id  = id.replace(/\./g,"_");
			id  = id.replace(/&lt;/g,"_");
			id  = id.replace(/\,/g,"_");
			id  = id.replace(/\//g,"_");
			
		var value_1 = $(this).parent().parent().find('#'+id);
		var value_2 = $(this).parent().parent().find('#'+id+'_1');
		
		var attr_new_value = $(this).parent().parent().find('.attr_new_value');
		var attr_edit_ok = $(this).parent().parent().find('.attr_edit_ok');
		var attr_edit_no = $(this).parent().parent().find('.attr_edit_no');
		//alert($(this).parent().parent().find('select').html());

		$.ajax({ 
                    type: "POST", 
                    url: "buildability/save_entry_data.php",
					data:{site:$("#site_text").html(),title:title,value:value,part_number_id:$("#pn_id").html(),user_name:$("#loggedUserName").html()},
					dataType: "json", 
                    success: function(msg) { 
						var Result = msg.success;
                        if (Result == false) {
                           alert(msg.message);  
						   return false;
                         }
                        else 
                        if (Result == true) {
							 var date = new Date();
							 var updated_txt = 'Updated by '+$("#loggedUserName").html()+' at ' + date.pattern("yyyy-MM-dd hh:mm:ss");
							 value_1.html(value);
							 value_2.html(updated_txt);
							 //$('#'+id).html(value);
							 //$('#'+id+'_1').html(updated_txt);
							 value_1.show();
							 value_2.show();
							 attr_new_value.remove();
							 attr_edit_ok.remove();
							 attr_edit_no.remove();
							 attr_edit.show();
							 $.parser.parse($(this).parent().parent());
							 
                        }
                       } 
              });
}
);

$('.attr_edit_no').live("click",
function (){
	var title = $(this).parent().parent().find('.td_title').html();
	if (title == 'Are soldermask SMD annular ring, SMDs clearance bridge, or SM slivers Genesis check rules violated')
		{
			title ='Are soldermask SMD annular ring check rules violated';
		}
	var id=title.replace(/\s/g, "_");
	id  = id.replace(/\+\/\-/g,"_");
	id  = id.replace(/\./g,"_");
	id  = id.replace(/&lt;/g,"_");
	id  = id.replace(/\,/g,"_");
	id  = id.replace(/\//g,"_");
	
	$('#'+id).show();
	if ($('#'+id+'_1').length>0)
	{
		$('#'+id+'_1').show();
	}
	
	$(this).parent().parent().find('.attr_edit').show();

	var attr_new_value = $(this).parent().parent().find('.attr_new_value');
	var attr_edit_ok = $(this).parent().parent().find('.attr_edit_ok');
	var attr_edit_no = $(this).parent().parent().find('.attr_edit_no');

	 attr_new_value.remove();
	 attr_edit_ok.remove();
	 attr_edit_no.remove();
							 
}
);



$("#run_logic").live("click",
		function() {
		//check login
			if ($("#loggedUserName").html().length==0)
			{
				$(".login").trigger("click");
				return false;
			}
			//check close
			if ($(".radio_close").find(":radio").attr("checked"))
			{
				alert('This buildability is "completed" and no updates can be made.');
				return false;
			}
		if ( confirm('Are you sure want to re-run logic for this part number?'))
		{
			$('body').mask("Processing, please wait...");
			$.ajax({ 
						type: "POST", 
						url: "buildability/run_logic.php?part_number="+$("#part_number").html(),
						data:{site:$("#site_text").html(),user_name:$("#loggedUserName").html(),part_number_id:$("#pn_id").html()},
						dataType: "json", 
						success: function(msg) { 
							var Result = msg.success;
							if (Result == false) {
							   $('body').unmask();
							   alert(msg.message);
							   return;
							 }
							else 
							if (Result == true) {
								$('body').unmask();
								location.reload();
							}
						   } 
			  });
		}
		
	}
);

/*
$("#email_button").live("click",
		function() {
		//check login
			if ($("#loggedUserName").html().length==0)
			{
				$(".login").trigger("click");
				return false;
			}
			if ($("#email_send_note").html())
			{
				alert('Email already sent ' +$("#email_send_note").html() );
				return false;
			}
		if ( confirm('Are you sure want to send Buildability creation email out for this part number?'))
		{
			$('body').mask("Processing, please wait...");
			$.ajax({ 
						type: "POST", 
						url: "buildability/send_email.php?part_number="+$("#part_number").html(),
						data:{site:$("#site_text").html(),user_name:$("#loggedUserName").html(),site:$("#site_text").html()},
						dataType: "json", 
						success: function(msg) { 
							var Result = msg.success;
							if (Result == false) {
							   $('body').unmask();
							   alert(msg.message);
							   return;
							 }
							else 
							if (Result == true) {
								$('body').unmask();
								$("#email_send_note").html(msg.send_note);
								alert(msg.message);
							}
						   } 
			  });
		}
		
	}
);
*/

$('#nav_b_dis_list,#top_nav_b_dis_list').live("click",
		function() {
			$('body').mask('Loading Buildability Dispatch List...');	
	}
);


$(".area_status").live("click",function(){
	if ($("#loggedUserName").html().length==0)
	{
		$(".login").trigger("click");
		return false;
	}

});


function do_change_status(cur_this){
	var id = cur_this.id;

	var title = id.replace('_',' ');
	var old_value = cur_this.title;
	var new_value = $("#"+id+" option:selected").html();
	
	
	if (old_value == new_value)
	{
		return false;
	}

	var status = new_value;
	
	var this_dom = $("#"+id);
	
	var comment_dialog = '<div id="com_dig"><div id="comment_dialog" style="padding:5px;width:450px;height:300px;"><textarea id="comment_content" style="width:99%;height:210px;"></textarea></div></div>';
				
	this_dom.parent().append(comment_dialog);
	//$("#comment_content").xheditor({tools:'mini',skin:'default',width:420,height:210,upImgUrl:"buildability/upload.php",upImgExt:"jpg,jpeg,gif,png"}); 
	/*editor = KindEditor.create('textarea[name="comment_content"]', {
						langType : 'en',
						resizeTyle:1,
						items : [
						'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
						'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
						'insertunorderedlist', '|', 'image', 'link']
				});*/
	$('#comment_dialog').dialog({
			title:'Add comment...',
			iconCls:'icon-save',
			modal:true,
			closable:false,
			onClose:function(){
						$('#comment_dialog').remove();
						$('#com_dig').remove();
						},
								onOpen:function(){
									$('#comment_content').focus();
								},
								buttons:[{
									text:'Ok',
									iconCls:'icon-ok',
									handler:function(){
										//editor.sync();
										if (!$('#comment_content').val())
										{
											alert('Plese input some comments before change the status.');
											return;
										}
										if (confirm("Are you sure want to change the status to "+status+"?"))
											{
												
												$.ajax({ 
														type: "POST", 
														url: "buildability/change_status.php?part_number="+$("#part_number").html()+'&section_name='+title,
														data:{status:status,user_name: $("#loggedUserName").html(),part_number_id:$("#pn_id").html(),site:$("#site_text").html()},
														dataType: "json", 
														success: function(msg) { 
															var Result = msg.success;
															if (Result == false) {
															   alert(msg.message);  
															   return false;
															 }
															else 
															if (Result == true) {
																return true;
															}
														   } 
												  });
												//add note:
												$.ajax({ 
															type: "POST", 
															url: "buildability/create_note.php?part_number="+$("#part_number").html()+'&section_name='+title,
															data:{note: $('#comment_content').val(),user_name: $("#loggedUserName").html(),part_number_id:$("#pn_id").html(),site:$("#site_text").html()},
															dataType: "json", 
															success: function(msg) { 
																var Result = msg.success;
																if (Result == false) {
																   alert(msg.message);
																   return;
																 }
																else 
																if (Result == true) {
																	
																}
															   } 
												 });
												var date = new Date(); 
												//change html doc
												
												var note_index =new Number(this_dom.parent().parent().parent().parent().parent().parent().parent().find("#comments").find('h4:last').html()) + 1;
												var new_comment = '<div class="comment_note"> ' +
																	  '<div class="comment_note_title"><h5 style="float:left;">#</h5><h4 style="float:left;">'+note_index+'</h4><h3 style="float:left;">User Name:'+ $("#loggedUserName").html()+'</h3>'+
																	  '<div title="Delete note" style="float:right;" class="note_del_btn"></div>'+
																	  '<div title="Edit note" style="float:right;" class="note_edit_btn"></div>'+
																	  '<h3 style="float:right;">Time:'+date.pattern("yyyy-MM-dd hh:mm:ss")+'</h3></div> '+
																	  '<div class="this_note">'+
																	  $('#comment_content').val()+
																	  '</div></div>';
												
												this_dom.parent().parent().parent().parent().parent().parent().parent().find("#comments").append(new_comment);
												 
												
												this_dom.parent().parent().parent().parent().parent().find(".update_uname").html($("#loggedUserName").html());
												this_dom.parent().parent().parent().parent().parent().find(".update_upass").html(date.pattern("yyyy-MM-dd hh:mm:ss"));
												if (new_value =='Review Required')
												{
													this_dom.parent().css('background-color','red');
												} else if (new_value =='Not Needed')
												{
													this_dom.parent().css('background-color','gray');
												} else if (new_value =='Eng Complete')
												{
													this_dom.parent().css('background-color','yellow');
												} else if (new_value =='PT Completed')
												{
													this_dom.parent().css('background-color','yellowgreen');
												}
												this_dom.attr("title",new_value);
												$('#comment_dialog').dialog('close');

											} else
											{
												$("#"+id).val(old_value);
												$("#"+id).parent().parent().find("INPUT").val(old_value);
												$("#"+id).parent().parent().find("Li").each(function(){
													//alert($(this).html() + ' - ' + old_value);
														if ($(this).hasClass("selected"))
														{
															$(this).removeClass("selected");
														}
														if ($(this).html()==old_value)
														{
															
															$(this).addClass("selected");
														}
												});
											}
									}
								},{
									text:'No',
									iconCls:'icon-cancel',
									handler:function(){
										
										
										$("#"+id).val(old_value);
										$("#"+id).parent().parent().find("INPUT").val(old_value);
										$("#"+id).parent().parent().find("Li").each(function(){
											//alert($(this).html() + ' - ' + old_value);
												if ($(this).hasClass("selected"))
												{
													$(this).removeClass("selected");
												}
												if ($(this).html()==old_value)
												{
													
													$(this).addClass("selected");
												}
										});
										$('#comment_dialog').dialog('close');
									}
								}]
							});
		
	

}


$("#about").bind("click",function(){
	$('#about_window').window('open');

})

function close_about(){
	$('#about_window').window('close');
}

$("#print").live("click",function(){
	$('body').mask("Creating PDF file, please wait...");
	$.ajax({ 
			type: "POST", 
			url: "buildability/html2pdf.php",
			data:{url:window.location.href+"&html2pdf=pdf",part_number:$("#part_number").html()},
			dataType: "json", 
			success: function(msg) { 
				var Result = msg.success;
				if (Result == false) {
						$('body').unmask();
						alert(msg.message);  
						return false;
				}
				else 
					if (Result == true) {
						$('body').unmask();
						//var myurl = window.location.href;
						//myurl = myurl.substring(0,myurl.indexOf('index.php')-1);
						//window.open(myurl+'/buildability/temp/'+$("#part_number").html()+'-buildability.pdf');
						window.open('buildability/temp/'+$("#part_number").html()+'-buildability.pdf');
						return true;
						}
			} 
	});
})




/////////////////////////
jQuery.fn.extend({
	selectbox: function(options) {
		return this.each(function() {
			new jQuery.SelectBox(this, options);
		});
	}
});


/* pawel maziarz: work around for ie logging */
if (!window.console) {
	var console = {
		log: function(msg) { 
	 }
	}
}
/* */

jQuery.SelectBox = function(selectobj, options) {
	
	var opt = options || {};
	opt.inputClass = opt.inputClass || "selectbox";
	opt.containerClass = opt.containerClass || "selectbox-wrapper";
	opt.hoverClass = opt.hoverClass || "current";
	opt.currentClass = opt.selectedClass || "selected"
	opt.debug = opt.debug || false;

	var elm_id = selectobj.id;
	var active = -1;
	var inFocus = false;
	var hasfocus = 0;
	//jquery object for select element
	var $select = $(selectobj);

	// jquery container object
	var $container = setupContainer(opt);
	//jquery input object 
	var $input = setupInput(opt);
	// hide select and append newly created elements
	$select.hide().before($input).before($container);
	
	
	init();
	
	$input
	.click(function(){
		//alert($("#loggedUserName").html());
    if (!inFocus) {
		  $container.toggle();
		}
	})
	.focus(function(){
	   if ($container.not(':visible')) {
	       inFocus = true;
	       $container.show();
	   }
	})
	.keydown(function(event) {	   
		switch(event.keyCode) {
			case 38: // up
				event.preventDefault();
				moveSelect(-1);
				break;
			case 40: // down
				event.preventDefault();
				moveSelect(1);
				break;
			//case 9:  // tab 
			case 13: // return
				event.preventDefault(); // seems not working in mac !
				$('li.'+opt.hoverClass).trigger('click');
				break;
			case 27: //escape
			  hideMe();
			  break;
		}
	})
	.blur(function() {
		if ($container.is(':visible') && hasfocus > 0 ) {
			if(opt.debug) console.log('container visible and has focus')
		} else {
			hideMe();	
		}
	});

	function hideMe() { 
		hasfocus = 0;
		$container.hide(); 
	}
	
	function init() {
		$container.append(getSelectOptions($input.attr('id'))).hide();
		var width = $input.css('width');
		$container.width(width);
    }
	
	function setupContainer(options) {
		var container = document.createElement("div");
		$container = $(container);
		$container.attr('id', elm_id+'_container');
		$container.addClass(options.containerClass);
		
		return $container;
	}
	
	function setupInput(options) {
		var input = document.createElement("input");
		var $input = $(input);
		$input.attr("id", elm_id+"_input");
		$input.attr("type", "text");
		$input.addClass(options.inputClass);
		$input.attr("autocomplete", "off");
		$input.attr("readonly", "readonly");
		$input.attr("tabIndex", $select.attr("tabindex")); // "I" capital is important for ie
		
		return $input;	
	}
	
	function moveSelect(step) {
		var lis = $("li", $container);
		if (!lis) return;

		active += step;

		if (active < 0) {
			active = 0;
		} else if (active >= lis.size()) {
			active = lis.size() - 1;
		}

		lis.removeClass(opt.hoverClass);

		$(lis[active]).addClass(opt.hoverClass);
	}
	
	function setCurrent() {	
		var li = $("li."+opt.currentClass, $container).get(0);
		var ar = (''+li.id).split('_');
		var el = ar[ar.length-1];
		if($select.val() != el) {
            if (opt.debug) console.log('trigger change : '+ $select.attr('id') + ' changed from ' + $select.val() + ' to ' + el);
            
			$select.val(el);
			$select.change();
			
        }

		$input.val($(li).html());
		return true;
	}
	
	// select value
	function getCurrentSelected() {
		return $select.val();
	}
	
	// input value
	function getCurrentValue() {
		return $input.val();
	}
	
	function getSelectOptions(parentid) {
		var select_options = new Array();
		var ul = document.createElement('ul');
		$select.children('option').each(function() {
			var li = document.createElement('li');
			li.setAttribute('id', parentid + '_' + $(this).val());
			li.innerHTML = $(this).html();
			if ($(this).is(':selected')) {
				$input.val($(this).html());
				$(li).addClass(opt.currentClass);
			}
			if ($(this).html() =='Review Required')
			{
				$(li).css("background-color","red");
			} else if ($(this).html() =='Not Needed')
			{
				$(li).css("background-color","gray");
			} else if ($(this).html() =='Eng Complete')
			{
				$(li).css("background-color","yellow");
			} else if ($(this).html() =='PT Completed')
			{
				$(li).css("background-color","yellowgreen");
			}
			
			ul.appendChild(li);
			$(li)
			.mouseover(function(event) {
				hasfocus = 1;
				if (opt.debug) console.log('over on : '+this.id);
				jQuery(event.target, $container).addClass(opt.hoverClass);
			})
			.mouseout(function(event) {
				hasfocus = -1;
				if (opt.debug) console.log('out on : '+this.id);
				jQuery(event.target, $container).removeClass(opt.hoverClass);
			})
			.click(function(event) {
			  var fl = $('li.'+opt.hoverClass, $container).get(0);
				if (opt.debug) console.log('click on :'+this.id);

				var ar = (''+fl.id).split('_');
				var el = ar[ar.length-1];
				
				if($select.val() != el) {
					
					var role = $("#userRole").html();
			
					if (el == 'Not Needed' && role !='PT' && role !='Admin')
					{

						alert('Only system itself or PT can change the status to No Needed.');
						hideMe();
						return false;
					}
							
					if (role)
					{
						if (role == 'Engineer' && el== 'PT Completed' && role !='Admin')
						{
								alert('You can not change the status to '+el+'.');
								hideMe();
								return false;
						}
					}

					//check close
					if ($(".radio_close").find(":radio").attr("checked"))
					{
						alert('This buildability is "completed" and no updates can be made.');
						hideMe();
						return false;
					}
				}


				$('li.'+opt.currentClass).removeClass(opt.currentClass); 
				$(this).addClass(opt.currentClass);
				setCurrent();
				hideMe();
			});
		});
		return ul;
	}
	
};






