<?php
	require_once "usesession.php";
	require_once "../../../conf.php";
	require_once "fnc_general.php";
	require_once "classes/Upload_photo.class.php";
	
	$photo_upload_error = null;
	$image_file_type = null;
	$image_file_name = null;
	$file_name_prefix = "vr_";
	$file_size_limit = 1.5 * 1024 * 1024;
	$image_max_w = 600;
	$image_max_h = 400;
	$resize_image = null;
	$watermark = "../images/vr_watermark.png";

	if(isset($_POST["photo_submit"])){
		//var_dump($_POST);
		//var_dump($_FILES);
		//kas üldse on pilt
		$check = getimagesize($_FILES["file_input"]["tmp_name"]);

		if($check !== false){

			//kontrollime, kas aktepteeritud failivorming ja fikseerime laiendi
			if($check["mime"] == "image/jpeg"){
				$image_file_type = "jpg";
			} elseif ($check["mime"] == "image/png"){
				$image_file_type = "png";
			} else {
				$photo_upload_error = "Pole sobiv formaat! Ainult jpg ja png on lubatud!";
			}
		} else {
			$photo_upload_error = "Tegemist pole pildifailiga!";
		}
		
		if(empty($photo_upload_error)){
		
			// võtame nüüd kasutusele ulpoad_photo classi
			$photo_upload = new Upload_photo($_FILES["file_input"],$image_file_type, $file_size_limit);




			//ega pole liiga suur fail
			if($_FILES["file_input"]["size"] > $file_size_limit){
				$photo_upload_error = "Valitud fail on liiga suur! Lubatud kuni 1MiB!";
			}
			
			if(empty($photo_upload_error)){
				//loome oma failinime
				$timestamp = microtime(1) * 10000;
				$image_file_name = $file_name_prefix .$timestamp ."." .$image_file_type;

			
				
			//-- loome normaalsuuruses pildi säilitades külgede proportsiooni
				$photo_upload->resize_photo(600, 400, true);

				
				// Lisan vesimärgi
				$photo_upload->add_watermark($watermark);

				$photo_upload->picture_date();


				$target_file = "../upload_photos_normal/" .$image_file_name;
				$result = $photo_upload->save_image_to_file($target_file); // 1 on ok tulemus muidu error


				$photo_upload->resize_photo( 100, 100, false );
				$target_file = "../upload_photos_thumbnail/" .$image_file_name;
				$result = $photo_upload->save_image_to_file($target_file); // 1 on ok tulemus muidu error

				
				$target_file = "../upload_photos_orig/" .$image_file_name;

				if(move_uploaded_file($_FILES["file_input"]["tmp_name"], $target_file)){
					$photo_upload_error .= " Foto üleslaadimine õnnestus!";
					if (upload_to_database($image_file_name,$_FILES["file_input"]["name"], $_POST['alt_text'], $_POST['privacy_input']) == 1){
						$photo_upload_error .= "  Foto andmete lisamine andmebaasi õnnestus";
					} else {
						$photo_upload_error .= "  Foto andmete lisamine ebaõnnestus";
					}

				} else {
					$photo_upload_error .= " Foto üleslaadimine ebaõnnestus!";
				}
			}
		}
	}

	

?>
<!DOCTYPE html>
<html lang="et">
<head>
	<script src="checkImageSize.js"> defer </script>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2021</title>
</head>
<body>
	<h1>Fotode üleslaadimine</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<hr>
	
	<p><a href="home.php">Avalehele</a></p>
	<p><a href="galerii.php">Galeriisse!</a></p>
	<hr>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<label for="file_input">Vali foto fail! </label>
		<input id="file_input" name="file_input" type="file">
		<br>
		<label for= "alt_input">Alternatiivtekst ehk pildi selgitus</label>
		<input id="alt_text" name = "alt_text" type = "text" placeholder="Pildil on ... ">
		<br>
		<label> Privaatsustase: </label>
		<br>
		<label for="privacy_input_1">Privaatne</label>
		<input id="privacy_input_1" name ="privacy_input" type="radio" value="3" checked>
		<br>
		<label for="privacy_input_2">Registreeritud kasutajatele</label>
		<input id="privacy_input_2" name ="privacy_input" type="radio" value="2" >
		<br>
		<label for="privacy_input_3">Avalik</label>
		<input id="privacy_input_3" name ="privacy_input" type="radio" value="1" >

		<br>
		<input type="submit" name="photo_submit" value="Lae pilt üles!">
	</form>
	<p><?php echo $photo_upload_error; ?></p>
	<hr>
	<p><a href="?logout=1">Logi välja</a></p>
</body>
</html>