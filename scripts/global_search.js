$(document).ready(function(){

	var action = $("#search_sub_tag").html().toLowerCase();
	var kw = request("global_kw");

		if (action == 'specification')
		{
			prepareSpecGrid(action,kw);
		} else if (action == 'inplan')
		{
			prepareTravellerGrid(action,kw);
		} else if (action == 'tq')
		{
			prepareTQGrid(action,kw);
		}
		 else if (action == 'genesis')
		{
			//prepareCamGrid(action);
			$(".grid").append("Comming soon...");
		}
		
	//$('.pSearch').click();
});

function request(paras)
    { 
        var url = location.href; 
        var paraString = url.substring(url.indexOf("?")+1,url.length).split("&"); 
        var paraObj = {} 
        for (i=0; j=paraString[i]; i++){ 
        paraObj[j.substring(0,j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf("=")+1,j.length); 
        } 
        var returnValue = paraObj[paras.toLowerCase()]; 
        if(typeof(returnValue)=="undefined"){ 
        return ""; 
        }else{ 
        return returnValue; 
        } 
    }
	

function prepareSpecGrid(action,kw){
	var auto_load = true;
	if (kw)
	{
		auto_load = false;
	}
	$("#global_grid").flexigrid
				(
				{
				url: 'getgloballist.php?site='+$("#site_text").html()+'&action='+action+'&kw='+kw,
				dataType: 'json',
				//checkBoxSelect: true,
				showZero : false,
				colModel : [
				    
					{display: 'Spec Name', name : 'item_name', width : 300, hide : false, align: 'left',needOrder:false },
					{display: 'Description', name : 'description', width : 350, hide : false, align: 'left',needOrder:false},
					{display: 'Priority', name : 'priority', width : 80, hide : false, align: 'left',needOrder:false},
					{display: 'Site', name : 'site', width : 80, hide : false, align: 'left',needOrder:false}
					],
				onRowDblclick:procMe,
				usepager: true,
				filter: true,
				keyFilter:'item_name',
				searchitems : [
									{display: 'Spec Name', name : 'item_name',isdefault: true,operater:'Like'},
									{display: 'Description', name : 'description', operater:'Like'},
									{display: 'Site', name : 'site', operater:'Like'}
							   ],
				autoload: auto_load,
				rp: 10000,
				useRp:true,
				width: 930,
				height: 380
				}
	);
	
	if (auto_load == false)
	{
		$('.sDiv2 input:first').val(kw);
		$('#top_search_txt').val(kw)
		$('.sDiv2 input:[value=Search]').trigger("click");

	}

}

function prepareTravellerGrid(action,kw){
	var auto_load = true;
	if (kw)
	{
		auto_load = false;
	}
	$("#global_grid").flexigrid
				(
				{
				url: 'getgloballist.php?site='+$("#site_text").html()+'&action='+action+'&kw='+kw,
				dataType: 'json',
				//checkBoxSelect: true,
				showZero : false,
				colModel : [
				    
					{display: 'Job Name', name : 'item_name', width : 150, hide : false, align: 'left',needOrder:true },
					{display: 'Mrp Name', name : 'mrp_name', width : 150, hide : false, align: 'left',needOrder:true },
					{display: 'Customer P/N', name : 'name', width : 150, hide : false, align: 'left',needOrder:true},
					{display: 'Layer Count', name : 'num_layers', width : 80, hide : false, align: 'left',needOrder:true},
					{display: 'Site', name : 'site', width : 80, hide : false, align: 'left',needOrder:true}
					],
				onRowDblclick:procMe,
				usepager: true,
				filter: true,
				keyFilter:'item_name',
				searchitems : [
									{display: 'Job Name', name : 'item_name',isdefault: true,operater:'Like'},
									{display: 'Mrp Name', name : 'mrp_name',isdefault: true,operater:'Like'},
									{display: 'Customer P/N', name : 'name',isdefault: true,operater:'Like'},
									{display: 'Layer Count', name : 'num_layers', operater:'Like'},
									{display: 'Site', name : 'site', operater:'Like'}
							   ],
				autoload: auto_load,
				rp: 10000,
				useRp:true,
				width: 930,
				height: 380
				}
	);

	if (auto_load == false)
	{
		$('.sDiv2 input:first').val(kw);
		$('#top_search_txt').val(kw)
		$('.sDiv2 input:[value=Search]').trigger("click");

	}
} 

function prepareTQGrid(action,kw){
	var auto_load = true;
	if (kw)
	{
		auto_load = false;
	}
	$("#global_grid").flexigrid
				(
				{
				url: 'tq/getlist.php?site='+$("#site_text").html()+'&action='+action+'&global=true',
				dataType: 'json',
				//checkBoxSelect: true,
				showZero : false,
				colModel : [
					{display: 'Customer Name', name : 'customer_name', width : 120, hide : false, align: 'left',needOrder:true},
					{display: 'Customer P/N', name : 'customer_pn', width : 150, hide : false, align: 'left',needOrder:true},
					{display: 'Customer Rev', name : 'customer_rev', width : 80, hide : false, align: 'left',needOrder:true},
					{display: 'Job Name', name : 'site_tooling_pn', width : 100, hide : false, align: 'left',needOrder:true },
					{display: 'Mrp Name', name : 'viasystems_pn', width : 100, hide : false, align: 'left',needOrder:true },
					{display: 'Received Date', name : 'received_date', width : 150, hide : false, align: 'left',needOrder:true},
					{display: 'Site Name', name : 'site_name', width : 80, hide : false, align: 'left',needOrder:true}
					],
				onRowDblclick:procMe,
				usepager: true,
				filter: true,
				keyFilter:'item_name',
				searchitems : [		
									{display: 'Customer P/N', name : 'customer_pn',isdefault: true,operater:'Like'},
									{display: 'Customer Name', name : 'customer_name',isdefault: true,operater:'Like'},
									{display: 'Job Name', name : 'site_tooling_pn',isdefault: true,operater:'Like'},
									{display: 'Mrp Name', name : 'viasystems_pn',isdefault: true,operater:'Like'},
									{display: 'Received Date', name : 'received_date', operater:'Like'}
							   ],
				autoload: auto_load,
				rp: 15,
				useRp:true,
				width: 930,
				height: 380
				}
	);

	if (auto_load == false)
	{
		$('.sDiv2 input:first').val(kw);
		$('#top_search_txt').val(kw)
		$('.sDiv2 input:[value=Search]').trigger("click");

	}
} 

function procMe(rowData){
	$('body').mask("Loading...");
	var action = $("#search_sub_tag").html().toLowerCase();
	var server_name = $('#server_'+site).html();

	if (action =='specification')
	{	var site = $.trim($(rowData).data("site").toString());
		if ($("#lang_code").html() == 'zh-cn')
		{
			window.location.href='http://'+server_name+'/feeweb/index.php?site='+site+'&action=specification&spec_name='+$.trim($(rowData).data("item_name").toString())+'&lang=zh-cn';
		} 
		else {
			window.location.href='http://'+server_name+'/feeweb/index.php?site='+site+'&action=specification&spec_name='+$.trim($(rowData).data("item_name").toString());
		}
		
	} else if (action =='inplan')
	{
		var site = $.trim($(rowData).data("site").toString());
		if ($("#lang_code").html() == 'zh-cn')
		{
			window.location.href='http://'+server_name+'/feeweb/index.php?site='+site+'&action=inplan&data=Job Attributes&job_name='+$.trim($(rowData).data("item_name").toString())+'&lang=zh-cn';
		} else 
		{
			window.location.href='http://'+server_name+'/feeweb/index.php?site='+site+'&action=inplan&data=Job Attributes&job_name='+$.trim($(rowData).data("item_name").toString());
		}
		
	} 
	else if (action =='genesis')
	{
		var site = $.trim($(rowData).data("site").toString());
		if ($("#lang_code").html() == 'zh-cn')
		{
			window.location.href='http://'+server_name+'/feeweb/index.php?site='+site+'&action=genesis&t_code='+$.trim($(rowData).data("item_name").toString())+'&lang=zh-cn';
		} else {
			window.location.href='http://'+server_name+'/feeweb/index.php?site='+site+'&action=genesis&t_code='+$.trim($(rowData).data("item_name").toString());
		}
		
	}  else if (action =='tq')
	{
		var site = $.trim($(rowData).data("site_name").toString());
		var job_name = $.trim($(rowData).data("customer_pn").toString());
		if ($.trim($(rowData).data("customer_rev").toString())!='')
		{
			job_name += ' Rev ' + $.trim($(rowData).data("customer_rev").toString());
		}
		var url = 'index.php?site='+site+'&action=tq'+'&job_name='+job_name;
		url = url + '&data=TQ&tq_pn=yes';
		if ($("#lang_code").html() == 'zh-cn')
		{
			window.location.href=url+'&lang=zh-cn';
		} else 
		{
			window.location.href=url;
		}
	}
}
