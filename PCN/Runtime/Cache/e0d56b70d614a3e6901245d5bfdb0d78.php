<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
 
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta http-equiv="content-type" content="txt/html; charset=utf-8" />
  <title>Add New Recorder</title>

  <!-- Bootstrap core CSS --> 
  <link rel="stylesheet" href="__TMPL__assets/dist/css/bootstrap.min.css">
  <!-- Custom styles for this template -->
  <link href="__TMPL__assets/css/simple-sidebar.css" rel="stylesheet">
  <!--link href="__TMPL__assets/font-awesome/css/font-awesome.min.css" rel="stylesheet"-->
  <!--<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"></script>-->
  <link href="__TMPL__/assets/fontawesome/css/all.css" rel="stylesheet">
  <link href="__TMPL__/assets/css/pcn.css" rel="stylesheet">
  <link href="__TMPL__assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
  <link href="__TMPL__assets/fileInput/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
  <link href="__TMPL__assets/fileInput/themes/explorer-fas/theme.css" media="all" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Open+Sans">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link href="__TMPL__/assets/js/jquery-ui.min.css" rel="stylesheet">
  <link href="__TMPL__/assets/js/jquery-ui.theme.css" rel="stylesheet">
 <style>
    .ui-autocomplete { z-index:2147483647; }
 </style>

</head>

<body>

   <div class="d-flex" id="wrapper">
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">
      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">      
        <!--span class="navbar-toggler-icon" id="menu-toggle">&#128263;</span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span!-->
        <div class="collapse navbar-collapse " id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto mt-2 mt-lg-0 " >
            <li class="nav-item active">
              <a class="nav-link" href="__APP__/Admin/index"><i class="fa fa-home"></i><?php echo (L("home")); ?> </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#"><i class="fas fa-user" style="font-size: 18px;"></i><?php echo ($userName); ?></a>
            </li>
          </ul>
        </div>

      </nav>	
	
		<!--hr style='margin-top:-3px;'-->
			  <div class="container">
			    <div class="row" >
					<div class="col-sm">
					 <img src="__TMPL__logo.png" class="card-img-top" style="width:50%;height: auto;">
					</div>
					<div class="col-sm" style='font-size:15px;'>					
					  <br>Guangzhou Termbray Electronics Technology Company Limited<br>
                      广州添利电子科技有限公司<br>
                      A member of TTM Technologies Group					
					</div>
				 </div>	
				  <p class="text-center font-weight-bold" style='font-size:30px;margin—top:50px;'>Process Change Notice Auth</p>
			     <form action='__URL__/save' method='post' style='margin-top:0;' enctype="multipart/form-data" class="needs-validation" novalidate>
				      <div class="form-group row"  style='border-top:1px solid black; font-size:20px;'>
						   <label for="usert_name" class="col-sm-2 col-form-label-sm"><?php echo (L("user_name")); ?> :</label>
						   <input type="text" class="form-control col-form-label-sm col-sm-10" id="user_name" name='user_name' placeholder="<?php echo (L("user_name")); ?>" style='margin-top:10px;' required>	
					  </div>
					   <div class="form-group row"  >
						   <label for="user_pwd" class="col-sm-2 col-form-label-sm"><?php echo (L("user_pwd")); ?> :</label>
						   <input type="password" class="form-control col-form-label-sm col-sm-10" id="user_pwd" name='user_pwd' placeholder="<?php echo (L("user_pwd")); ?>" style='margin-top:10px;'>					
					  </div>
					   <div class="form-group row"  >
						   <label for="role" class="col-sm-2 col-form-label-sm"><?php echo (L("role")); ?> :</label>
						    <select  class="form-control col-sm-10" id="role" name='role'>
								  <option></option>
								  <option>admin</option>
								  <option>general</option>
								  <option>guest</option>
							</select>				
					  </div>  
					   <div class="form-group row"  >
						   <label for="status" class="col-sm-2 col-form-label-sm"><?php echo (L("status")); ?> :</label>
						   <select  class="form-control col-sm-10" id="status" name='status'>
								  <option>Valid</option>
								  <option>Invalid</option>
							</select>						
					  </div>    
					  <div class="form-group row"  >
						   <label for="status" class="col-sm-2 col-form-label-sm"><?php echo (L("access_rule")); ?> :</label>			
					  </div>    
					   <div class="form-group row"  >
						       <div class="form-check form-check-inline">
								  <label class="form-check-label">
									<input type="checkbox" id='new' name='access_rule[]' class="form-check-input" value="add"><?php echo (L("new")); ?>
								  </label>
								</div>
								<div class="form-check form-check-inline">
								  <label class="form-check-label">
									<input type="checkbox" id='eidt' name='access_rule[]' class="form-check-input" value="edit"><?php echo (L("edit")); ?>
								  </label>
								</div>
								<div class="form-check form-check-inline disabled">
								  <label class="form-check-label">
									<input type="checkbox" id='copy' name='access_rule[]' class="form-check-input" value="copy_rec" ><?php echo (L("copy")); ?>
								  </label>
								</div> 
								<div class="form-check form-check-inline disabled">
								  <label class="form-check-label">
									<input type="checkbox" id='split_rec' name='access_rule[]' class="form-check-input" value="split_rec" ><?php echo (L("split")); ?>
								  </label>
								</div>  
								<div class="form-check form-check-inline disabled">
								  <label class="form-check-label">
									<input type="checkbox" id='dele' name='access_rule[]' class="form-check-input" value="del" ><?php echo (L("dele")); ?>
								  </label>
								</div>  
								<div class="form-check form-check-inline">
								  <label class="form-check-label">
									<input type="checkbox" id='pe' name='access_rule[]' class="form-check-input" value="pe"><?php echo (L("pe")); ?>
								  </label>
								</div>
								<div class="form-check form-check-inline">
								  <label class="form-check-label">
									<input type="checkbox" id='pre' name='access_rule[]' class="form-check-input" value="pre" ><?php echo (L("pre")); ?>
								  </label>
								</div>
								<div class="form-check form-check-inline disabled">
								  <label class="form-check-label">
									<input type="checkbox" id='aq'  name='access_rule[]' class="form-check-input" value="qa" ><?php echo (L("qa")); ?>
								  </label>
								</div>  
								<div class="form-check form-check-inline">
								  <label class="form-check-label">
									<input type="checkbox" id='engd' name='access_rule[]'  class="form-check-input" value="engd"><?php echo (L("engd")); ?>
								  </label>
								</div>
								<div class="form-check form-check-inline">
								  <label class="form-check-label">
									<input type="checkbox" id='mfgd' name='access_rule[]' class="form-check-input" value="mfgd" ><?php echo (L("mfgd")); ?>
								  </label>
								</div>
								<div class="form-check form-check-inline disabled">
								  <label class="form-check-label">
									<input type="checkbox" id='aqd' name='access_rule[]' class="form-check-input" value="qad" ><?php echo (L("qad")); ?>
								  </label>
								</div>  
								<div class="form-check form-check-inline">
								  <label class="form-check-label">
									<input type="checkbox" id='spcn' name='access_rule[]' class="form-check-input" value="spcn"><?php echo (L("spcn")); ?>
								  </label>
								</div>
								<div class="form-check form-check-inline">
								  <label class="form-check-label">
									<input type="checkbox" id='ccomp'  name='access_rule[]' class="form-check-input" value="ccomp" ><?php echo (L("ccomp")); ?>
								  </label>
								</div>
								<div class="form-check form-check-inline disabled">
								  <label class="form-check-label">
									<input type="checkbox" id='pee'  name='access_rule[]' class="form-check-input" value="pee" ><?php echo (L("pee")); ?>
								  </label>
								</div>  
								<div class="form-check form-check-inline disabled">
								  <label class="form-check-label">
									<input type="checkbox" id='approval'  name='access_rule[]' class="form-check-input" value="approval" >approval
								  </label>
								</div>  
								<div class="form-check form-check-inline disabled">
								  <label class="form-check-label">
									<input type="checkbox" id='view_detail'  name='access_rule[]' class="form-check-input" value="view_detail" >view detail
								  </label>
								</div>  
								<div class="form-check form-check-inline disabled">
								  <label class="form-check-label">
									<input type="checkbox" id='pe_exec'  name='access_rule[]' class="form-check-input" value="pe_exec" >pe exec
								  </label>
								</div>  
								<div class="form-check form-check-inline disabled">
								  <label class="form-check-label">
									<input type="checkbox" id='save'  name='access_rule[]' class="form-check-input" value="save" >save
								  </label>
								</div>  
								<div class="form-check form-check-inline disabled">
								  <label class="form-check-label">
									<input type="checkbox" id='export_excel'  name='access_rule[]' class="form-check-input" value="export_excel" >export excel 
								  </label>
								</div> 
								<div class="form-check form-check-inline disabled">
								  <label class="form-check-label">
									<input type="checkbox" id='update'  name='access_rule[]' class="form-check-input" value="update" >update
								  </label>
								</div>  
					  </div>    
	               <div class="form-group row"  style='border-top:1px solid black;' >
                              <div class='col-sm-9'  >
							  </div>
							 <div class='col-sm-2'  >
							     <button type="submit" class="btn btn-info float-right" onclick="myform.submit()" style='margin-top:10px;'>Submit</button>
							 </div>
							 <div class='col-sm-1'>								
								 <button type="reset" class="btn btn-outline-secondary float-right" style='margin-top:10px;'>Reset</button>							
						    </div>
				 </div>               
				</form>


	 </div>
	  <!-- /#wrapper -->

  <!-- Bootstrap core JavaScript -->
	<script src="__TMPL__assets/js/jquery-3.4.1.js"></script>
	<script src="__TMPL__assets/js/popper.min.js"></script>
	<script src="__TMPL__assets/js/jquery-ui.min.js"></script>
	<script src="__TMPL__assets/dist/js/bootstrap.min.js"></script>
	<script src="__TMPL__assets/dist/js/polyfill.min.js"></script> <!-- resolve IE11 Promise-->
	<script src="__TMPL__assets/fileInput/js/plugins/piexif.js" type="text/javascript"></script>
	<script src="__TMPL__assets/fileInput/js/plugins/purify.js" type="text/javascript"></script>
	<script src="__TMPL__assets/fileInput/js/plugins/sortable.js" type="text/javascript"></script>
	<script src="__TMPL__assets/fileInput/js/fileinput.js" type="text/javascript"></script>
	<script src="__TMPL__assets/fileInput/js/locales/fr.js" type="text/javascript"></script>
	<script src="__TMPL__assets/fileInput/js/locales/es.js" type="text/javascript"></script>
	<script src="__TMPL__assets/fileInput/themes/fas/theme.js" type="text/javascript"></script>
	<script src="__TMPL__assets/fileInput/themes/explorer-fas/theme.js" type="text/javascript"></script>

	<!--<script src="https://cdn.jsdelivr.net/gh/xcash/bootstrap-autocomplete@v2.2.2/dist/latest/bootstrap-autocomplete.min.js"></script>-->
	
  
  <!-- Menu Toggle Script -->

</body>
<script>
  /*Check Valide*/
		(function() {
			  'use strict';
			  window.addEventListener('load', function() {
				// Fetch all the forms we want to apply custom Bootstrap validation styles to
				var forms = document.getElementsByClassName('needs-validation');
				// Loop over them and prevent submission
				var validation = Array.prototype.filter.call(forms, function(form) {
				  form.addEventListener('submit', function(event) {
					if (form.checkValidity() === false) {
					  event.preventDefault();
					  event.stopPropagation();
					}
					form.classList.add('was-validated');
				  }, false);
				});
			  }, false);
			})();
    /*Check Valide end*/
   var status = [ 'valid','invalid'];
   $( "#status" ).autocomplete({
      source: status
    });
	var roles = ['','admin','general','guest'];
   $( "#role" ).autocomplete({
      source: roles
    });
	

  

</script>

</html>