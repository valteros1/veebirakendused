<?php
	//session_start();
	// kas on sisse loginud
	require_once "usesession.php";
    /* session_start();
    //kas on sisse loginud
    if(!isset($_SESSION["user_id"])){
        header("Location: page.php");
    }
    //välja logimine
    if(isset($_GET["logout"])){
        session_destroy();
        header("Location: page.php");
    } */
?>

<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2021</title>
</head>
<body>
	<h1>
	<p><?php echo "Sisse loginud kasutaja ", $_SESSION["user_first_name"]." ".$_SESSION["user_last_name"]." vinge süsteem ";?></p>
	
	</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<hr>
	<a href="show_news.php">Näita uudiseid</a>
	<br>
	<a href="page_add_news.php">Lisa uudiseid!</a>
	<br>
	<a href="upload_photo.php">Lae pilt</a>
	<br>
	<a href="galerii.php">Vaata galeriid!</a>
	<hr>
	
	
	<p><a href="?logout=1"><p>Logi välja</a></p>

<ul>
	

</body>
</html>
