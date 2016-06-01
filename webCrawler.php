<?php
ini_set('display_errors', 'On');
$arrayHTMLS = array();
ini_set('memory_limit', '-1'); 
$myfile = fopen("answer.txt", "w");
/*

Script para crear la tabla titles:
CREATE TABLE TITLES(id int(30) not null AUTO_INCREMENT,title varchar(300),url varchar(300),PRIMARY KEY(id));

*/


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
function saveTitles(){
	$servername = "localhost"; 
	$username = "root"; 
	$password = "cloudera"; 
	$db = "KNOWLEDGE";
	$conn = new mysqli("localhost","root","cloudera", "KNOWLEDGE");

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$query = "select url from CountWordInURL group by url" ;
	$result = $conn->query($query); 

	 while ($row = $result->fetch_assoc()){ 
	 $url= $row["url"];
	 $title = get_Title($url);
	 //TITLES(id int(30) not null AUTO_INCREMENT,title varchar(300),url varchar(300),PRIMARY KEY(id))
	 $query = "INSERT INTO TITLES(title,url) values('".$title."','".$url."');"; 
		$conn->query($query);
		
	 }   
}
function get_Title($url){
	
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
		$title = $title->nodeValue;
		

	}
	return $title;
}


function getHTML($url){
	global $myfile;
	$textoHTML = "{".$url."}";
	//fwrite($myfile, "{".$url."}");
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
			
			//$textoHTML= $textoHTML."{".$title->nodeValue."}";
			$textotitle= split(" ", $title->nodeValue);
			foreach ($textotitle as $word) {
				if (strlen($word)>1 && strpos($word, "\n")==false) { 
					$word = preg_replace("/\s|&nbsp;/",'',$word);
					//$textoHTML = $textoHTML."[title]"."{".limpiarPalabra($word)."}";
					fwrite($myfile,"{title}"."{".limpiarPalabra($word)."}{".$url."}\n");

				}
			}
	}
	$spans = $xpath->evaluate("/html/body//span");
	for ($i = 0; $i < $spans->length; $i++) {
		$span = $spans->item($i);
			
			$textoSpan= split(" ", $span->nodeValue);
			foreach ($textoSpan as $word) {
				if (strlen($word)>1 && strpos($word, "\n")==false) {
					$word = preg_replace("/\s|&nbsp;/",'',$word);
					//$textoHTML = $textoHTML."[span]"."{".limpiarPalabra($word)."}";
					fwrite($myfile,"{span}"."{".limpiarPalabra($word)."}{".$url."}\n");

				}
			}
	}
	$ps = $xpath->evaluate("/html/body//p");
	for ($i = 0; $i < $ps->length; $i++) {
		$p = $ps->item($i);
			
			$textoP = split(" ", $p->nodeValue);
			foreach ($textoP as $word ) {
				if (strlen($word)>1 && strpos($word, "\n")==false) {

					$word = preg_replace("/\s|&nbsp;/",'',$word);
					//$textoHTML = $textoHTML."[p]"."{".limpiarPalabra($word)."}";
					fwrite($myfile,"{p}"."{".limpiarPalabra($word)."}{".$url."}\n");	
				}
			}
	}

	$h1s = $xpath->evaluate("/html/body//h1");
	for ($i = 0; $i < $h1s->length; $i++) {
		$h1 = $h1s->item($i);
			

			$textoh1 = split(" ", $h1->nodeValue);
			foreach ($textoh1 as $word) {
				if (strlen($word)>1 && strpos($word, "\n")==false) {
					
					$word = preg_replace("/\s|&nbsp;/",'',$word);
					//$textoHTML = $textoHTML."[h1]"."{".limpiarPalabra($word)."}";
					fwrite($myfile,"{h1}"."{".limpiarPalabra($word)."}{".$url."}\n");	
				}
			}
	}
	$h2s = $xpath->evaluate("/html/body//h2");
	for ($i = 0; $i < $h2s->length; $i++) {
		$h2 = $h2s->item($i);
			
			$textoh2 = split(" ", $h2->nodeValue);
			foreach ($textoh2 as $word) {
				if (strlen($word)>1 && strpos($word, "\n")==false) {

					$word = preg_replace("/\s|&nbsp;/",'',$word);
					//$textoHTML = $textoHTML."[h2]"."{".limpiarPalabra($word)."}";
					fwrite($myfile,"{h2}"."{".limpiarPalabra($word)."}{".$url."}\n");
				}
			}
	}
	$h3s = $xpath->evaluate("/html/body//h3");
	for ($i = 0; $i < $h3s->length; $i++) {
		$h3 = $h3s->item($i);
			$textoh3 = split(" ", $h3->nodeValue);
			foreach ($textoh3 as $word) {
				if (strlen($word)>1 && strpos($word, "\n")==false) {

					$word = preg_replace("/\s|&nbsp;/",'',$word);
					//$textoHTML = $textoHTML."[h3]"."{".limpiarPalabra($word)."}";
					fwrite($myfile,"{h3}"."{".limpiarPalabra($word)."}{".$url."}\n");
				}
			}
	}
	$h4s = $xpath->evaluate("/html/body//h4");
	for ($i = 0; $i < $h4s->length; $i++) {
		$h4 = $h4s->item($i);
			$textoh4 = split(" ", $h4->nodeValue);
			foreach ($textoh4 as $word) {
				if (strlen($word)>1 && strpos($word, "\n")==false) {

					$word = preg_replace("/\s|&nbsp;/",'',$word);
					//$textoHTML = $textoHTML."[h4]"."{".limpiarPalabra($word)."}";
					fwrite($myfile,"{h4}"."{".limpiarPalabra($word)."}{".$url."}\n");
				}
			}
	}
	$h5s = $xpath->evaluate("/html/body//h5");
	for ($i = 0; $i < $h5s->length; $i++) {
		$h5 = $h5s->item($i);
			$textoh5 = split(" ", $h5->nodeValue);
			foreach ($textoh5 as $word) {
				if (strlen($word)>1 && strpos($word, "\n")==false) {

					$word = preg_replace("/\s|&nbsp;/",'',$word);
					//$textoHTML = $textoHTML."[h5]"."{".limpiarPalabra($word)."}";
					fwrite($myfile,"{h5}"."{".limpiarPalabra($word)."}{".$url."}\n");
				}
			}
	}
	$h6s = $xpath->evaluate("/html/body//h6");
	for ($i = 0; $i < $h6s->length; $i++) {
		$h6 = $h6s->item($i);
			$textoh6 = split(" ", $h6->nodeValue);
			foreach ($textoh6 as $word) {
				if (strlen($word)>1 && strpos($word, "\n")==false) {

					$word = preg_replace("/\s|&nbsp;/",'',$word);
					//$textoHTML = $textoHTML."[h6]"."{".limpiarPalabra($word)."}";
					fwrite($myfile,"{h6}"."{".limpiarPalabra($word)."}{".$url."}\n");
				}
			}
	}
	$pres = $xpath->evaluate("/html/body//pre");
	for ($i = 0; $i < $h6s->length; $i++) {
		$pre = $pres->item($i);
			$textopre = split(" ", $pre->nodeValue);
			foreach ($textopre as $word) {
				if (strlen($word)>1 && strpos($word, "\n")==false) {

					$word = preg_replace("/\s|&nbsp;/",'',$word);
					//$textoHTML = $textoHTML."[pre]"."{".limpiarPalabra($word)."}";
					fwrite($myfile,"{pre}"."{".limpiarPalabra($word)."}{".$url."}\n");
				}
			}
	}
	$blockquotes = $xpath->evaluate("/html/body//blockquote");
	for ($i = 0; $i < $blockquotes->length; $i++) {
		$blockquote = $blockquotes->item($i);
			$textoblockquote = split(" ", $blockquote->nodeValue);
			foreach ($textoblockquote as $word) {
				if (strlen($word)>1 && strpos($word, "\n")==false) {

					$word = preg_replace("/\s|&nbsp;/",'',$word);
					//$textoHTML = $textoHTML."[h6]"."{".limpiarPalabra($word)."}"; 
					fwrite($myfile,"{blockquote}"."{".limpiarPalabra($word)."}{".$url."}\n");
				}
			}
	}
	$lis = $xpath->evaluate("/html/body//li");
	for ($i = 0; $i < $lis->length; $i++) {
		$li = $lis->item($i);
			$textoli = split(" ", $li->nodeValue);
			foreach ($textoli as $word) {
				if (strlen($word)>1 && strpos($word, "\n")==false) {

					$word = preg_replace("/\s|&nbsp;/",'',$word);
					//$textoHTML = $textoHTML."[h6]"."{".limpiarPalabra($word)."}";
					fwrite($myfile,"{li}"."{".limpiarPalabra($word)."}{".$url."}\n");
				}
			}
	}
	$dls = $xpath->evaluate("/html/body//dl");
	for ($i = 0; $i < $dls->length; $i++) {
		$dl = $dls->item($i);
			$textodl = split(" ", $dl->nodeValue);
			foreach ($textodl as $word) {
				if (strlen($word)>1 && strpos($word, "\n")==false) {

					$word = preg_replace("/\s|&nbsp;/",'',$word);
					//$textoHTML = $textoHTML."[h6]"."{".limpiarPalabra($word)."}";
					fwrite($myfile,"{dl}"."{".limpiarPalabra($word)."}{".$url."}\n");
				}
			}
	}
	$dds = $xpath->evaluate("/html/body//dd");
	for ($i = 0; $i < $dds->length; $i++) {
		$dd = $dds->item($i);
			$textodd = split(" ", $dd->nodeValue);
			foreach ($textodd as $word) {
				if (strlen($word)>1 && strpos($word, "\n")==false) {

					$word = preg_replace("/\s|&nbsp;/",'',$word);
					//$textoHTML = $textoHTML."[h6]"."{".limpiarPalabra($word)."}";
					fwrite($myfile,"{dd}"."{".limpiarPalabra($word)."}{".$url."}\n");
				}
			}
	}
	$figcaptions = $xpath->evaluate("/html/body//figcaption");
	for ($i = 0; $i < $figcaptions->length; $i++) {
		$figcaption = $figcaptions->item($i);
			$textofigcaption = split(" ", $figcaption->nodeValue);
			foreach ($textofigcaption as $word) {
				if (strlen($word)>1 && strpos($word, "\n")==false) {

					$word = preg_replace("/\s|&nbsp;/",'',$word);
					//$textoHTML = $textoHTML."[h6]"."{".limpiarPalabra($word)."}";
					fwrite($myfile,"{figcaption}"."{".limpiarPalabra($word)."}{".$url."}\n");
				}
			}
	}
	$ems = $xpath->evaluate("/html/body//em");
	for ($i = 0; $i < $ems->length; $i++) {
		$em = $ems->item($i);
			$textoem = split(" ", $em->nodeValue);
			foreach ($textoem as $word) {
				if (strlen($word)>1 && strpos($word, "\n")==false) {

					$word = preg_replace("/\s|&nbsp;/",'',$word);
					//$textoHTML = $textoHTML."[h6]"."{".limpiarPalabra($word)."}";
					fwrite($myfile,"{em}"."{".limpiarPalabra($word)."}{".$url."}\n");
				}
			}
	}
	$strongs = $xpath->evaluate("/html/body//strong");
	for ($i = 0; $i < $strongs->length; $i++) {
		$strong = $strongs->item($i);
			$textostrong = split(" ", $strong->nodeValue);
			foreach ($textostrong as $word) {
				if (strlen($word)>1 && strpos($word, "\n")==false) {

					$word = preg_replace("/\s|&nbsp;/",'',$word);
					//$textoHTML = $textoHTML."[h6]"."{".limpiarPalabra($word)."}";
					fwrite($myfile,"{strong}"."{".limpiarPalabra($word)."}{".$url."}\n");
				}
			}
	}
	$smalls = $xpath->evaluate("/html/body//small");
	for ($i = 0; $i < $smalls->length; $i++) {
		$small = $smalls->item($i);
			$textosmall = split(" ", $small->nodeValue);
			foreach ($textosmall as $word) {
				if (strlen($word)>1 && strpos($word, "\n")==false) {

					$word = preg_replace("/\s|&nbsp;/",'',$word);
					//$textoHTML = $textoHTML."[h6]"."{".limpiarPalabra($word)."}";
					fwrite($myfile,"{small}"."{".limpiarPalabra($word)."}{".$url."}\n");
				}
			}
	}
	$cites = $xpath->evaluate("/html/body//cite");
	for ($i = 0; $i < $cites->length; $i++) {
		$cite = $cites->item($i);
			$textocite = split(" ", $cite->nodeValue);
			foreach ($textocite as $word) {
				if (strlen($word)>1 && strpos($word, "\n")==false) {

					$word = preg_replace("/\s|&nbsp;/",'',$word);
					//$textoHTML = $textoHTML."[h6]"."{".limpiarPalabra($word)."}";
					fwrite($myfile,"{cite}"."{".limpiarPalabra($word)."}{".$url."}\n");
				}
			}
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
				//echo $href->getAttribute('href')."</p>";
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
		//echo "<h1>............................".sizeof($arrayListaWeb).".......................</h1>";
		global $contador;
		global $arrayHTMLS;
		foreach ($arrayListaWeb as  $paginaWeb) {
			$codigoHtml = getHTML($paginaWeb);
			echo "<h5>".$contador.")".$paginaWeb."</h5>";
			//echo $codigoHtml;
			//array_push($arrayHTMLS, $codigoHtml);
		$contador=$contador+1;	
		}
		if (isset($newFinalWebList)) {
			unset($newFinalWebList);
		}
		$newFinalWebList = array();
		//$tamanoLista = sizeof($arrayListaWeb);
		foreach ($arrayListaWeb as $key) {
			//echo "Voy a buscar en la pagina: ".$key;
			$newWebList = get_links($key);
			foreach ($newWebList as $paginita) {
				array_push($newFinalWebList, $paginita);
			}
		}
		unset($arrayListaWeb);

			webCrawlerAux($newFinalWebList);
		
}



//webCrawler("listaPaginas.txt",900000000000000000000);
//get_links("http://resultados.as.com/resultados/ficha/deportista/casillas/390/");
saveTitles();
fclose("answer.txt");

function limpiarPalabra($palabra){
	$palabra = mb_strtoupper($palabra);
	$size = strlen($palabra);
	$i = 0;
	while($i < $size){
		if((ord($palabra[$i]) < 65 or ord($palabra[$i]) > 90) and (ord($palabra[$i]) < 48 or ord($palabra[$i]) > 57) ){
			$palabra = str_replace($palabra[$i],"",$palabra);
			$size = strlen($palabra);
		}
		else{
			$i+=1;
		}
	}
	return $palabra;
}

/*
// Para pasar a mayúsculas
$texto = strtoupper($texto)



*/


?>
