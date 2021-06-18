<?php

	require_once "../../../conf.php";
	require_once "usesession.php";
	$news_input_error = null;
	$photo_upload_error = null;
	//$notice = null;
	/* var_dump($_POST);  Massiivi nimi, On olemas ka $_GET */
	
	if(isset($_POST["news_submit"] )){
		if(empty($_POST["news_title_input"])){
			$news_input_error = "Uudise Pealkiri on puudu! ";

			

		} 
		//var_dump($_FILES["upload_photo_news"]);
		if(empty($_POST["news_content_input"])){
			$news_input_error .= "Uudise tekst on puudu!"; /* .= Võta senine väärtus juurde*/

		}
			if(empty($_POST["news_input_error"])){
				store_news($_POST["news_title_input"], $_POST["news_content_input"], $_POST["news_author_input"], 
				$_FILES["upload_photo_news"]["name"], $_POST["news_photo_alttext"]);

				$target_file = "../news_pictures/".$_FILES["upload_photo_news"]["name"];
				//if(file_exists($target_file))
				if(move_uploaded_file($_FILES["upload_photo_news"]["tmp_name"], $target_file)){
					$notice = " Originaalfoto üleslaadimine õnnestus!";
				} else {
					$photo_upload_error .= " Originaalfoto üleslaadimine ebaõnnestus!";
				}	
	
	} 
			 
	}
	function store_news($news_title, $news_content, $news_author, $news_photo, $news_photo_alttext){

		/* echo $news_title .$news_content .$news_author; */
		/*echo $GLOBALS["server_host"]; */
		/* loome andmebaasi serveriga ja baasiga ühenduse*/
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		/* Määrame suhtluseks kodeeringu*/
		$conn -> set_charset("utf8");

		/* Valmistan ette SQL käsu*/

		$stmt = $conn -> prepare("INSERT INTO vr21_news (vr21_news_news_title, vr21_news_news_content, vr21_news_news_author, vr21_news_photo, vr21_news_photo_alttext) VALUES(?,?,?,?,?) ");
		echo $conn -> error;
		/* i - integer s - string d - decimal PS! paramiga JÄRJEKORD!!!!!*/
		$stmt -> bind_param("sssss", $news_title, $news_content, $news_author, $news_photo, $news_photo_alttext);
		$stmt -> execute();
		$stmt -> close();
		$conn -> close();

	}

	


?>

<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2021</title>
</head>
<body>
	<h1>
	Uudiste lisamine
	
	</h1>
	<p>See leht on valminud õppetöö raames!</p>
	
	<hr>
	<form method="POST"action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"enctype="multipart/form-data"> 
		<label for="news_title_input">Uudise pealkiri</label>
		<br> 
		<input type="text" id="news_title_input" name="news_title_input" placeholder="Pealkiri">
		<br>
		<label for="news_content_input">Uudise tekst</label>
		<br>
		<textarea id="news_content_input" name="news_content_input" placeholder="Uudise tekst" rows="6" cols="40"></textarea>
		<br>
		<label for="news_author">Uudise lisaja nimi</label>
		<br> 
		<input type="text" id="news_author_input" name="news_author_input" placeholder="Nimi">
		<br>
		<label for="upload_photo_news">Lisa pilt uudisele</label>
		<br>
		<br> 
		<input type="file" id= "upload_photo_news" name="upload_photo_news" value="Lae pilt">
		<br> 
		<input type="text" id="news_photo_alttext" name="news_photo_alttext" placeholder="Alternatiivtekst">
		
		
		<br>
		<br>
		<input type="submit" name="news_submit" value="Salvesta uudis!">
		<br>
		<hr>
		<a href="home.php">Tagasi pealehele</a>

	</form>

	<p><?php echo $news_input_error; ?></p> 

</body>
</html>
