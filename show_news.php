<?php

	require_once "../../../conf.php";
	require_once "usesession.php";
	if (isset($_POST["news_output_num"])) {
		$news_limit = $_POST["news_output_num"];
		}
		else {
			$news_limit = 4;
		}
	
	function read_news($news_limit){

		/* loome andmebaasi serveriga ja baasiga ühenduse*/
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		/* Määrame suhtluseks kodeeringu*/
		$conn -> set_charset("utf8");
		/* Valmistan ette SQL käsu*/
		$stmt = $conn -> prepare("SELECT vr21_news_news_title, vr21_news_news_content, vr21_news_news_author, vr21_news_added, vr21_news_photo, vr21_news_photo_alttext FROM vr21_news ORDER BY vr21_news_id DESC LIMIT ? ");
		$stmt -> bind_param("s",$news_limit);
		echo $conn -> error;
		/* i - integer s - string d - decimal PS! paramiga JÄRJEKORD!!!!!*/
		$stmt -> bind_result($news_title_from_db, $news_content_from_db, $news_author_from_db, $news_added_from_db, $news_photo_from_db, $news_photo_alttext_from_db );
		$stmt -> execute();
		$raw_news_html = null;
		while ($stmt -> fetch()){
			$raw_news_html .= "<img class='News_picture' src='../news_pictures/".$news_photo_from_db."'>";
		
			$raw_news_html .= "\n <h2>" .$news_title_from_db ." </h2>";
			$date_of_news = new DateTime($news_added_from_db); 
			$raw_news_html .= "\n <p>Lisatud: ".$date_of_news->format('d-m-Y')."</H4>";
			$raw_news_html .= "\n <p> " .nl2br($news_content_from_db) ."</p>";
			

			if(!empty($news_author_from_db)) { 
				$raw_news_html .= "\n <p>Edastas: " .$news_author_from_db;
			}else {
					$raw_news_html .= "Edastas: Tundmatu reporter";

			}
			$raw_news_html .= "</p>";

		}

		$stmt -> close();
		$conn -> close();
		return $raw_news_html;
	}
	
	$raw_news_html = read_news($news_limit);
	
?>

<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2021</title>
	<<link rel="stylesheet" href="css/modal.css">
</head>
<body>
	<h1>
	Uudiste lugemine
	
	</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<form method="POST" id="news_num">
	<INPUT type="number" min="1" max="10" value="<?php echo $news_limit; ?>" name="news_output_num" onchange="do_submit()">
	
	<hr>

	</form>

	<p><?php echo $raw_news_html ?></p> 
	<script>                                                                                                                
function do_submit() {
     document.getElementById("news_num").submit();
}
</script>
<br>
		<hr>
		<a href="home.php">Tagasi pealehele</a>
</body>
</html>
