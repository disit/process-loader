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
?>
<html>
    <head>
     <title>Process Loader</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="css/bootstrap.min.css">
          <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
          <link href="css/bootstrap.css" rel="stylesheet">
         <link href="css/loader.css" rel="stylesheet">
</head>
<body>
<script type="text/javascript">
var x = 0;

var myTimer = setInterval(function() { 
$.ajax({
	url:'aggiornaUplaoadedFiles.php',
	type: "GET",
	success: function(){
		console.log("Passaggio Riuscito");
	}
});
}, 60000);


//
</script>
</body>
</html>