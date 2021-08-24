<?php
/* Resource Manager - Process Loader
 Copyright (C) 2018 DISIT Lab http://www.disit.org - University of Florence
 
 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as
 published by the Free Software Foundation, either version 3 of the
 License, or (at your option) any later version.
 
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU Affero General Public License for more details.
 
 You should have received a copy of the GNU Affero General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>. */

$file=$_GET['file'];

include_once 'datatablemanager_sqlClient.php';
include_once 'datatablemanager_sqlQueryManager.php';

$file_uploaded="false";

$query=getDtmSelectUploadedFilesQuery();
$uploaded_files=executeGetUploadedFiles($query);

if(in_array($file, $uploaded_files)){
    $file_uploaded="true";
}

echo $file_uploaded;
?>