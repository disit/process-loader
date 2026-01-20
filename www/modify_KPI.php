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
			$verify = "SELECT COUNT(*) AS cnt FROM processloader_db.DashboardWizard WHERE high_level_type='KPI' AND sub_nature=? AND nature=? AND unique_name_id=?";
			$stmt_ver = mysqli_prepare($link, $verify);
			if (!$stmt_ver) {
				die ("Operation failed".mysqli_error());
			}
			$valuename_check = isset($_POST['valuename']) ? $_POST['valuename'] : '';
			mysqli_stmt_bind_param($stmt_ver, "sss", $subnature, $nature, $valuename_check);
			mysqli_stmt_execute($stmt_ver);
			$res_ver = mysqli_stmt_get_result($stmt_ver);
			$row_ver = mysqli_fetch_assoc($res_ver);
			$total_rows = isset($row_ver['cnt']) ? (int)$row_ver['cnt'] : 0;
			mysqli_stmt_close($stmt_ver);
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
					$query_reg = "UPDATE processloader_db.DashboardWizard SET nature=?, sub_nature=?, unique_name_id=?, unit=?, ownership=?, description=?, longitudes=?, low_level_type=?, latitudes=?, parameters=?, Info=? WHERE Id=?";
					$stmt_reg = mysqli_prepare($link, $query_reg);
					if (!$stmt_reg) {
						die ("Operation failed".mysqli_error());
					}
					$valuename = isset($_POST['valuename']) ? $_POST['valuename'] : '';
					$datatype = isset($_POST['datatype']) ? $_POST['datatype'] : '';
					$ownership = isset($_POST['ownership']) ? $_POST['ownership'] : '';
					$description = isset($_POST['description']) ? $_POST['description'] : '';
					$longitudes = isset($_POST['longitudes']) ? $_POST['longitudes'] : '';
					$valuetype = isset($_POST['valuetype']) ? $_POST['valuetype'] : '';
					$latitudes = isset($_POST['latitudes']) ? $_POST['latitudes'] : '';
					$parameters = isset($_POST['paramters']) ? $_POST['paramters'] : '';
					$info = isset($_POST['info']) ? $_POST['info'] : '';
					$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
					mysqli_stmt_bind_param(
						$stmt_reg,
						"sssssssssssi",
						$nature,
						$subnature,
						$valuename,
						$datatype,
						$ownership,
						$description,
						$longitudes,
						$valuetype,
						$latitudes,
						$parameters,
						$info,
						$id
					);
					$exec_ok = mysqli_stmt_execute($stmt_reg);
					mysqli_stmt_close($stmt_reg);
					if (!$exec_ok) {
						die ("Operation failed".mysqli_error());
					}
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
						$query_reg = "UPDATE processloader_db.DashboardWizard SET last_date=?, last_value=? WHERE Id=?";
						$stmt_reg = mysqli_prepare($link, $query_reg);
						if (!$stmt_reg) {
							die ("Operation failed".mysqli_error());
						}
						$last_date = isset($_POST['last_date']) ? $_POST['last_date'] : '';
						$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
						mysqli_stmt_bind_param($stmt_reg, "ssi", $last_date, $var, $id);
						$exec_ok = mysqli_stmt_execute($stmt_reg);
						mysqli_stmt_close($stmt_reg);
						if (!$exec_ok) {
							die ("Operation failed".mysqli_error());
						}
						//INSERIMENTO IN KPI_values
						$query_ins = "INSERT INTO processloader_db.kpi_values (id, kpi, date, value) VALUES (NULL,?,?,?)";
						$stmt_ins = mysqli_prepare($link, $query_ins);
						if (!$stmt_ins) {
							die ("Operation failed".mysqli_error());
						}
						mysqli_stmt_bind_param($stmt_ins, "iss", $id, $last_date, $var);
						$exec_ok = mysqli_stmt_execute($stmt_ins);
						mysqli_stmt_close($stmt_ins);
						if (!$exec_ok) {
							die ("Operation failed".mysqli_error());
						}
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
