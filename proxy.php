<?php

    $url = "https://api.thingspeak.com/channels/{$_GET["channelId"]}/feeds.{$_GET["type"]}?";
    $queryString = $_SERVER['QUERY_STRING'];

    $deb = true;
	foreach ($_GET as $key => $value) {
		if($key != "channelId" && $key != "type"){
			if ($deb == false)	
				$url .= '&'; 
		    $url .= $key . '=' . $value;
			$deb = false;
		}	
	}
	
	$string = file_get_contents($url);
	header('content-type:application/json');
	echo $string;
?>