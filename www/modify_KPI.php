<?php
//include('config.php');
include('external_service.php');
/*
$link = mysqli_connect($host_kpi $username_kpi, $password_kpi);
	mysqli_set_charset($link, 'utf8');
	mysqli_select_db($link, $dbname_kpi);
	*/
if (isset ($_SESSION['username'])&& isset($_SESSION['role'])){	
$link = mysqli_connect($host_kpi, $username_kpi, $password_kpi) or die("failed to connect to server !!");
mysqli_set_charset($link, 'utf8');
mysqli_select_db($link, $dbname_kpi);	
//

if (isset($_REQUEST['type'])){
		$type=$_REQUEST['type'];
}

if ($type=='st'){
	//NOTHING
	//
			if (isset($_REQUEST['other_nature'])&&($_REQUEST['nature']=='Other...')){
						$nature0 = mysqli_real_escape_string($link, $_REQUEST['other_nature']);
						$nature = filter_var($nature0, FILTER_SANITIZE_STRING);
			}else{
				if (isset($_REQUEST['nature'])){
						$nature0 = mysqli_real_escape_string($link, $_REQUEST['nature']);
						$nature = filter_var($nature0, FILTER_SANITIZE_STRING);				
				}else{
					$nature = "";
				}
			}
			//
			if (isset($_REQUEST['other_subnature'])&&($_REQUEST['subnature'] =='Other...')){
				    $subnature0 = mysqli_real_escape_string($link, $_REQUEST['other_subnature']);
					$subnature = filter_var($subnature0, FILTER_SANITIZE_STRING);
			}else{
					if (isset($_REQUEST['subnature'])){
					$subnature0 = mysqli_real_escape_string($link, $_REQUEST['subnature']);
					$subnature = filter_var($subnature0, FILTER_SANITIZE_STRING);
					}else{
					$subnature = "";	
					}
			}
	
	//ESISTE GIà?
		$verify = "select COUNT(*) FROM processloader_db.DashboardWizard WHERE high_level_type='KPI' AND sub_nature='".$subnature."' AND nature='".$nature."' AND unique_name_id='".$_POST['valuename'] ."'";
		$res_ver = mysqli_query($link,$verify) or die ("Operation failed".mysqli_error());
		$count_ver = array();
								if ($res_ver->num_rows > 0) {
									while($row = mysqli_fetch_assoc($res_ver)){
										array_push($count_ver, $row);
									}
								}
								//var_dump($count_list);
								$total_rows = $count_ver[0]["COUNT(*)"];
				/*if ($total_rows > 0){
						if (isset($_REQUEST['showFrame'])){
							if ($_REQUEST['showFrame'] == 'false'){
									header ("location:KPI_editor.php?showFrame=false&error_unique_key=duplicated");
								}else{
									header ("location:KPI_editor.php?showFrame=true&error_unique_key=duplicated");
								}	
						}else{
							header ("location:KPI_editor.php?showFrame=true&error_unique_key=duplicated");
						}
						*/
		//}else{
				//
				$query_reg="UPDATE processloader_db.DashboardWizard SET nature='".$nature."', sub_nature='".$subnature."', unique_name_id='".$_POST['valuename']."', unit='".$_POST['datatype']."', ownership='".$_POST['ownership']."', description='".$_POST['description']."', longitudes='".$_POST['longitudes']."', low_level_type='".$_POST['valuetype']."', latitudes='".$_POST['latitudes']."', parameters='".$_POST['paramters']."', Info='".$_POST['info']."' WHERE Id='".$_POST['id']."'";
				echo ($query_reg);
				$query_registrazione = mysqli_query($link,$query_reg) or die ("Operation failed".mysqli_error());	
				mysqli_close($link);
				//
	//}
	//
}elseif ($type=='rt'){
	
	//FARE UN CHECK DEL TIPO DEL LAST VALUE
	
	$var0 = mysqli_real_escape_string($link, $_POST['last_value']);
	$var = filter_var($var0, FILTER_SANITIZE_STRING);
	//
	$value = gettype ($var);
	//
	$type_value0 = mysqli_real_escape_string($link, $_POST['type_val']);
	$type_value = filter_var($type_value0, FILTER_SANITIZE_STRING);
	//
	$check = 0;
	//
	
	if ($type_value == 'integer'){
		$var = (int)$var;	
	}
	
	if ($type_value == 'float'){
		$var = (float)$var;	
	}
	

	//
	echo ($value);

			//if ($check == 1){
					$query_reg="UPDATE processloader_db.DashboardWizard SET last_date='".$_POST['last_date']."', last_value='".$var."' WHERE Id='".$_POST['id']."'";
						$query_registrazione = mysqli_query($link,$query_reg) or die ("Operation failed".mysqli_error());
						//INSERIMENTO IN KPI_values
						$query_ins = "INSERT INTO processloader_db.kpi_values (id, kpi, date, value) VALUES (NULL,'".$_POST['id']."','".$_POST['last_date']."','".$var."')";
						$result_ins = mysqli_query($link,$query_ins) or die ("Operation failed".mysqli_error());
						//
						mysqli_close($link);
				//}else{
				//	echo ('ERROR!');
				//	//header ("location:page.php");
			//}
} else{
	//ERRORE
}

if (isset($_REQUEST['showFrame'])){
			if ($_REQUEST['showFrame'] == 'false'){
				header ("location:KPI_editor.php?showFrame=false");
			}else{
				header ("location:KPI_editor.php?showFrame=true");
			}	
}else{
	header ("location:KPI_editor.php?showFrame=true");
}
}else{
	header ("location:page.php");
}



?>