<?php

session_start();

include("../dbinfo.inc.php"); 


	if(isset($_GET['upload_mentor_files']))
	{ 
			
			$updated_by_user_id = $_SESSION['user_id'];
			$updated_by_user_name = $_SESSION['first_name']. ' '. $_SESSION['last_name'];
			$_SESSION['userid'] = $_GET['id'];
			$user_id = $_SESSION['userid'];			
			$mentorfile = '';
			
			if($_SESSION['user_level'] == 2 || $_SESSION['user_level'] == 11)
			{
				if( $_POST['mentor1'] || $_POST['mentori1_date1'] != 'mm/dd/yyyy' || $_POST['mentori1_date2'] != 'mm/dd/yyyy' || $_FILES['usermentorfile11']['name']!= '' || $_FILES['usermentorfile12']['name']!='' )
				{	
					$name = 'mentorinspfiles1';
					$mentorfile = '1';
				}
				else if( $_POST['mentor2'] || $_POST['mentori2_date1'] != 'mm/dd/yyyy' || $_POST['mentori2_date2'] != 'mm/dd/yyyy' || $_FILES['usermentorfile21']['name']!= '' || $_FILES['usermentorfile22']['name']!='' )
				{	
					$name = 'mentorinspfiles2';
					$mentorfile = '2';
				}
				else if( $_POST['mentor3'] || $_POST['mentori3_date1'] != 'mm/dd/yyyy' || $_POST['mentori3_date2'] != 'mm/dd/yyyy' || $_FILES['usermentorfile31']['name']!= '' || $_FILES['usermentorfile32']['name']!='' )
				{	
					$name = 'mentorinspfiles3';
					$mentorfile = '3';
				}
				else if( $_POST['mentor6'] || $_POST['mentori6_date1'] != 'mm/dd/yyyy' || $_POST['mentori6_date2'] != 'mm/dd/yyyy' || $_FILES['usermentorfile61']['name']!= '' || $_FILES['usermentorfile62']['name']!='' )
				{	
					$name = 'mentorinspfiles6';
					$mentorfile = '6';
				}
				
				$fetchquery = " select * from training_progress where user_id = '$user_id' and name = '$name' order by id desc ";						
			}
			
			function save_notes($id,$user,$mentorfilename,$file,$uploadDir_display,$mentor_name,$mentor_insp_date,$mentor_approval_date)
			{
			
							if($mentorfilename!='')
							{

								$texttodisplay = 'Uploaded File: '.$file.' in the '.$uploadDir_display.' slot';
							}
							else
							{

								$texttodisplay = "Updated Data: ";
								
								if($mentor_name!='')
								{
									$texttodisplay.= ' Mentor Name: '.$mentor_name;								
								}
								if($mentor_insp_date!='')
								{
									$texttodisplay.= ' Inspection Date: '.$mentor_insp_date;
								}
								if($mentor_approval_date!='')
								{
									$texttodisplay.= ' Approval Date: '.$mentor_approval_date;
								}
							}
							$mentorfilesnotesquery="INSERT INTO rater_notes (rater_id, notes, user, category)	VALUES ('$id', '$texttodisplay','$user', 'Admin')";							
							mysql_query($mentorfilesnotesquery) or die(mysql_error());
			}
			
				
			// File upload section 

			$uploadDir = '../raterfiles/'.$_SESSION['userid'];
			$result= chdir($uploadDir);
			
			//Todays date also used to create unique fileanme
			$date = getdate();
			$month = $date['mon'];
			$day = $date['mday'];
			$year = $date['year'];
			if ($month < 10) {$month = '0'.$month;}
			if ($day < 10) {$day = '0'.$day;} 
			$uniqueId = "_".$month.$day.$year;
			
			// This Section handles the Uploading of the files 
				   
				   function uploadFiles($file,$name,$mentor_insp_number,$mentorformtype,$mentor_name,$mentor_insp_date,$mentor_approval_date,$mentorfilename,$mentorfiletempname,$fetchedmentorfilename,$updated_by_user_id,$updated_by_user_name)
				   {
					 //echo "updated by@". $updated_by_user_id."done";  
					   include("../dbinfo.inc.php");
					   
					   
					   //Todays date also used to create unique fileanme
						$date = getdate();
						$month = $date['mon'];
						$day = $date['mday'];
						$year = $date['year'];
						if ($month < 10) {$month = '0'.$month;}
						if ($day < 10) {$day = '0'.$day;} 
						$uniqueId = "_".$month.$day.$year;
					   
					   
					   $user = $_SESSION['first_name'].' '.$_SESSION['last_name'];
					   
					   $id = $_SESSION['userid'];
					   
					   $uploadDir = 'raterfiles/'.$id;
					   
					   
					   if($file=='mentorinspfile11')
					   {
							$uploadDir = 'Mentor Inspection 1/'.$_POST['mentorformtype11'].'/'; 
							$uploadDir_display = 'Mentor Inspection 1/'.$_POST['mentorformtype11'];
					   }
					   
					   else if($file=='mentorinspfile12')
					   {
							$uploadDir = 'Mentor Inspection 1/'.$_POST['mentorformtype12'].'/'; 
							$uploadDir_display = 'Mentor Inspection 1/'.$_POST['mentorformtype12'];
					   }
					   
					   else if($file=='mentorinspfile21')
					   {
							$uploadDir = 'Mentor Inspection 2/'.$_POST['mentorformtype21'].'/'; 
							$uploadDir_display = 'Mentor Inspection 2/'.$_POST['mentorformtype21'];
					   }
					   
					   else if($file=='mentorinspfile22')
					   {
							$uploadDir = 'Mentor Inspection 2/'.$_POST['mentorformtype22'].'/'; 
							$uploadDir_display = 'Mentor Inspection 2/'.$_POST['mentorformtype22'];
					   }
					   else if($file=='mentorinspfile31')
					   {
							$uploadDir = 'Mentor Inspection 3/'.$_POST['mentorformtype31'].'/'; 
							$uploadDir_display = 'Mentor Inspection 3/'.$_POST['mentorformtype31'];
					   }
					   
					   else if($file=='mentorinspfile32')
					   {
							$uploadDir = 'Mentor Inspection 3/'.$_POST['mentorformtype22'].'/'; 
							$uploadDir_display = 'Mentor Inspection 3/'.$_POST['mentorformtype32'];
					   }
					   else if($file=='mentorinspfile61')
					   {
							$uploadDir = 'Graded Field Evaluation/'.$_POST['mentorformtype61'].'/'; 
							$uploadDir_display = 'Graded Field Evaluation/'.$_POST['mentorformtype61'];
					   }
					   
					   else if($file=='mentorinspfile62')
					   {
							$uploadDir = 'Graded Field Evaluation/'.$_POST['mentorformtype62'].'/'; 
							$uploadDir_display = 'Graded Field Evaluation/'.$_POST['mentorformtype62'];
					   }				   
					   
						$dir = @opendir($uploadDir);
						if ($dir === FALSE) { 
							$oldumask = umask(0); 
							mkdir($uploadDir, 0777,true); 
							umask($oldumask); 
							//echo " Sub Directory didn't exist. It has been created now<br>";
						}

						
					   $result= chdir($uploadDir);
					   
					  
					   $uploadFile = $mentorfilename;
					   
					   
					   
					   $t=1; // variable for appending to the filename if already exists 
					   // This loop checks if the file already exists and appends number if true 
					   while(file_exists($uploadFile)){

						$uploadFile = $mentorfilename;
						   
						 $uploadFile=substr($uploadFile,0,strpos($uploadFile,"."))."_$t".strstr($uploadFile,".");
						 $t++;
					   }
					   $uploadFile=substr($uploadFile,0,strpos($uploadFile,".")).$uniqueId.strstr($uploadFile,".");
					   $uploadFile = preg_replace("/[^A-Za-z0-9\._]/",'',$uploadFile);
					   
					   
					   $ret = @move_uploaded_file($mentorfiletempname, $uploadFile ); 
					   
					   
					   $result= chdir('../../'); // Go back folder up 
						
						
						if($fetchedmentorfilename!='')
						{
							if($mentorfiletempname!='')
							{
								$save_file_name = $uploadFile;
							}
							else
							{
								$save_file_name = $fetchedmentorfilename;
							}
							
							mysql_query("update training_progress SET active = '0' where user_id = '$id' AND 
							name = '$name' AND mentor_form_type = '$mentorformtype' ");
							
							$saveTrainingProgress = mysql_query("insert into training_progress						(name,mentor_insp_number,mentor_form_type,mentor_name,user_id,document,mentor_insp_date,mentor_approval_date,updated_by_user_id,updated_by_user_name,active,updated_date) 
							VALUES
							('$name','$mentor_insp_number','$mentorformtype','$mentor_name','$id','$save_file_name','$mentor_insp_date','$mentor_approval_date','$updated_by_user_id','$updated_by_user_name','1',NOW())");

							save_notes($id,$user,$mentorfilename,$uploadFile,$uploadDir_display,$mentor_name,$mentor_insp_date,$mentor_approval_date);
							
						}
						else
						{
							
							if ($ret === TRUE) {
								
							mysql_query("update training_progress SET active = '0' where user_id = '$id' AND 
							name = '$name' AND mentor_form_type = '$mentorformtype' ");
							
							$saveTrainingProgress = mysql_query("insert into training_progress						(name,mentor_insp_number,mentor_form_type,mentor_name,user_id,document,mentor_insp_date,mentor_approval_date,updated_by_user_id,updated_by_user_name,active,updated_date) 
							VALUES
							('$name','$mentor_insp_number','$mentorformtype','$mentor_name','$id','$uploadFile','$mentor_insp_date','$mentor_approval_date','$updated_by_user_id','$updated_by_user_name','1',NOW())");

							save_notes($id,$user,$mentorfilename,$uploadFile,$uploadDir_display,$mentor_name,$mentor_insp_date,$mentor_approval_date);
							
						  }
						}						
				   } //end function uploadfiles
				   
				   function setAdminFields($name,$user_id,$mentorinspfileno,$mentorinspfile,$getmentorformtype,$mentorname,$mentordate1,$mentordate2,$mentorfile1,$mentorfile1tmpname,$updated_by_user_id,$updated_by_user_name)
				   {
						   include("../dbinfo.inc.php");					   
						   
						   $fetchquery = " select * from training_progress where user_id = '$user_id' and name = '$name' order by id desc ";						
							
						
							$fetchQuery = mysql_query($fetchquery);
							
							if($mentordate1 == 'mm/dd/yyyy')
							{
								$mentordate1 = '';
							}
							if($mentordate2 == 'mm/dd/yyyy')
							{
								$mentordate2 = '';
							}
							
						  $mentorformtype = $getmentorformtype;
						   
						  $result = array(); 
						  
						  $fetch = mysql_fetch_array($fetchQuery);
						  
						  if($mentorname!='')
						  {
							  $mentor_name = $mentorname;
						  }
						  if($mentorname=='')
						  {
							  $mentor_name = $fetch['mentor_name'];
						  }
						  
						  if($mentordate1!='')
						  {
							  $mentor_insp_date = $mentordate1;
						  }	
						  if(!$mentordate1)
						  {
							 $mentor_insp_date = $fetch['mentor_insp_date'] ;
						  }	

						  if($mentordate2!='')
						  {
							  $mentor_approval_date = $mentordate2;
						  }
						  if(!$mentordate2)
						  {
							  $mentor_approval_date = $fetch['mentor_approval_date'] ;
						  }	
						  
						  
						  if($mentorfile1!= '')
						  {
							  $mentorfilename = $mentorfile1;
							  $mentorfiletempname = $mentorfile1tmpname;
							  $mentorformtype = $getmentorformtype;
							 // $mentorinspfile = $mentorisnpfile;
						  }
						  if($mentorfile1== ''){
							  $mentorfilename = "";
							  $mentorfiletempname = "";
							  $fetchedmentorfilename = $fetch["document"];
							  $mentorformtype = $fetch['mentor_form_type'];
							  //$mentorinspfile = 'mentorinspfile12';
						  }
						
						$mentoredinspinput = '';	
							
						if($mentorfilename=='' && $fetchedmentorfilename=='')
						{ ?>
							<script>
								alert("Details can not be saved as no document was submitted");
							</script>
						<?php }							
									  
						 uploadFiles($mentorinspfile,$mentorinspfileno,$mentoredinspinput,$mentorformtype,$mentor_name,$mentor_insp_date,$mentor_approval_date,$mentorfilename,$mentorfiletempname,$fetchedmentorfilename,$updated_by_user_id,$updated_by_user_name);
					  
				   }
				   
				   
				   if($_SESSION['user_level'] == 2 || $_SESSION['user_level'] == 11)
				   {
					  if($name == 'mentorinspfiles1')
					  {	
							if($_FILES['usermentorfile11']['name']!='')
							{
								$mentorformtype = $_POST['mentorformtype11'];
								$mentorisnpfile = 'mentorinspfile11';
								$mentorfilename = $_FILES['usermentorfile11']['name'];
								$mentorfiletempname = $_FILES['usermentorfile11']['tmp_name'];
								
								$mentor1array = setAdminFields($name,$user_id,"mentorinspfiles1",$mentorisnpfile,$mentorformtype,$_POST['mentor1'],$_POST['mentori1_date1'],$_POST['mentori1_date2'],$mentorfilename,$mentorfiletempname,$updated_by_user_id,$updated_by_user_name);	
								
							}
							if($_FILES['usermentorfile12']['name']!='')
							{
								$mentorformtype = $_POST['mentorformtype12'];
								$mentorisnpfile = 'mentorinspfile12';
								$mentorfilename = $_FILES['usermentorfile12']['name'];
								$mentorfiletempname = $_FILES['usermentorfile12']['tmp_name'];
								
								$mentor1array = setAdminFields($name,$user_id,"mentorinspfiles1",$mentorisnpfile,$mentorformtype,$_POST['mentor1'],$_POST['mentori1_date1'],$_POST['mentori1_date2'],$mentorfilename,$mentorfiletempname,$updated_by_user_id,$updated_by_user_name);							
							}
							else{
								$mentorformtype = '';
								$mentorisnpfile = '';
								
								$mentor1array = setAdminFields($name,$user_id,"mentorinspfiles1",$mentorisnpfile,$mentorformtype,$_POST['mentor1'],$_POST['mentori1_date1'],$_POST['mentori1_date2'],$mentorfilename,$mentorfiletempname,$updated_by_user_id,$updated_by_user_name);							
							}										
					  }	
					  
					  if($name == 'mentorinspfiles2')
					  {	
							if($_FILES['usermentorfile21']['name']!='')
							{
								$mentorformtype = $_POST['mentorformtype21'];
								$mentorisnpfile = 'mentorinspfile21';
								$mentorfilename = $_FILES['usermentorfile21']['name'];
								$mentorfiletempname = $_FILES['usermentorfile21']['tmp_name'];
								
								$mentor1array = setAdminFields($name,$user_id,"mentorinspfiles2",$mentorisnpfile,$mentorformtype,$_POST['mentor2'],$_POST['mentori2_date1'],$_POST['mentori2_date2'],$mentorfilename,$mentorfiletempname,$updated_by_user_id,$updated_by_user_name);	
								
							}
							if($_FILES['usermentorfile22']['name']!='')
							{
								$mentorformtype = $_POST['mentorformtype22'];
								$mentorisnpfile = 'mentorinspfile22';
								$mentorfilename = $_FILES['usermentorfile22']['name'];
								$mentorfiletempname = $_FILES['usermentorfile22']['tmp_name'];
								
								$mentor1array = setAdminFields($name,$user_id,"mentorinspfiles2",$mentorisnpfile,$mentorformtype,$_POST['mentor2'],$_POST['mentori2_date1'],$_POST['mentori2_date2'],$mentorfilename,$mentorfiletempname,$updated_by_user_id,$updated_by_user_name);							
							}
							else{
								$mentorformtype = '';
								$mentorisnpfile = '';
								
								$mentor1array = setAdminFields($name,$user_id,"mentorinspfiles2",$mentorisnpfile,$mentorformtype,$_POST['mentor2'],$_POST['mentori2_date1'],$_POST['mentori2_date2'],$mentorfilename,$mentorfiletempname,$updated_by_user_id,$updated_by_user_name);							
							}										
					  }
					  
					  if($name == 'mentorinspfiles3')
					  {	
							if($_FILES['usermentorfile31']['name']!='')
							{
								$mentorformtype = $_POST['mentorformtype31'];
								$mentorisnpfile = 'mentorinspfile31';
								$mentorfilename = $_FILES['usermentorfile31']['name'];
								$mentorfiletempname = $_FILES['usermentorfile31']['tmp_name'];
								
								$mentor1array = setAdminFields($name,$user_id,"mentorinspfiles3",$mentorisnpfile,$mentorformtype,$_POST['mentor3'],$_POST['mentori3_date1'],$_POST['mentori3_date2'],$mentorfilename,$mentorfiletempname,$updated_by_user_id,$updated_by_user_name);	
								
							}
							if($_FILES['usermentorfile32']['name']!='')
							{
								$mentorformtype = $_POST['mentorformtype32'];
								$mentorisnpfile = 'mentorinspfile32';
								$mentorfilename = $_FILES['usermentorfile32']['name'];
								$mentorfiletempname = $_FILES['usermentorfile32']['tmp_name'];
								
								$mentor1array = setAdminFields($name,$user_id,"mentorinspfiles3",$mentorisnpfile,$mentorformtype,$_POST['mentor3'],$_POST['mentori3_date1'],$_POST['mentori3_date2'],$mentorfilename,$mentorfiletempname,$updated_by_user_id,$updated_by_user_name);							
							}
							else{
								$mentorformtype = '';
								$mentorisnpfile = '';
								
								$mentor1array = setAdminFields($name,$user_id,"mentorinspfiles3",$mentorisnpfile,$mentorformtype,$_POST['mentor3'],$_POST['mentori3_date1'],$_POST['mentori3_date2'],$mentorfilename,$mentorfiletempname,$updated_by_user_id,$updated_by_user_name);							
							}										
					  }
					  if($name == 'mentorinspfiles6')
					  {	
							if($_FILES['usermentorfile61']['name']!='')
							{
								$mentorformtype = $_POST['mentorformtype61'];
								$mentorisnpfile = 'mentorinspfile61';
								$mentorfilename = $_FILES['usermentorfile61']['name'];
								$mentorfiletempname = $_FILES['usermentorfile61']['tmp_name'];
								
								$mentor1array = setAdminFields($name,$user_id,"mentorinspfiles6",$mentorisnpfile,$mentorformtype,$_POST['mentor6'],$_POST['mentori6_date1'],$_POST['mentori6_date2'],$mentorfilename,$mentorfiletempname,$updated_by_user_id,$updated_by_user_name);	
								
							}
							if($_FILES['usermentorfile62']['name']!='')
							{
								$mentorformtype = $_POST['mentorformtype62'];
								$mentorisnpfile = 'mentorinspfile62';
								$mentorfilename = $_FILES['usermentorfile62']['name'];
								$mentorfiletempname = $_FILES['usermentorfile62']['tmp_name'];
								
								$mentor1array = setAdminFields($name,$user_id,"mentorinspfiles6",$mentorisnpfile,$mentorformtype,$_POST['mentor6'],$_POST['mentori6_date1'],$_POST['mentori6_date2'],$mentorfilename,$mentorfiletempname,$updated_by_user_id,$updated_by_user_name);							
							}
							else{
								$mentorformtype = '';
								$mentorisnpfile = '';
								
								$mentor1array = setAdminFields($name,$user_id,"mentorinspfiles6",$mentorisnpfile,$mentorformtype,$_POST['mentor6'],$_POST['mentori6_date1'],$_POST['mentori6_date2'],$mentorfilename,$mentorfiletempname,$updated_by_user_id,$updated_by_user_name);							
							}										
					  }
					  
				   }
				else{		
				   
					   if($_FILES['usermentorfile11']['name'])
					   {	
							$mentorformtype = $_POST['mentorformtype11'];
							$mentor_name = $_POST['mentor1'];
							
							$mentor_insp_date = $_POST['mentori1_date1'] ;
							
							$mentorfilename = $_FILES["usermentorfile11"]["name"];
							
							$mentorfiletempname = $_FILES["usermentorfile11"]["tmp_name"];
								 
							$mentor_approval_date = $_POST['mentori1_date2'] ;
							
							uploadFiles("mentorinspfile11","mentorinspfiles1","Mentored Insp 1",$mentorformtype,$mentor_name,$mentor_insp_date,$mentor_approval_date,$mentorfilename,$mentorfiletempname,'',$updated_by_user_id,$updated_by_user_name);				   
					   }
					   
					   if($_FILES['usermentorfile12']['name'])
					   {	
							$mentorformtype = $_POST['mentorformtype12'];
							$mentor_name = $_POST['mentor1'];
							
							$mentor_insp_date = $_POST['mentori1_date1'] ;
								 
							$mentor_approval_date = $_POST['mentori1_date2'] ;
							
							$mentorfilename = $_FILES["usermentorfile12"]["name"];
							
							$mentorfiletempname = $_FILES["usermentorfile12"]["tmp_name"];
							
							uploadFiles("mentorinspfile12","mentorinspfiles1","Mentored Insp 1",$mentorformtype,$mentor_name,$mentor_insp_date,$mentor_approval_date,$mentorfilename,$mentorfiletempname,'',$updated_by_user_id,$updated_by_user_name);				   
					   }
					   

					   if($_FILES['usermentorfile21']['name'])
					   {	
							$mentorformtype = $_POST['mentorformtype21'];
							$mentor_name = $_POST['mentor2'];
							
							$mentor_insp_date = $_POST['mentori2_date1'] ;
								 
							$mentor_approval_date = $_POST['mentori2_date2'] ;
							
							$mentorfilename = $_FILES["usermentorfile21"]["name"];
							
							$mentorfiletempname = $_FILES["usermentorfile21"]["tmp_name"];
							
							uploadFiles("mentorinspfile21","mentorinspfiles2","Mentored Insp 2",$mentorformtype,$mentor_name,$mentor_insp_date,$mentor_approval_date,$mentorfilename,$mentorfiletempname,'',$updated_by_user_id,$updated_by_user_name);		
							
					   }
					   
					   if($_FILES['usermentorfile22']['name'])
					   {	
							$mentorformtype = $_POST['mentorformtype22'];
							$mentor_name = $_POST['mentor2'];
							
							$mentor_insp_date = $_POST['mentori2_date1'] ;
								 
							$mentor_approval_date = $_POST['mentori2_date2'] ;
							
							$mentorfilename = $_FILES["usermentorfile22"]["name"];
							
							$mentorfiletempname = $_FILES["usermentorfile22"]["tmp_name"];
							
							uploadFiles("mentorinspfile22","mentorinspfiles2","Mentored Insp 2",$mentorformtype,$mentor_name,$mentor_insp_date,$mentor_approval_date,$mentorfilename,$mentorfiletempname,'',$updated_by_user_id,$updated_by_user_name);						   
					   }		
					   
					   
					   if($_FILES['usermentorfile31']['name'])
					   {	
							$mentorformtype = $_POST['mentorformtype31'];
							$mentor_name = $_POST['mentor3'];
							
							$mentor_insp_date = $_POST['mentori3_date1'] ;
								 
							$mentor_approval_date = $_POST['mentori3_date2'] ;
							
							$mentorfilename = $_FILES["usermentorfile31"]["name"];
							
							$mentorfiletempname = $_FILES["usermentorfile31"]["tmp_name"];
							
							uploadFiles("mentorinspfile31","mentorinspfiles3","Mentored Insp 3",$mentorformtype,$mentor_name,$mentor_insp_date,$mentor_approval_date,$mentorfilename,$mentorfiletempname,'',$updated_by_user_id,$updated_by_user_name);		
							
					   }
					   
					   if($_FILES['usermentorfile32']['name'])
					   {	
							$mentorformtype = $_POST['mentorformtype32'];
							$mentor_name = $_POST['mentor3'];
							
							$mentor_insp_date = $_POST['mentori3_date1'] ;
								 
							$mentor_approval_date = $_POST['mentori3_date2'] ;
							
							$mentorfilename = $_FILES["usermentorfile32"]["name"];
							
							$mentorfiletempname = $_FILES["usermentorfile32"]["tmp_name"];
							
							uploadFiles("mentorinspfile32","mentorinspfiles3","Mentored Insp 3",$mentorformtype,$mentor_name,$mentor_insp_date,$mentor_approval_date,$mentorfilename,$mentorfiletempname,'',$updated_by_user_id,$updated_by_user_name);						   
					   }
					   
					   if($_FILES['usermentorfile61']['name'])
					   {	
							$mentorformtype = $_POST['mentorformtype61'];
							$mentor_name = $_POST['mentor6'];
							
							$mentor_insp_date = $_POST['mentori6_date1'] ;
								 
							$mentor_approval_date = $_POST['mentori6_date2'] ;
							
							$mentorfilename = $_FILES["usermentorfile61"]["name"];
							
							$mentorfiletempname = $_FILES["usermentorfile61"]["tmp_name"];
							
							uploadFiles("mentorinspfile61","mentorinspfiles6","Graded Field Evaluation",$mentorformtype,$mentor_name,$mentor_insp_date,$mentor_approval_date,$mentorfilename,$mentorfiletempname,'',$updated_by_user_id,$updated_by_user_name);		
							
					   }
					   
					   if($_FILES['usermentorfile62']['name'])
					   {	
							$mentorformtype = $_POST['mentorformtype62'];
							$mentor_name = $_POST['mentor6'];
							
							$mentor_insp_date = $_POST['mentori6_date1'] ;
								 
							$mentor_approval_date = $_POST['mentori6_date2'] ;
							
							$mentorfilename = $_FILES["usermentorfile62"]["name"];
							
							$mentorfiletempname = $_FILES["usermentorfile62"]["tmp_name"];
							
							uploadFiles("mentorinspfile62","mentorinspfiles6","Graded Field Evaluation",$mentorformtype,$mentor_name,$mentor_insp_date,$mentor_approval_date,$mentorfilename,$mentorfiletempname,'',$updated_by_user_id,$updated_by_user_name);						   
					   }

				}	  

			}

else if(isset($_POST['total_files_upload_test'])){
	foreach($_FILES as $key => $val){
		
		$user = $_SESSION['first_name'].' '.$_SESSION['last_name'];
		
				$index = explode("-",$key);
				$index = $index[1];
				
					if(isset($_POST['name'.$index])){
						
						$image_path = $_POST['name'.$index];
						
						//$image_path = $_POST['projectc_files_path'.$i];
				
						echo $file_path = "../".$image_path;
						echo "<br>";
						if (!file_exists($file_path)) 
						{
							mkdir($file_path, 0777, true);
						}
						
						if (move_uploaded_file($val['tmp_name'], __DIR__ . '/'.$file_path.'/' . $val['name'])) {
							
							echo "File Uploaded: ". $val['name']."<br>";
						

							
							// if admins or RFI are using this page then get rater id from project
							if ($_SESSION['user_level'] == 2 || $_SESSION['user_level'] == 11 || $_SESSION['user_level'] == 4 || $_SESSION['user_level'] == 5) {
							$query="SELECT rater_id FROM projectsandratings WHERE id = '$project_id'";
							$result_raterid=@mysql_query($query);
							$rater_id=@mysql_result($result_raterid,0,"rater_id");
							} else {
							$rater_id=$_SESSION['user_id'];
							}
						

							
							$project_name_query = mysql_fetch_array(mysql_query("select project_name from projectsandratings where id = '$project_id' "));
							
							$project_name = $project_name_query['project_name'];
							
							$valfilename = $val['name'];

							if($rater_id=='' && $rater_id==0){ } else{
							$filesnotesquery="INSERT INTO rater_notes (rater_id, notes, user, category)	VALUES ('$rater_id', 'Files uploaded : $valfilename  For project $project_name ', '$user', 'Ratings')";
							mysql_query($filesnotesquery) or die(mysql_error());
							}
							
							
						} else {
							echo "<span style='color:red'>File upload fail !!! Try Again...</span>";
						}
					}
				//$i++;
			}
}	
			
else
{	

	if(!empty($_FILES))
	{

		$total_plans = $_POST['total_plans'];
		$total_files  = $_POST['total_files'];


							$user = $_SESSION['first_name'].' '.$_SESSION['last_name'];
							
/* 							if($user == 'Jesus Otero' || $user == 'Esteban Puerta') { // green light taff
									$user = 'BER QA';
								} */
							


		if(isset($total_files))	
		{
			for($i = 1 ; $i<= $total_files; $i++)
			{
				$ii=0;
				if(is_uploaded_file($_FILES['file_upload_project_rating'.$i]['tmp_name'][$ii])) 
				{
					$image_path = $_POST['projectc_files_path'.$i];
				
					$file_path = "../".$image_path;
					if (!file_exists($file_path)) 
					{
						mkdir($file_path, 0777, true);
					}
					$file_name = $_FILES['file_upload_project_rating'.$i]['name'];
					foreach($file_name as $key=>$val)
					{
					
						$sourcePath = $_FILES['file_upload_project_rating'.$i]['tmp_name'][$key];
						$targetPath = $file_path.'/'.$val;
						
						if(move_uploaded_file($sourcePath,$targetPath)) {
							echo "File Uploaded: ". $val."<br>";
							
							
							$project_id=$_GET['project_id'];
							
							// if admins or RFI are using this page then get rater id from project
							if ($_SESSION['user_level'] == 2 || $_SESSION['user_level'] == 11 || $_SESSION['user_level'] == 4 || $_SESSION['user_level'] == 5) {
							$query="SELECT rater_id FROM projectsandratings WHERE id = '$project_id'";
							$result_raterid=@mysql_query($query);
							$rater_id=@mysql_result($result_raterid,0,"rater_id");
							} else {
							$rater_id=$_SESSION['user_id'];
							}
						

							
							$project_name_query = mysql_fetch_array(mysql_query("select project_name from projectsandratings where id = '$project_id' "));
							
							$project_name = $project_name_query['project_name'];

							if($rater_id=='' && $rater_id==0){ } else{
							$filesnotesquery="INSERT INTO rater_notes (rater_id, notes, user, category)	VALUES ('$rater_id', 'Files uploaded :  $val For project $project_name ', '$user', 'Ratings')";
							mysql_query($filesnotesquery);
							}
							
						}
						else{
							echo "<span style='color:red'>File upload fail !!! Try Again...</span>";
						}
					}
				}
			}
		}
		if(isset($total_plans))	
		{
			$user_id = $_SESSION['user_id'];
			$query = "select company_id from users where id = ".$user_id;
			$result = mysql_query($query);
			$row = mysql_fetch_assoc($result);
			$company_id = $row['company_id'];
		
			$total_plans = $_POST['total_plans'];
			$plan_file_path =$_POST['plan_files_path'];

			for($i =1; $i<=$total_plans; $i++)
			{
				$ii=0;
				if(is_uploaded_file($_FILES['file_upload_project_rating'.$i]['tmp_name'][$ii])) 
				{
					$plan_name = $_POST['plan_name'.$i];
					$query = "INSERT INTO plan(planname,rater_id, company_id) VALUES ('".$plan_name."','".$user_id."','".$company_id."')";
					$result = mysql_query($query);
					$plan_id = mysql_insert_id();
			
					$file_arr = $_FILES['file_upload_project_rating'.$i]['name'];
					foreach($file_arr as $key=>$val)
					{
						$file_temp_arr = $_FILES['file_upload_project_rating'.$i]['tmp_name'][$key];
						$file_temp_type = $_FILES['file_upload_project_rating'.$i]['type'][$key];
						$file_temp_size = $_FILES['file_upload_project_rating'.$i]['size'][$key];
						
						$file_path = "../".$plan_file_path.'/'.$plan_id;
						
						if (!file_exists($file_path)) 
						{
							mkdir($file_path, 0777, true);
						}
						
						$uploadfile = $file_path.'/'.$val;
			
						if (move_uploaded_file($file_temp_arr, $uploadfile)) {
							echo $file_upload_success =  "File uploaded successfully:".$val."<br>";
							$file_actual_path = $plan_file_path.'/'.$plan_id;
							$query = "update plan set name = '".$val."', type = '".$file_temp_type."', size = '".$file_temp_size."' where id = ".$plan_id;
							$result = mysql_query($query);
							
							$planname = $_POST['plan_name'.$i];
							
							//echo "plan name".$planname."@";
							
							$rater_id=$_SESSION['user_id'];
							
							$notesquery="INSERT INTO rater_notes (rater_id, notes, user, category)	VALUES ('$rater_id', 'Plan $planname created', '$user', 'Ratings')";
							mysql_query($notesquery);
							
						} else {
							echo $file_upload_error =  "Opps there is some error, contact developer.";
						}
					}
				}
			}

		}

	}

} 

?>