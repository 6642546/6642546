$(document).ready(function(){
	if ($('#layer_1').length >0 )
	{
		var pp_left = $("#stackup_table img:first").offset().left;
	if ($("#html2pdf").html()!=''){
		$("#setting_table").hide();
		//$(".div_prepreg").hide();
		$(".header").css("width",800);
		//$(".prepreg_bg").css("background-image","url(stackup/prepreg/prepreg_back.png)");
		//$(".prepreg_bg").css("width","100%");
		if (request('site') == 'HY' || request('site') == 'HZ')
		{
			pp_left = pp_left -8-25-1;
		} else if (request('site') == 'FG' || request('site') == 'SJ')
		{
			if (request('erp')!='')
			{
				pp_left = pp_left +15+25;
			} else {
				pp_left = pp_left+15+25;
			}
		}
		
		$(".div_prepreg").css("border","solid 0px black");
		$(".div_prepreg").css("width",148);
	}
	//$(".div_drill").css('left',$(".header").offset().left);
	//$(".div_drill").css('top',$(".header").offset().top);
	$(".div_prepreg").css('left',pp_left );
	$(".div_prepreg").css('top',$("#stackup_table img:first").offset().top);
	$(".div_prepreg").css("height",$("#stackup_table tr:last").offset().top - $("#stackup_table img:first").offset().top+10);
	$(".div_prepreg").css("width",148);
	//$(".div_prepreg").css("_width",150);
	
	var loop_index=1;
	var left = $("#stackup_table img:first").offset().left+23;
	if ($("#html2pdf").html()!=''){
		if (request('site') == 'HY' || request('site') == 'HZ'){
			left = left - 23 - 25 -25 -1  ;
		} else if (request('site') == 'FG' || request('site') == 'SJ')
		{
			if (request('erp')!='checked')
			{
				//left = left - 23 - 25 -25 -1  ;
			} 
		}
	}

	$(".drill").each(function(){
		var start_layer = $(this).attr("start_layer");
		var end_layer = $(this).attr("end_layer");

		if (parseInt(start_layer) > parseInt(end_layer))
		{
			var tmp = end_layer;
			end_layer = start_layer;
			start_layer = tmp;
		} 
		if ($(".drill").length<=5 )
		{
			$(this).css('left',left+(loop_index-1)*25);
		} else {
			$(this).css('left',left+(loop_index-1)*12);
		}
		
		$(this).css('top',$("#layer_"+start_layer).offset().top+7);
		$(this).css('height',$("#layer_"+end_layer).offset().top -$("#layer_"+start_layer).offset().top + $("#layer_"+end_layer).height()-13);
		
		loop_index+=1;
	}
	
	)
	
	$(".drill").show();

	} else {
		$(".div_prepreg").hide();
	}
	


	$(window).resize(function() {
		location.reload();
	});

	$("#refresh").bind("click",
	function() {
		//var pos = window.location.href.indexOf('job_name');
		//var end = window.location.href.substring(pos,window.location.href.length);
		//var begin = "";
		//if (end.indexOf('&')!=-1)
		//{
		//	begin = end.substring(0,end.indexOf('&'));
		//} else {
		//	begin = end;
		//}

		var url = 'http://'+$("#server_"+$("#site_text").html()).html()+'/feeweb/index.php?site='+$("#site_text").html()+'&action=inplan&job_name='+request('job_name')+'&data=Stackup';
		if (request('lang')!='')
		{
			url += '&lang='+request('lang');
		}
		
		var new_url = url;
		
		
		if ($("#core_rd").attr("checked") && new_url.indexOf('corerd')==-1)
		{
			new_url += "&corerd=checked";
		}
		
		if ($("#pp_fn").attr("checked") && new_url.indexOf('pp_fn')==-1)
		{
			new_url += "&pp_fn=checked";
		}
		if ($("#pp_rs").attr("checked") && new_url.indexOf('pp_rs')==-1)
		{
			new_url += "&pp_rs=checked";
		}

		if ($("#erp").attr("checked") && new_url.indexOf('erp')==-1)
		{
			new_url += "&erp=checked";
		}
		if ($("#tg").attr("checked") && new_url.indexOf('tg')==-1)
		{
			new_url += "&tg=checked";
		}
		if ($("#dk").attr("checked") && new_url.indexOf('dk')==-1)
		{
			new_url += "&dk=checked";
		}

		//alert(new_url);
		window.location.href=new_url;
		
    }
	);

});

(function(a){a.fn.mask=function(c,b){a(this).each(function(){if(b!==undefined&&b>0){var d=a(this);d.data("_mask_timeout",setTimeout(function(){a.maskElement(d,c)},b))}else{a.maskElement(a(this),c)}})};a.fn.unmask=function(){a(this).each(function(){a.unmaskElement(a(this))})};a.fn.isMasked=function(){return this.hasClass("masked")};a.maskElement=function(d,c){if(d.data("_mask_timeout")!==undefined){clearTimeout(d.data("_mask_timeout"));d.removeData("_mask_timeout")}if(d.isMasked()){a.unmaskElement(d)}if(d.css("position")=="static"){d.addClass("masked-relative")}d.addClass("masked");var e=a('<div class="loadmask"></div>');if(navigator.userAgent.toLowerCase().indexOf("msie")>-1){e.height(d.height()+parseInt(d.css("padding-top"))+parseInt(d.css("padding-bottom")));e.width(d.width()+parseInt(d.css("padding-left"))+parseInt(d.css("padding-right")))}if(navigator.userAgent.toLowerCase().indexOf("msie 6")>-1){d.find("select").addClass("masked-hidden")}d.append(e);if(c!==undefined){var b=a('<div class="loadmask-msg" style="display:none;"></div>');b.append("<div>"+c+"</div>");d.append(b);b.css("top",Math.round(d.height()/2-(b.height()-parseInt(b.css("padding-top"))-parseInt(b.css("padding-bottom")))/2)+"px");b.css("left",Math.round(d.width()/2-(b.width()-parseInt(b.css("padding-left"))-parseInt(b.css("padding-right")))/2)+"px");b.show()}};a.unmaskElement=function(b){if(b.data("_mask_timeout")!==undefined){clearTimeout(b.data("_mask_timeout"));b.removeData("_mask_timeout")}b.find(".loadmask-msg,.loadmask").remove();b.removeClass("masked");b.removeClass("masked-relative");b.find("select").removeClass("masked-hidden")}})(jQuery);

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