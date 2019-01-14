<?php			
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