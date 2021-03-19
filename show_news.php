<?php

	require_once "../../../conf.php";
	

	
	
	function read_news(){

		
		/* loome andmebaasi serveriga ja baasiga ühenduse*/
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		/* Määrame suhtluseks kodeeringu*/
		$conn -> set_charset("utf8");

		/* Valmistan ette SQL käsu*/

		$stmt = $conn -> prepare("SELECT vr21_news_news_title, vr21_news_news_content, vr21_news_news_author FROM vr21_news");
		echo $conn -> error;
		/* i - integer s - string d - decimal PS! paramiga JÄRJEKORD!!!!!*/
		$stmt -> bind_result($news_title_from_db, $news_content_from_db, $news_author_from_db);
		$stmt -> execute();
		$raw_news_html = null;
		while ($stmt -> fetch()){
			$raw_news_html .= "\n <h2>" .$news_title_from_db ." </h2>";
			$raw_news_html .= "\n <p> " .nl2br($news_content_from_db) ."</p>";
			$raw_news_html .= "\n <p>Edastas: ";
			if(!empty($news_author_from_db)) { 
				$raw_news_html .= $news_author_from_db;
			}else {
					$raw_news_html .= "Tundmatu reporter";

			}
			$raw_news_html .= "</p>";

		}



		
		$stmt -> close();
		$conn -> close();
		return $raw_news_html;
	}
	

		$raw_news_html = read_news();
	



?>

<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2021</title>
</head>
<body>
	<h1>
	Uudiste lugemine
	
	</h1>
	<p>See leht on valminud õppetöö raames!</p>
	
	<hr>


	</form>

	<p><?php echo $raw_news_html ?></p> 

</body>
</html>
