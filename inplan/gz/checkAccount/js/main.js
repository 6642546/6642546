
(function ($) {
    "use strict";

    /*==================================================================
    [ Focus Contact2 ]*/
    $('.input100').each(function(){
        $(this).on('blur', function(){
            if($(this).val().trim() != "") {
                $(this).addClass('has-val');
            }
            else {
                $(this).removeClass('has-val');
            }
        })    
    })

    /*==================================================================
    [ Validate ]*/
    var input = $('.validate-input .input100');

    $('.validate-form').on('submit',function(){
        var check = true;

        for(var i=0; i<input.length; i++) {
            if(validate(input[i]) == false){
                showValidate(input[i]);
                check=false;
            }
        }

        return check;
    });


    $('.validate-form .input100').each(function(){
        $(this).focus(function(){
           hideValidate(this);
        });
    });

    function validate (input) {
        if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
            if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
                return false;
            }
        }
        else {
            if($(input).val().trim() == ''){
                return false;
            }
        }
    }

    function showValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).addClass('alert-validate');
    }

    function hideValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).removeClass('alert-validate');
    }
   $('.login100-form-btn').click(function(){
    var  userName=$("input[name='username']").val();
	var  userPwd=$("input[name='pass']").val();
	 //alert(userName+'   '+userPwd);
	  $.ajax({
				 type: "POST",
				 url: "./checkLog.php",
				 data: {user:userName, pwd:userPwd},
				 dataType: "json",
			    // contentType: "application/json; charset=utf-8",
				 success: function(data, textStatus){
							/* $('#resText').empty();   //���resText�������������
							 var html = ''; 
							 $.each(data, function(commentIndex, comment){
								   html += '<div class="comment"><h6>' + comment['username']
											 + ':</h6><p class="para"' + comment['content']
											 + '</p></div>';
							 });
							 $('#resText').html(html);JSON.stringify*/
							console.log(textStatus);							
							if (data.result==1){
							  //location.href("./../../../index.php");
							  location.href="http://10.120.1.243/feeweb/index.php";
							  // $.session.set("test",'1');
							}else if(data.result==0){
							   location.href="http://10.120.1.243/feeweb/index.php";
                            }else if(data.result==2){
                                   alert('Unauthorized access, please contact administrator, login failed!');
						 	}else{
							   alert('Please,Recheck your account&password, login failed!');
							 }
                              
						  },
               error:function(XMLHttpRequest, textStatus, errorThrown){
						  alert(errorThrown);
						  }
			 });
	   
	   }) 

})(jQuery);