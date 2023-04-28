$(document).ready(function(){

	if ($("#html2pdf").html()!=''){
		$("#setting_table").hide();
		//$(".drill").hide();
		//$(".div_prepreg").hide();
	}
	$("#div_drill").css('left',$(".header").offset().left);
	$("#div_drill").css('top',$(".header").offset().top);
	$(".div_prepreg").css('left',$("img:first").offset().left);
	$(".div_prepreg").css('top',$("img:first").offset().top);
	$(".div_prepreg").css("height",$("#stackup_table tr:last").offset().top - $("img:first").offset().top+10);
	$(".div_prepreg").css("width",$("#img_1").width());


	var loop_index=1;
	var left = $("img:first").offset().left+23;

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


	$(window).resize(function() {
		location.reload();
	});

	$("#refresh").bind("click",
	function() {
		var pos = window.location.href.indexOf('job_name');
		var end = window.location.href.substring(pos,window.location.href.length);
		var begin = "";
		if (end.indexOf('&')!=-1)
		{
			begin = end.substring(0,end.indexOf('&'));
		} else {
			begin = end;
		}

		var url = window.location.href.substring(0,pos)+begin;
		
		var new_url = url;
		
		
		if ($("#core_rd").attr("checked"))
		{
			new_url += "&corerd=checked";
		}
		
		if ($("#pp_fn").attr("checked"))
		{
			new_url += "&pp_fn=checked";
		}
		if ($("#pp_rs").attr("checked"))
		{
			new_url += "&pp_rs=checked";
		}

		
		if ($("#tg").attr("checked"))
		{
			new_url += "&tg=checked";
		}
		if ($("#dk").attr("checked"))
		{
			new_url += "&dk=checked";
		}

		//alert(new_url);
		window.location.href=new_url;
		
    }
	);

});

(function(a){a.fn.mask=function(c,b){a(this).each(function(){if(b!==undefined&&b>0){var d=a(this);d.data("_mask_timeout",setTimeout(function(){a.maskElement(d,c)},b))}else{a.maskElement(a(this),c)}})};a.fn.unmask=function(){a(this).each(function(){a.unmaskElement(a(this))})};a.fn.isMasked=function(){return this.hasClass("masked")};a.maskElement=function(d,c){if(d.data("_mask_timeout")!==undefined){clearTimeout(d.data("_mask_timeout"));d.removeData("_mask_timeout")}if(d.isMasked()){a.unmaskElement(d)}if(d.css("position")=="static"){d.addClass("masked-relative")}d.addClass("masked");var e=a('<div class="loadmask"></div>');if(navigator.userAgent.toLowerCase().indexOf("msie")>-1){e.height(d.height()+parseInt(d.css("padding-top"))+parseInt(d.css("padding-bottom")));e.width(d.width()+parseInt(d.css("padding-left"))+parseInt(d.css("padding-right")))}if(navigator.userAgent.toLowerCase().indexOf("msie 6")>-1){d.find("select").addClass("masked-hidden")}d.append(e);if(c!==undefined){var b=a('<div class="loadmask-msg" style="display:none;"></div>');b.append("<div>"+c+"</div>");d.append(b);b.css("top",Math.round(d.height()/2-(b.height()-parseInt(b.css("padding-top"))-parseInt(b.css("padding-bottom")))/2)+"px");b.css("left",Math.round(d.width()/2-(b.width()-parseInt(b.css("padding-left"))-parseInt(b.css("padding-right")))/2)+"px");b.show()}};a.unmaskElement=function(b){if(b.data("_mask_timeout")!==undefined){clearTimeout(b.data("_mask_timeout"));b.removeData("_mask_timeout")}b.find(".loadmask-msg,.loadmask").remove();b.removeClass("masked");b.removeClass("masked-relative");b.find("select").removeClass("masked-hidden")}})(jQuery);


$("#output").live("click",function(){
		$('.header').mask("Creating PDF file, please wait...");
		$.ajax({ 
				type: "POST", 
				url: "../../inplan/html2pdf.php",
				data:{url:window.location.href+"&html2pdf=pdf",part_number:$("#part_number").html()},
				dataType: "json", 
				success: function(msg) { 
					var Result = msg.success;
					if (Result == false) {
							$('.header').unmask();
							alert(msg.message);  
							return false;
					}
					else 
						if (Result == true) {
							$('.header').unmask();
							window.open('../../inplan/temp/'+$("#part_number").html()+'-stackup.pdf');
							return true;
							}
				} 
		});
	});