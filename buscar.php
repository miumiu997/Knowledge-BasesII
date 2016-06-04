<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Case</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="stylesheet/style3.css?rand=1237">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body id = "searchBoy">
<form action="buscar.php" method="POST">
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a id="title" class="navbar-brand" href="#">Knowledge</a>
      <div id="search" class="input-group">
   	 <input name="input" id="input" type="text"></input><br><br>
   	 <input type="submit" class="button button2" value="Search"/>
      </div>
    </div>
  </div>
</nav>  
</form>

<div id="squares">  
<?php
	ini_set('display_errors', 'On');	
	$servername = "localhost"; 
	$username = "root"; 
	$password = "cloudera"; 
	$db = "KNOWLEDGE";
	$conn = new mysqli("localhost","root","cloudera", "KNOWLEDGE");

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 
   
	$TEXTO_BUSCAR = $_POST["input"];  
	$PALABRAS_SEPARADAS = explode(" ", $TEXTO_BUSCAR); 
	//echo $PALABRAS_SEPARADAS[0];  

	$query = "CREATE TABLE temporal SELECT url, count FROM CountWordInURL WHERE CountWordInURL.word Like '".$PALABRAS_SEPARADAS[0]."';"; 
	$conn->query($query);

	$i = 1;
	while($i < sizeof($PALABRAS_SEPARADAS)){ 
		$query = "INSERT INTO temporal(url, count) SELECT url, count FROM CountWordInURL WHERE CountWordInURL.word Like '".$PALABRAS_SEPARADAS[$i]."'; "; 
		$conn->query($query);
		$i++;
	} 	

	$finalResult = '';	
	$query = "CREATE TABLE temporal2 SELECT url, SUM(count) countTotalWords, COUNT(url) countWords FROM  temporal GROUP BY url ORDER BY COUNT(url) DESC;" ;
	$conn->query($query); 
	$query = "SELECT A.url, A.countTotalWords, A. countWords, B.title FROM temporal2 A, temporal4 B WHERE A.url = B.url;" ; 
	$result = $conn->query($query); 

	 if($result->num_rows == null){
	 	$finalResult = "<h2>No results found<h2>";	
	 }
	 while ($row = $result->fetch_assoc()){ 
	 		$finalResult = $finalResult.'  <div class="container"> 
	 		   							   		<div class="subContainer1">
	 		   							   		      <h1 class="ContainerTitle">'.$row['title'].'</h1><br> 
    											</div> 
    											<div class="subContainer2">
											      <a class="link" href='.$row['url'].'>'.$row['url'].'</a><br><br> 
											    </div>  
											    <div class="subContainer3">
      												 <p class="wordsFound">Total words found: '.$row['countTotalWords'].'</p> 
    											</div>  
    											<div class="subContainer4">
												    <p class="wordsFound">Words found: '.$row['countWords'].'</p> 
												</div> 
										   </div>';
	 	}
	echo $finalResult; 


	$query = "DROP TABLE temporal" ;
	$result = $conn->query($query);  
	$query = "DROP TABLE temporal2" ;
	$result = $conn->query($query);  
	$conn->close();


	//mysql_free_result($result);

?> 

</div>

</body>
</html>


