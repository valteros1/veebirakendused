<?php
	function test_input($data) {
		$data = filter_var($data, FILTER_SANITIZE_STRING);
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	//funktsioon pildi saatmise jaoks andmebaasi
	function upload_to_database($pic_name,$pic_orig_name,$alt_text,$pic_privacy){
			$notice = 0;
			$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
			$stmt = $conn->prepare("INSERT INTO vr21_photos (vr21_photos_userid, vr21_photos_filename, vr21_photos_origname, vr21_photos_alttext, vr21_photos_privacy) VALUES (?,?,?,?,?)");
			echo $conn->error;
			// bind parameetrid, int, string, string, string, int
			$stmt -> bind_param("isssi", $_SESSION["user_id"], $pic_name, $pic_orig_name, $alt_text, $pic_privacy);
			
			if($stmt -> execute()){
				$notice = 1;
			}
			$stmt -> close();
			$conn -> close();
			return $notice;
		}
			
	function resize_image($temp_image, $image_max_w, $image_max_h, $keep_ratio) {
			$image_w = imagesx($temp_image);
			$image_h = imagesy($temp_image);
			
			if ($keep_ratio){ 
				if($image_w / $image_max_w > $image_h / $image_max_h){
					$image_size_ratio = $image_w / $image_max_w;
				} else {
					$image_size_ratio = $image_h / $image_max_h;
				}
	
				$image_new_w = round($image_w / $image_size_ratio);
				$image_new_h = round($image_h / $image_size_ratio);
	
				$new_temp_image = imagecreatetruecolor($image_new_w, $image_new_h);
				imagecopyresampled($new_temp_image, $temp_image, 0, 0, 0, 0, $image_new_w, $image_new_h, $image_w, $image_h);
	
			} else {
				if($image_h<$image_w){
					$src_x = ($image_w - $image_h) /2;
					$src_width = $image_h;
					$src_y = 0;
					$src_height = $image_h;
	
				
				} else {
					$src_x = 0;
					$src_width = $image_h;
					$src_y = ($image_h - $image_w) /2;
					$src_height = $image_w;
				}
	
				$image_new_w = $image_max_w;
				$image_new_h = $image_max_h;
			
				$new_temp_image = imagecreatetruecolor($image_new_w, $image_new_h);
				imagecopyresampled($new_temp_image, $temp_image, 0, 0, $src_x, $src_y, $image_new_w, $image_new_h, $src_width, $src_height);
			}
	
			return $new_temp_image;
	
		}
?>