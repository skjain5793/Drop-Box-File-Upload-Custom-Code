<? 
	session_set_cookie_params(7200);
	session_start(); 
	
	/*
		echo '<pre>';
		print_r($_SESSION);
		echo '</pre>';
		
	*/
	
	if (isset($_SESSION['initiated'])) { 
		
		if ($_SESSION['user_level'] == 2 || $_SESSION['user_level'] == 11  || $_SESSION['user_level'] == 1  || $_SESSION['user_level'] == 4 || $_SESSION['user_level'] == 5 ) { 
			
			//Make sure this is Master account
			
			//display page 
			
			//include("header.inc.php");
			
			$page_title = "User Admin Page : Rater Report";
			
			include("dbinfo.inc.php");
			
			include("header_db.inc.php");
			
			// rater status dropdown entries
			
			$query="SELECT DISTINCT raterstatus FROM users WHERE raterstatus <> '' ORDER BY raterstatus";
			
			$result_status=mysql_query($query);
			
			$num_status=mysql_numrows($result_status);
			
			$project_id = $_GET['project_id'];
			
			if ($_SESSION['user_level'] == 2 || $_SESSION['user_level'] == 11 || $_SESSION['user_level'] == 1 || $_SESSION['user_level'] == 4 || $_SESSION['user_level'] == 5) {
				$query="SELECT rater_id FROM projectsandratings WHERE id = '".$project_id."'";
				$result_raterid=@mysql_query($query);
				$rater_id=@mysql_result($result_raterid,0,"rater_id");
				
				
				} else {
				$rater_id=$_SESSION['user_id'];
			}
			mysql_close();
		?>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		
		<style>
			
			form {
				//position: absolute;
				position: relative;
				//top: 50%;
				//left: 50%;
				//margin-top: -100px;
				//margin-left: -250px;
				width: 500px;
				height: 200px;
				//border: 4px dashed blue;
				border: 2px dashed #d2d2d2;
			}

			form p.dragdroptxtdiv {
				width: 100%;
				height: 100%;
				text-align: center;
				//line-height: 170px;
				//color: black;
				font-family: Arial;
				//color: rgb(58, 160, 255);
				color: #466da9;
			}

			form input.user_picked_files {
				position: absolute;
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				outline: none;
				opacity: 0;
			}

			form input:hover {
				cursor: pointer;
			}

			form button {
				margin: 0;
				color: #fff;
				background: #16a085;
				border: none;
				width: 508px;
				height: 35px;
				margin-top: -20px;
				margin-left: -4px;
				border-radius: 4px;
				border-bottom: 4px solid #117A60;
				transition: all .2s ease;
				outline: none;
			}

			form button:hover {
				background: #149174;
				color: #0C5645;
			}

			form button:active {
				border: 0;
			}

			.sbmtbtndiv {
				text-align: center;
				margin-bottom: 2%;
			}

			.msg {
				font-size: 15px;
			}

			table.cvf_uploaded_files {
				list-style-type: none;
				margin: auto;
				margin-top: 40px;
				padding: 0;
				/* width: 70%; */
			}

			table.cvf_uploaded_files .fa.fa-file {
				color: #466da9;
				font-size: 20px;
				padding: 0 10px;
			}
			
			table.cvf_uploaded_files tr {
				background-color: #fff;
				border-radius: 5px;
				margin: 20px 20px 0 0;
				padding: 2px;
			}

			.bg-success {
				padding: 7px;
			}

			.slimScrollDiv {
				margin: auto
			}
			
		</style>
		
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		
		<body>
			<div id="content">
				
				<div id="full">
					<div class="tit_bot_full">
						<h1 style="font-size:28px;padding: 20px 0px 20px 0px; font-weight:bold; width:980px">Project Files Upload</h1>
						<div class="text">
						
						<div id="total_progress_div"><div class="progress-bar"></div></div>
							
							<div align="center"><br><b>Please note that once upload is done you will need to refresh the project page to see the files.<br>You can also select multiple file now.</b></div> <br><br>
							<div class="div_project_files">
								
								<!--<div id="progress-div"><div id="progress-bar"></div></div>-->
								
								<form id="uploadForm" method="post" enctype="multipart/form-data">
									
									<input type = "file" name = "upload" multiple = "multiple" class = "user_picked_files" />
									<input type="hidden" name="total_files_upload_test" id="total_files_upload_test" value="1"/>
									
									<p class="dragdroptxtdiv">
										<i class="fa fa-cloud-upload" style="font-size: 7em;padding-top:5%"></i><br><br>
										<font style="font-size:16px;border-top: 1px solid #3AA0FF;border-bottom: 1px solid #3AA0FF;padding: 8px;">Drag &amp; Drop Your File(s) Here To Upload</font>
									</p>
									
									<div id="overlay"></div>
									
									
									<!--<div class="append_more_file" id="append_file">
										<div class="div_child_data_append">
										<select name="projectc_files_path1" class="projectc_files_path">
										<option value=""> Select </option>
										<option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/AHRICerts">AHRI Certs</option>
										<option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/ConstructionSpecs">Construction Specs</option>
										<option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/EnergyStarChecklists">Energy Star Checklists</option>
										<option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/HERSinputforms">HERS input forms</option>
										<option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/Incentiveapps">Incentive apps</option>
										<option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/ManualJReports">Manual J Reports</option>
										<option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/Photos">Photos</option>
										<option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/RESNETStandardDisclosureform">RESNET Standard Disclosure</option>
										<option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/Takeoffs">Takeoffs</option>
										</select>
										<input name="file_upload_project_rating1[]" multiple class="file_upload_project_rating" type="file" />
										</div>
									</div>-->
									
									<!--<div id="appended_div">
										
									</div>-->
									
									
									<div class="sbmtbtndiv">
										<input type="submit" name="btn_project_files_upload" class = "cvf_upload_btn" id="btn_project_files_upload" value="Start Uploading">
										<!--<span style="margin-top:10px;" id="upload_more_file_link"><a href="#" id="add_more_file">Add more files</a></span>-->
									</div>
									
									
								</form>
								
								<div id="targetLayer"></div>
								
							</div>
							
							<div class="msg"></div>
							
							<div class="slim-scroll" style="margin-top:40px">
								<ul id="cvf_uploaded_files"><table class="cvf_uploaded_files"></table></ul>
							</div>	
							
							<div id="loader-icon" style="display:none;"><img src="LoaderIcon.gif" /></div>			
							
						</div>      
					</div>
					
					<br />
					
					<div style="clear: both"><img src="images/spaser.gif" alt="" width="1" height="1" /></div>
					
				</div>
				<style>
					
					.div_project_files {
					width: 500px;
					margin: auto;
					}.div_child_data_append {
					margin-bottom: 20px;
					width: 100%;
					float: left;
					}
					input.file_upload_project_rating {
					float: left;
					width: 50%;
					}
					select.projectc_files_path {
					//width: 49%;
					//float: left;
					height: 24px;
					}
					input#btn_project_files_upload {
					//float: left;
					width: 120px;
					margin-top: 10px;
					height: 30px;
					background-color:#466da9;
					color:#FFFFFF;
					}
					a#add_more_file {
					width: 50%;
					float: left;
					margin-top: 19px;
					margin-left: 20px;
					}
				</style>
				<!-- content ends -->
				
				<div id="bot"></div>
				
				<!-- footer begins -->
				
				<div id="footer">
					
					<? include("login_footer.inc.php");?>
					
					
					<!-- footer ends -->
					
					
				</div>
				
			</div>
			
			<style>
				
				
				/* #uploadForm label {margin:2px; font-size:1em; font-weight:bold;}*/
				.demoInputBox{padding:5px; border:#F0F0F0 1px solid; border-radius:4px; background-color:#FFF;}
				.progress-bar {background-color: #466da9;height:20px;color: #FFFFFF;width:0%;-webkit-transition: width .3s;-moz-transition: width .3s;transition: width .3s;}
				.btnSubmit{background-color:#09f;border:0;padding:10px 40px;color:#FFF;border:#F0F0F0 1px solid; border-radius:4px;}
				#progress-div, #total_progress_div {border:#466da9 1px solid;padding: 5px 0px;margin:30px 0px;border-radius:4px;text-align:center;}
				#targetLayer{width:100%;text-align:center;}
				
				/*.slimScrollBar{display:block !important;}*/
				
			</style>
			
			
			<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
			<script src="js/jquery.form.min.js"></script>
			<script src="js/jquery.slimscroll.min.js"></script>
			
			<button class="js-notification-icon" disabled style="display:none">Icon</button>
			<script src="js/notification.js"></script>
			
			<input type="hidden" id="total_percent_files">
			
			<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.3.0/bootbox.min.js"></script>
			
			<script>
			
			percentageArray = [];
			
			upload_success_array = [];
			
			stopped_files_array = [];
			
					function closeAndRefresh(){
						//alert(window.opener.$("table#uploadedDocumentsDivTable").html());
						//opener.location.reload(); // or opener.location.href = opener.location.href;
						window.close(); // or self.close();
					}
					
					function update_total_progress_bar(index,percentage,stopped=''){
						//console.log("iundex status"+typeof percentageArray[index]);
						/* if ( percentageArray[index] !== void 0 ){
							console.log("index not");
							percentageArray[index] = percentage;
						}
						else{
							console.log("index exists");
							//percentageArray.push(percentage);
							percentageArray.splice(index,0,percentage);
						} */
						if(typeof percentageArray[index] === 'undefined')
						{
							//console.log("index not exists"+index);
							percentageArray.splice(index,0,percentage);
							for(var i=0; i<=index; i++){
								if(typeof percentageArray[i] === 'undefined'){
									percentageArray.splice(i,0,0);
								}
							}
							//percentageArray.join();
						}
						else{
							//console.log("index exists"+index);
							percentageArray[index] = percentage;
						}
						
							stored_total_files_total = $("input#total_percent_files").val();
							//console.log(percentageArray);
							var total_percentage = 0;
							for(i=0;i<percentageArray.length;i++){
								total_percentage += percentageArray[i];
							}
							$("#total_progress_div .progress-bar").css({'width': total_percentage/stored_total_files_total + '%'});
							//console.log("total "+stored_total_files_total);
							$("#total_progress_div .progress-bar").html('<span id="progress-status">' + parseInt(total_percentage/stored_total_files_total) +' %</span>');
							//console.log("total_percentage "+total_percentage/stored_total_files_total);
						if(stopped=="yes"){
							var total_uploaded = 0;
							for(i=0;i<upload_success_array.length;i++){
								total_uploaded += upload_success_array[i];
							}
							if(stored_total_files_total==total_uploaded){
								//console.log("upload was successful");
								$(".js-notification-icon").trigger("click");
									$("div.msg").css({'margin-top':'76px'});
									$("div.msg").html("<center><b style='font-size:14px'>All files have been completed. successfully!</b><br>You will be redirected to project page automatically in 5 seconds<br><br></center>");
									var project_id = "<?php echo $project_id; ?>";
									window.setTimeout(function() { closeAndRefresh(); }, 5000);
							}
						}
						
					}
				
				$(document).ready(function() {
									
					$("#logo, #top, #header").hide();
					
					$("#footer").html('');
					
					$("#add_more_file").on("click",function(){
						
						var total_files = $("#total_files").val();
						var total_new_files = parseInt(total_files)+parseInt(1);
						$("#total_files").val(total_new_files);
						
						var html = '<div class="div_child_data_append"><select name="projectc_files_path'+total_new_files+'" class="projectc_files_path"><option value=""> Select </option><option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/AHRICerts">AHRI Certs</option><option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/ConstructionSpecs">Construction Specs</option><option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/EnergyStarChecklists">Energy Star Checklists</option><option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/HERSinputforms">HERS input forms</option><option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/Incentiveapps">Incentive apps</option><option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/ManualJReports">Manual J Reports</option><option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/Photos">Photos</option><option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/RESNETStandardDisclosureform">RESNET Standard Disclosure</option><option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/Takeoffs">Takeoffs</option></select><input multiple name="file_upload_project_rating'+total_new_files+'[]" class="file_upload_project_rating" type="file" class="demoInputBox" /></div>';
						
						$("#appended_div").append(html);
					});
					
					
					function progress(e){
						
						if(e.lengthComputable){
							var max = e.total;
							var current = e.loaded;
							
							var Percentage = (current * 100)/max;
							//console.log(parseInt(Percentage));
							
							/* if(Percentage >= 100)
								{
								// process completed  
							} */
							$("#progress-bar").width(Percentage+"%");
							$("#progress-bar").html('<div id="progress-status">' + parseInt(Percentage) +' %</div>');
						}  
					}				 
					
					function updateParentFileDiv(id_of_parent_page_section,file,filepath){
						//console.log(id_of_parent_page_section+' '+file);
						
						var fullDate = new Date();
						
						//convert month to 2 digits
						var today = new Date();
						var day = today.getDate();
						var month = today.getMonth() + 1;
						var year = today.getFullYear().toString().substr(-2);

						var currentDate =
							(month < 10 ? "0" : "") + month + "/"  +
							(day < 10 ? "0" : "") + day + "/"  +
							year;
						
						/* var currentDate = twoDigitDate + "/" + twoDigitMonth + "/" + fullDate.getFullYear().toString().substr(-2); */
						//console.log(currentDate);
						
						filepath = filepath+'/'+file;
						//console.log(filepath);
						//console.log(new Date("m/d/y"));
						
						var append_file = "<table width='793'><tr><td>"+
						"<a href='download_file.php?downnload_file="+filepath+"'>"+file+"</a>"+
						"</td><td width='80'>"+
						"<font size='-1'>"+currentDate+"</font>"+
						"</td><td width='50'>"+
						"<a title='View in browser' target='_blank' href='download_file.php?downnload_file="+filepath+"&inline=Yes'><img src='images/xeyes1.png' border='0'  height='20' width='20'></a> "+
						"<a href='rater_ProjectRating_manage1.php?path="+filepath+"&action=deletefile&project_id=<? echo $project_id?>' style='font-size:12px; font-weight:bold'><img src='images/delete1.png' alt='Delete' border='0' onClick='if(confirm(\"Are you sure you wish to delete this file?\")) { return true; } else { return false; }'></a>&nbsp;"+
						"</td></tr></table>";
						window.opener.$("table td#"+id_of_parent_page_section).append(append_file); 
					}					
					
					function uploadfiles(data,i){
						//console.log("data"+storedFiles.length);
						console.log('stop'+i);
						console.log("window1 "+window['stop'+i]);
						//return i;
						console.log(data);
						window['stop'+i] = $.ajax({
							url: 'ajax/upload.php?project_id=<?php echo $_GET['project_id'];?>',
							type: 'POST',
							contentType: false,
							data: data,
							processData: false,
							cache: false,
							beforeSend: function() {
								$("a.cvf_delete_image").remove();
								$(".progress-bar").width('0%');
							},
							xhr: function() {
								var myXhr = $.ajaxSettings.xhr();
								if(myXhr.upload){
									//myXhr.upload.addEventListener('progress',progress, false);
									myXhr.upload.addEventListener('progress',function(e){
										if(e.lengthComputable){
											var max = e.total;
											var current = e.loaded;
											var Percentage = (current * 100)/max;
											//console.log("bar"+i);
											
											//$("span#progress-div"+i+ " .progress-bar").width(Percentage+"%");
											//console.log({"padding":'0 ' + Percentage/2 + ' px'});
											$("td#progress-div"+i+ " .progress-bar").css({'padding':'0 '+ Percentage/2 + 'px'});
											$("td#progress-div"+i+ " .progress-bar").html('<span id="progress-status">' + parseInt(Percentage) +' %</span>');
											
											//console.log("stored new"+stored_total_files_new);
											if(storedFiles[i]!='null'){
												//console.log(i);
												update_total_progress_bar(i,parseInt(Percentage));
											}
											//prev = Percentage;
											/* prev_Percentage = parseInt(Percentage);
											
											total_Percentage = prev_Percentage + total_Percentage;
											console.log("total_Percentage"+total_Percentage); */
											//<i class="fa fa-stop-circle"></i>
											//$("#progress-bar").width(Percentage+"%");
											//$("#progress-bar").html('<div id="progress-status">' + parseInt(Percentage) +' %</div>');
										}
									},false);
								}
								return myXhr;
							},
							success: function(response) {
								
								console.log(response);
								
								var filename = data.get('files-'+i).name;
								
								var filepath = data.get('name'+i);
								
								var fileinfoarray = filepath.split('/');
								id_of_parent_td_toupdate = fileinfoarray[4];
								
								updateParentFileDiv(id_of_parent_td_toupdate,filename,filepath);
								
								stored_total_files_total = $("input#total_percent_files").val();
								
								upload_success = upload_success+1;
								
								upload_success_array.push(upload_success);
								
								if(stored_total_files_new == upload_success){
									$(".js-notification-icon").trigger("click");
									$("div.msg").css({'margin-top':'76px'});
									$("div.msg").html("<center><b style='font-size:14px'>All files have been completed. successfully!</b><br>You will be redirected to project page automatically in 5 seconds<br><br></center>");
									var project_id = "<?php echo $project_id; ?>";
									window.setTimeout(function() { closeAndRefresh(); }, 5000);
								}
								
								console.log("uploaded"+upload_success);
							},
							resetForm: true 
						});
						//return false;
					}
					
					
					$('#uploadForm').submit(function(e) {
						
						//alert("test");
						//return false;
						
						/*validations for empty fields*/
						var plan_err = 0;
						var file_err = 0;
						
						/* $(".projectc_files_path").each(function(){
							var temp = $(this).val();
							if (temp == "") {
							plan_err = plan_err+1;
							}
							});
							$(".file_upload_project_rating").each(function(){
							var temp = $(this).val();
							if (temp == "") {
							file_err = file_err+1;
							}
						}); */
						
						stored_total_files = storedFiles.length;
						stored_total_files_new = stored_total_files;
						//$("input#total_percent_files").val(stored_total_files);
						for (var i=0; i < stored_total_files;  i++){
							//console.log(storedFiles[i]);
							//return false;
							if(storedFiles[i]!='null'){
								var projectc_files_path = "projectc_files_path"+i;
								var projectc_files_path=$("select[name='"+projectc_files_path+"']").val();
								if(projectc_files_path==''){
									//console.log('hi');
									plan_err = plan_err+1;
								}
							}				
						}
						
						if (plan_err > 0) {
							alert("Select from Dropdown is required");
							return false;
						}
						
						else{
							//console.log("hi");
							//console.log(stored_total_files);
							//return false;
							upload_success = 0;
							total_Percentage = 0;
							
							$("input[type='file']").prop("disabled", true);
							
							$("#overlay").css({'position': 'absolute', 'display': 'block', 'top': '0', 'left': '0', 'right': '0', 'bottom': '0', 'background-color': 'rgba(0,0,0,0.5)', 'z-index': '2', 'cursor': 'no-drop'});
							
							for (var i=0; i < stored_total_files;  i++){
								console.log("hlw");
								var data = new FormData();
								$("input#btn_project_files_upload").css({'cursor':'no-drop','background-color':'#466da9c7'});
								$("input#btn_project_files_upload").prop("disabled", true);
								var projectc_files_path = "projectc_files_path"+i;
								$("select[name='"+projectc_files_path+"']").prop("disabled", true);
								if(storedFiles[i]!='null'){
									//alert(i);
									data.append('files-' + i, storedFiles[i]);
									var projectc_files_path = "projectc_files_path"+i;
									var projectc_files_path=$("select[name='"+projectc_files_path+"']").val();
									//console.log(projectc_files_path);
									data.append('name' + i, projectc_files_path);
									var total_files = $("input[name='total_files_upload_test']").val();
									data.append('total_files_upload_test', total_files);
									$("a#stop"+i).html("<i class='fa fa-stop-circle' id='"+i+"' style='font-size:20px'></i>");
									uploadfiles(data,i);
								}
								
							}
							
							
							
							//console.log('ajax/upload.php?project_id=<?php echo $_GET['project_id'];?>');
							//return false;
							
							//var inputfiles = $("input.user_picked_files")[0];
							
							//var totalinputfiles = inputfiles.files.length;
							
							//return false;
							
							//console.log(data[0]);
							
							
							
							return false;
							
						}
						
						/* if($('#total_files').val()) {
							e.preventDefault();
							$('#loader-icon').show();
							$(this).ajaxSubmit({ 
							target:   '#targetLayer', 
							beforeSubmit: function() {
							$("#progress-bar").width('0%');
							},
							uploadProgress: function (event, position, total, percentComplete){	
							$("#progress-bar").width(percentComplete + '%');
							$("#progress-bar").html('<div id="progress-status">' + percentComplete +' %</div>')
							},
							success:function (){
							$('#loader-icon').hide();
							},
							resetForm: true 
							}); 
							return false; 
						} */
					});
					
				});
				
				
				
			</script>
			
			
			<script type="text/javascript">
			
			function updateSelectArray(index,value){
				projectc_files_paths[index]=value;
				console.log(projectc_files_paths);
			}
			
				jQuery(document).ready(function() {         
					
					storedFiles = [];      
					//$('.cvf_order').hide();
					
					projectc_files_paths = [];
					// Apply sort function  
					/* function cvf_reload_order() {
						var order = $('.cvf_uploaded_files').sortable('toArray', {attribute: 'item'});
						$('.cvf_hidden_field').val(order);
					} */
					
					function cvf_add_order() {
						$('.cvf_uploaded_files li').each(function(n) {
							$(this).attr('item', n);
						});
						//console.log('test');
					}
					
					
					/* $(function() {
						$('.cvf_uploaded_files').sortable({
						cursor: 'move',
						placeholder: 'highlight',
						start: function (event, ui) {
                        ui.item.toggleClass('highlight');
						},
						stop: function (event, ui) {
                        ui.item.toggleClass('highlight');
						},
						update: function () {
                        //cvf_reload_order();
						},
						create:function(){
                        var list = this;
                        resize = function(){
						$(list).css('height','auto');
						$(list).height($(list).height());
                        };
                        $(list).height($(list).height());
                        $(list).find('img').load(resize).error(resize);
						}
						});
						$('.cvf_uploaded_files').disableSelection();
					}); */
					
					function appendfilesindiv(file){
						$('.cvf_uploaded_files').html('');
						//alert($('.cvf_uploaded_files').html());
						console.log(storedFiles);
						for(var i = 0; i<storedFiles.length; i++){
							//console.log(storedFiles);
							
							projectc_files_path = projectc_files_paths[i];
							
							//alert(projectc_files_path);
							
							var AHRICerts='', ConstructionSpecs='', EnergyStarChecklists='', HERSinputforms='', Incentiveapps='', ManualJReports='', Photos='', RESNETStandardDisclosureform='', Takeoffs='';
							
							if(projectc_files_path =="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/AHRICerts") { AHRICerts = "selected" };
							
							if(projectc_files_path =="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/ConstructionSpecs") { ConstructionSpecs = "selected" };
							
							if(projectc_files_path =="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/EnergyStarChecklists") { EnergyStarChecklists = "selected" };
							
							if(projectc_files_path =="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/HERSinputforms") { HERSinputforms = "selected" };
							
							if(projectc_files_path =="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/Incentiveapps") { Incentiveapps = "selected" };
							
							if(projectc_files_path =="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/ManualJReports") { ManualJReports = "selected" };
							
							if(projectc_files_path =="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/Photos") { Photos = "selected" };
							
							if(projectc_files_path =="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/RESNETStandardDisclosureform") { RESNETStandardDisclosureform = "selected" };
							
							if(projectc_files_path =="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/Takeoffs") { Takeoffs = "selected" };
							
							console.log(ManualJReports);
							
							var projectfiles = 
							'<select name="projectc_files_path'+i+'" class="projectc_files_path" id="'+i+'" onchange="updateSelectArray(this.id,this.value)">'+
							'<option value=""> Select </option>'+
							'<option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/AHRICerts" '+AHRICerts+' >AHRI Certs</option>'+
							'<option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/ConstructionSpecs" '+ConstructionSpecs+' >Construction Specs</option>'+
							'<option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/EnergyStarChecklists" '+EnergyStarChecklists+' >Energy Star Checklists</option>'+
							'<option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/HERSinputforms" '+HERSinputforms+' >HERS input forms</option>'+
							'<option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/Incentiveapps" '+Incentiveapps+' >Incentive apps</option>'+
							'<option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/ManualJReports" '+ManualJReports+' >Manual J Reports</option>'+
							'<option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/Photos" '+Photos+' >Photos</option>'+
							'<option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/RESNETStandardDisclosureform" '+RESNETStandardDisclosureform+' >RESNET Standard Disclosure</option>'+
							'<option value="raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/Takeoffs" '+Takeoffs+' >Takeoffs</option>'+
							'</select>';
							
							//var filename = file.name;
							if(storedFiles[i]!='null'){
								var filename = storedFiles[i].name;
								console.log(filename);
								if(filename.length > 28){
									filename = filename.substring(0,26) + '..'; 
								}
								else{
									filename = filename;
								}
								
								/* $('.cvf_uploaded_files').append(
                                "<span id='"+i+"' style='width:100%'>"+"<li file = '" + filename + "'>" + projectfiles  +
								"&nbsp;&nbsp;&nbsp;&nbsp;<span style='width:50%;font-size:13px'><i class='fa fa-file' aria-hidden='true' style='color:#466da9'></i>&nbsp;&nbsp;"								
								+ filename + 
								"</span>&nbsp;&nbsp;<span id='progress-div"+i+"' style='width:20%'>"+"<span class='progress-bar'></span>"+"</span>"+"<a class='stop_file_btn' title='"+i+"' id='stop"+i+"' style='width:2%;float:right;margin-top:3px'></a>"+
								"&nbsp;&nbsp;<a href = '#' class = 'cvf_delete_image' title = 'Remove' id='"+i+"' style='width:16%'><i class='fa fa-close' style='font-size:14px'></i></a>" +
                                "</li>"+"</span>"
                                ); */
								
								$('.cvf_uploaded_files').append(
									"<tr id='"+i+"' file = '" + filename + "'><td style='width:15%'>" 
										+ projectfiles  +
										"</td><td style='width:35%'><i class='fa fa-file' aria-hidden='true'></i><span style='font-size:14px' id='filenamespan"+i+"'>"								
										+ filename + 
									"</span></td><td id='progress-div"+i+"' style='width:30%'><span class='progress-bar'></span></td><td><a class='stop_file_btn' title='"+i+"' id='stop"+i+"'></a><a href='#' class='cvf_delete_image' title='Remove' id='"+i+"'><i class='fa fa-close'></i></a></td></tr>"
								);
								
								
							}
						}			
					}
                    
					$('body').on('change', '.user_picked_files', function() {
						
						$('ul#cvf_uploaded_files').slimScroll({
							height: '250px',
							alwaysVisible: true,
							width: '800px'
							/* 
							disableFadeOut: false */
						});
						
						totalnull = 0;
						jQuery.grep(storedFiles, function(n, i){ 
							if(n == "null"){
								++totalnull;
							}						
						});
						
						if(storedFiles.length==totalnull)
						{
							storedFiles = [];
						}
						
						var files = this.files;
						var i = 0;
						
						console.log(storedFiles.length);
						
						console.log(files.length);
						
						
						for (i = 0; i < files.length; i++) {
							//var readImg = new FileReader();
							var file = files[i];
							storedFiles.push(file);
							
							//$(projectfiles).after('.cvf_uploaded_files');
							
							//filesPath.push(file);
							
							projectc_files_paths.push("raterfiles/<?php echo $rater_id;?>/Ratings/<?php echo $project_id;?>/ConstructionSpecs");
							
							//console.log(file);
							
							console.log(projectc_files_paths);
							
							appendfilesindiv(file);
							
							/* if(files.length === (i+1)){
								setTimeout(function(){
									cvf_add_order();
								}, 1000);
							} */
						}
						$("input#total_percent_files").val(storedFiles.length);
					});
					
					// Stop file from Queue
					$('body').on('click','a.stop_file_btn',function(e){
						e.preventDefault();
						var stopid = $(this).attr('id');
						console.log("atop id "+stopid);
						$("#"+stopid).css({'cursor':'no-drop','color':'#466da9c7'});
						console.log(window[stopid]);
						window[stopid].abort();
						stored_total_files_new--;
						input = $("input#total_percent_files").val()-1;
						$("input#total_percent_files").val(input);
						var i = $(this).children().attr('id');
						stopped_files_array.push(i);
						$("span#filenamespan"+i).css({'text-decoration': 'line-through','text-decoration-color': '#a9464b'});
						console.log("stopped btn clicked"+i);
						update_total_progress_bar(i,0,"yes");
						/* for(var j=0;l<storedFiles.length;j++)
						{
							if(storedFiles.length!='')
							{
								stored_total_files_new++;
							}
						} */
						//stored_total_files_new = stored_total_files - 1;
						//alert(stopid);
						//return false; 
					});
					
					// Delete Image from Queue
					$('body').on('click','a.cvf_delete_image',function(e){
						e.preventDefault();
						var removeid = $(this).attr('id');       
						//return false;
						var file = $(this).parent().attr('file');
						for(var i = 0; i < storedFiles.length; i++) {
							//if(storedFiles[i].name == file) {
							if(i==removeid){
								//storedFiles.splice(i, 1);
								storedFiles[i]='null';
								$("tr#"+i).remove();
								var projectc_files_path = "projectc_files_path"+i;
								//var projectc_files_path=$("select[name='"+projectc_files_path+"']").val();
								$("select[name='"+projectc_files_path+"']").remove('');
								input = $("input#total_percent_files").val()-1;
								$("input#total_percent_files").val(input);
								update_total_progress_bar(i,0);								
								break;
							}
							//}
						}
						
						//cvf_reload_order();
						
					});
                    
					// AJAX Upload
					/* $('body').on('click', '.cvf_upload_btn', function(e){
						
						e.preventDefault();
						//cvf_reload_order();
						
						//$(".cvf_uploaded_files").html('<p><img src = "loading.gif" class = "loader" /></p>');
						var data = new FormData();
						
						var items_array = $('.cvf_hidden_field').val();
						var items = items_array.split(',');
						
						var total_files = storedFiles.length;
						//return false;
						
						console.log("total_files"+total_files);
						
						for (var i=0; i < total_files;  i++){
						//var item_number = items[i];
						console.log(storedFiles[i]);
						if(storedFiles[i]!='null'){
						data.append('files-' + i, storedFiles[i]);
						var projectc_files_path = "projectc_files_path"+i;
						//console.log("n"+n);
						var projectc_files_path=$("select[name='"+projectc_files_path+"']").val();
						console.log(projectc_files_path);
						data.append('name' + i, projectc_files_path);
						}				
						}
						
						for (var pair of data.entries()) {
						console.log(pair[0]+ ', ' + pair[1]); 
						}
						//return false;	
						//return false;    
						$.ajax({
						url: 'upload.php',
						type: 'POST',
						contentType: false,
						data: data,
						processData: false,
						cache: false,
						success: function(response, textStatus, jqXHR) {
                        //$(".cvf_uploaded_files").html('');                                                
                        //bootbox.alert('<br /><p class = "bg-success">File(s) uploaded successfully.</p>');
                        //alert(jqXHR.responseText);
						console.log(jqXHR.responseText);
						}
						});
						
					});  */        
					
				});
			</script>
			
			
			
		</body>
		
	</html>
	
	<?
		
		} else{ 
		
		exit("<center><h2>Sorry, You dont' have permission to view this page!</h2></center>"); 
		
	} 
	
	} else{ 
	
	require_once("logout_redirection.php");
	
	exit("<center><h2>You are not logged in, please <a href='login.php'>login</a>!</h2></center>"); 
	
} 

?>