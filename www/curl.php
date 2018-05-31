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
		
			function url_get($url)
						{
							$ch = curl_init();    // initialize curl handle
							curl_setopt($ch, CURLOPT_URL,$url); // set url to post 
							curl_setopt($ch, CURLOPT_FAILONERROR, 1);
							curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
							curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
							curl_setopt($ch, CURLOPT_TIMEOUT, 10); // times out after 10s
							$urlcontent = curl_exec($ch); 
							curl_close($ch); 
							return($urlcontent);
						} 
?>