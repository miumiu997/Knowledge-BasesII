<?php

require '/librerias/simple_html_dom.php';


$arrayHTMLS = array();
ini_set('memory_limit', '-1');

function get_links($url){
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


/*Funcion que recibe como parametro el lugar donde esta el archivo .txt y retorna la lista de sitios web separados por un  enter*/
function getStartLinks($direction){
	$direction = "listaPaginas.txt";
	$myfile = fopen($direction, "r") or die("Unable to open file!");
	$texto = fread($myfile,filesize("listaPaginas.txt"));
	$arrayTexto = split("
",$texto);
	fclose($myfile);
	return $arrayTexto;
}

function getHTML($URL){
	$textoHTML = "";
	//create object
	$html=new simple_html_dom();
	 
	//load specific URL
	$html->load_file($URL);
	echo "<h1>".sizeof($html);
	if(isset($html)){
		foreach ($html->@find("title") as $i) {
			$textoHTML.= $i;
		}
		foreach ($html->@find("p") as $i) {
			$textoHTML.= $i;	
		}

		foreach ($html->@find("h1") as $i) {
			$textoHTML.= $i;
		}
		foreach ($html->@find("h2") as $i) {
			$textoHTML.= $i;
		}
		foreach ($html->@find("h3") as $i) {
			$textoHTML.= $i;
		}
		foreach ($html->@find("h4") as $i) {
			$textoHTML.= $i;
		}
		foreach ($html->@find("h5") as $i) {
			$textoHTML.= $i;
		}
		foreach ($html->@find("h6") as $i) {
			$textoHTML.= $i;
		}
	}
		
		return $textoHTML;

}



function webCrawler($direccionStartLink,$timeLimit){
	$listaDeLinksInicio = getStartLinks($direccionStartLink); /*arreglo de la lista de links*/
	$listaLinksOfficial = array();
	foreach ($listaDeLinksInicio as $linkInicio) {
		$listaLinks = get_links($linkInicio);
		foreach ($listaLinks as $linksito) {
			array_push($listaLinksOfficial, $linksito);
		}
	}

 	set_time_limit ( $timeLimit );
	return webCrawlerAux($listaLinksOfficial);
}
$contador = 0;	
function webCrawlerAux($arrayListaWeb){
		global $contador;
		global $arrayHTMLS;
		foreach ($arrayListaWeb as  $paginaWeb) {
			$codigoHtml = getHTML($paginaWeb);
			echo "<h5>".$contador.")".$paginaWeb."</h5>";
			echo $codigoHtml;
			array_push($arrayHTMLS, $codigoHtml);
		$contador=$contador+1;	
		}
		if (isset($newFinalWebList)) {
			unset($newFinalWebList);
		}
		$newFinalWebList = array();
		foreach ($arrayListaWeb as $key) {
			$newWebList = get_links($key);
			foreach ($newWebList as $paginita) {
				array_push($newFinalWebList, $paginita);
			}
		}
		unset($arrayListaWeb);

			webCrawlerAux($newFinalWebList);
		/*
		foreach ($arrayListaWeb as $key ) {
			echo "<h3>".$key."</h3>";
		}*/
}

webCrawler("listaPaginas.txt",900000000000000000000);
//echo getHTML("https://www.pinterest.com/pin/330029478925571465/");		
?> 