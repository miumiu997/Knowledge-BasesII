<?php

$arrayHTMLS = array();
ini_set('memory_limit', '-1');




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



function getHTML($url){
	$textoHTML = "";
	//create object
	$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ));
	$urlUsed = file_get_contents($url, false, stream_context_create($arrContextOptions));
	$dom = new DOMDocument();
	@$dom->loadHTML($urlUsed);
	$xpath = new DOMXPath($dom);

	$titles = $xpath->evaluate("/html//title");
	for ($i = 0; $i < $titles->length; $i++) {
		$title = $titles->item($i);
			
			$textoHTML.= $title->nodeValue;
	}
	$spans = $xpath->evaluate("/html/body//span");
	for ($i = 0; $i < $spans->length; $i++) {
		$span = $spans->item($i);
			
			$textoHTML.= $span->nodeValue;
	}
	$ps = $xpath->evaluate("/html/body//p");
	for ($i = 0; $i < $ps->length; $i++) {
		$p = $ps->item($i);
			
			$textoHTML.= $p->nodeValue;
	}

	$h1s = $xpath->evaluate("/html/body//h1");
	for ($i = 0; $i < $h1s->length; $i++) {
		$h1 = $h1s->item($i);
			
			$textoHTML.= $h1->nodeValue;
	}
	$h2s = $xpath->evaluate("/html/body//h2");
	for ($i = 0; $i < $h2s->length; $i++) {
		$h2 = $h2s->item($i);
			
			$textoHTML.= $h2->nodeValue;
	}
	$h3s = $xpath->evaluate("/html/body//h3");
	for ($i = 0; $i < $h3s->length; $i++) {
		$h3 = $h3s->item($i);
			
			$textoHTML.= $h3->nodeValue;
	}
	$h4s = $xpath->evaluate("/html/body//h4");
	for ($i = 0; $i < $h4s->length; $i++) {
		$h4 = $h4s->item($i);
			
			$textoHTML.= $h4->nodeValue;
	}
	$h5s = $xpath->evaluate("/html/body//h5");
	for ($i = 0; $i < $h5s->length; $i++) {
		$h5 = $h5s->item($i);
			
			$textoHTML.= $h5->nodeValue;
	}
	$h6s = $xpath->evaluate("/html/body//h6");
	for ($i = 0; $i < $h6s->length; $i++) {
		$h6 = $h6s->item($i);
			
			$textoHTML.= $h6->nodeValue;
	}
		
		return $textoHTML;

}




function get_links($url){
	$c = array( );
	
	$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ));
	$urlUsed = file_get_contents($url, false, stream_context_create($arrContextOptions));
 	$dom = new DOMDocument();
	@$dom->loadHTML($urlUsed);
 	$xpath = new DOMXPath($dom);
	$hrefs = $xpath->evaluate("/html/body//a");
 
	for ($i = 0; $i < $hrefs->length; $i++) {
		$href = $hrefs->item($i);
		if(substr($href->getAttribute('href'),0,4)=="http"){
			
			if(!in_array($href->getAttribute('href'), $c)){
				array_push($c,$href->getAttribute('href'));
			}

		}
	}

	
	return $c;

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
			//$codigoHtml = getHTML($paginaWeb);
			echo "<h5>".$contador.")".$paginaWeb."</h5>";
			//echo $codigoHtml;
			//array_push($arrayHTMLS, $codigoHtml);
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
		
}

webCrawler("listaPaginas.txt",900000000000000000000);


?>