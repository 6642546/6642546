$(document).ready(function(){
	//alert(window.location.href);
	});


function submit_ok() {
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
		var new_url = url+"&data=Job Rev Compare";
		new_url+="&first_job="+document.getElementById('first_job').value+"&second_job="+document.getElementById('second_job').value;
	    var f_job=document.getElementById('first_job').value;
		var s_job=document.getElementById('second_job').value;
	    //  alert(f_job.substring(1,7));
		//alert(s_job.substring(1,7));
		if (f_job.substring(1,2)==s_job.substring(1,2))
	    {
			window.location.href=new_url;
	     } else {
	    	 alert("只能是同一P/N不同版本比较！");
		 }
};

function reset() {
	document.getElementById('first_job').value='';
	document.getElementById('second_job').value='';
}