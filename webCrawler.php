<?php

require '/librerias/simple_html_dom.php';
//$c = array( );
function get_links($url){
	//global $c;
	$c = array( );
	$input = @file_get_contents($url);
	$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
	preg_match_all("/$regexp/siU", $input, $matches);
	$base_url = parse_url($url,PHP_URL_HOST);
	$l = $matches[2];
	foreach ($l as $link) {
		if(strpos($link, "#")){
			$link = substr($link,0,strpos($link, "#"));
		}
		if (substr($link,0,1) ==".") {
			$link = substr($link, 1);
		}
		if (substr($link,0,7) == "http://") {
			$link =$link;
		}
		else if (substr($link,0,8) == "https://") {
			$link =$link;
		}
		else if (substr($link,0,2) == "//") {
			$link =substr($link, 2);
		}
		else if (substr($link,0,1) == "#") {
			$link =$url;
		}
		else if (substr($link,0,7) == "mailto:") {
			$link = "[".$link."]";
		}	
		else{
			if(substr($link, 0 ,1) != "/"){
				$link = $base_url."/".$link;
			}
			else{
				$link = $base_url.$link;
			}
		}
		if(substr($link, 0, 7 ) !="http://" && substr($link, 0,8) !="https://" && substr($link, 0, 1) !="["){
			if (substr($url,0, 8) =="https://") {
				$link = "https://".$link;
			}
			else{
				$link = "http://".$link;
			}

		}

		//echo $link."<br/>";
		if(!in_array($link, $c)){
			array_push($c,$link);
		}
	}
	return $c;

}
/*
get_links("http://www.marca.com/");

foreach ($c as $page) {
	echo $page."</br>";
}
*/

$myfile = fopen("listaPaginas.txt", "r") or die("Unable to open file!");
$texto = fread($myfile,filesize("listaPaginas.txt"));
$arrayTexto = split("
",$texto);
fclose($myfile);

foreach ($arrayTexto  as $sitioWeb) {
	echo "<h1>----------------------------".$sitioWeb."..............................</h1>";
	$d = get_links($sitioWeb);
	foreach ($d as $page) {
			echo $page."</br>";
	}
	//$html  = file_get_html($sitioWeb);

	/*
	foreach ($html-> find('p') as $i) {
		echo $i;
	}
	*/



} 

 
?> 