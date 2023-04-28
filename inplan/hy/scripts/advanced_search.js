$(document).ready(function(){

		$('.c_add_btn').live("click",
			function(){
				var line_tr = '<tr><td><select style="width:80px"><option>AND</option><option>OR</option></select></td><td><select class="c_feilds" style="width:200px">'+
							  ' 		<option>Job Name</option>'+
							  '			<option selected>Viasystems P/N</option>'+
							  '		 </select></td>'+
							  '	 <td><select style="width:80px">'+
							  '			<option>=</option>'+
							  '			<option>></option>'+
							  '			<option><</option>'+
							  '			<option><></option>'+
							  '			<option>>=</option>'+
							  '			<option><=</option>'+
							  '			<option>Like</option>'+
							  '			<option>Not Like</option>'+
							  '		 </select></td>'+
							  '	 <td><input type="text" style="width:200px"/></td>'+
							  '	 <td style="width:20px"><img class="c_add_btn" src="images/add.png" alt="Add one line."/></td>'+
							  '	 <td style="width:20px"><img class="c_del_btn" src="images/del.png" alt="Delete this line."/></td>'+
							  '</tr>';
				$('#criteria_table').append(line_tr);
		});

		$('.c_del_btn').live("click",
			function(){
				$(this).parent().parent().remove();
		
		});

		$('.c_feilds').live("click",
			function(){
				var select = $(this);
				if (select.find('option').length==2)
				{
					$('#dis_table').find('input').each(function(){
						var item_name= $(this).parent().find('label').html();
						if (item_name !='Job Name' && item_name !='Viasystems P/N')
						{
							select.append('<option>'+item_name+'</option>');
						}
					}
					);
				}
		
		});

		$('#c_do_search').live("click",
			function(){
			 var params = "params:'";
			  $('#dis_table input:checked').each(function(){
				  if (params =="params:'")
				  {
					params += $(this).parent().find('label').html();
				  } else {
					params += ','+$(this).parent().find('label').html();
				  }
				
			  });
			  params +="'";
				$.ajax({ 
                    type: "POST", 
                    url: "inplan/hy/get_adv_s_list.php?site="+$("#site_text").html(),
					data:params,
                    dataType: "json", 
                    success: function(msg) { 
						var Result = msg.success;
                        if (Result == false) {
                           alert(msg.message);
						   return;
                         }
                        else 
                        if (Result == true) {
							$('#c_result').css("display","block");
							if ($('#result_table').find('tr').length>0)
							{
								$('#result_table').find('tr').remove();
							}
							$('#result_count').html('Total result count: '+msg.count);
							$('#result_table').append(msg.message);
							$('#result_table').append("<tr><td colspan="+msg.column_count+">Display more...</td></tr>");
                        }
                       } 
         });	
		});

		
});
