<?php
    class Upload_photo {

        private $photo_to_upload;
        private $image_file_type;
        private $temp_image;
        private $image_file_size;
        private $file_size_limit = 1.5 * 1024 * 1024;
        public $photo_upload_error;
        public  $new_temp_image; //hiljem kui class hakkab ise tegema kõike siis private


        function __construct($photo_to_upload,$image_file_type, $file_size_limit){
            $this->photo_to_upload = $photo_to_upload;
            $this->image_file_type = $image_file_type; // see tuleks edaspidi classis sees tuvastada
            $this->image_file_size = $file_size_limit;
            $this->file_type();
            // test kas on pilt ja kas sobiv tuleks ka classis sees tuvastada
            $this->temp_image = $this ->create_image_from_file($this->photo_to_upload["tmp_name"],$this->image_file_type);
        }

        private function file_type(){
            $check = getimagesize($this->photo_to_upload["tmp_name"]);
            if($check == true){
			//kontrollime, kas aktepteeritud failivorming ja fikseerime laiendi
			if($check["mime"] == "image/jpeg"){
				$this->image_file_type = "jpg";
			} elseif ($check["mime"] == "image/png"){
				$this->image_file_type = "png";
			} else {
				$this->photo_upload_error = "Pole sobiv formaat! Ainult jpg ja png on lubatud!";
			}
		    } else {
			$this->photo_upload_error = "Tegemist pole pildifailiga!";
		    }
        }   
        private function create_image_from_file($image,$image_file_type){

            $temp_image = null;
            if($image_file_type == "jpg"){
                $temp_image = imagecreatefromjpeg($image);
            }
            if($image_file_type == "png"){
                $temp_image = imagecreatefrompng($image);
            }
            
            
           return $temp_image;
        }



        public function resize_photo($image_max_w, $image_max_h, $keep_ratio) {

						
            $image_w = imagesx($this->temp_image);
            $image_h = imagesy($this->temp_image);
    
            //
            
            if ($keep_ratio){ 
                if($image_w / $image_max_w > $image_h / $image_max_h){
                    $image_size_ratio = $image_w / $image_max_w;
                } else {
                    $image_size_ratio = $image_h / $image_max_h;
                }
    
                $image_new_w = round($image_w / $image_size_ratio);
                $image_new_h = round($image_h / $image_size_ratio);
    
                $this->new_temp_image  = imagecreatetruecolor($image_new_w, $image_new_h);
                imagecopyresampled($this->new_temp_image , $this->temp_image, 0, 0, 0, 0, $image_new_w, $image_new_h, $image_w, $image_h);
    
            } else {
                if($image_h<$image_w){
                    // Landscape picture
                    $src_x = ($image_w - $image_h) /2;
                    $src_width = $image_h;
                    $src_y = 0;
                    $src_height = $image_h;
    
                
                } else {
                    // Portrait picture
                    $src_x = 0;
                    $src_width = $image_h;
                    $src_y = ($image_h - $image_w) /2;
                    $src_height = $image_w;
                }
    
                $image_new_w = $image_max_w;
                $image_new_h = $image_max_h;
            
                $this->new_temp_image = imagecreatetruecolor($image_new_w, $image_new_h);
                imagecopyresampled($this->new_temp_image, $this->temp_image, 0, 0, $src_x, $src_y, $image_new_w, $image_new_h, $src_width, $src_height);
            }
        }

        public function save_image_to_file($target){
                $notice = null;
                if($this->image_file_type == "jpg"){
                    if(imagejpeg($this->new_temp_image, $target, 90)){
                        $notice = 1;
                    } else {
                        $notice = 0;
                    }
                }
                if($this->image_file_type == "png"){
                    if(imagepng($this->new_temp_image, $target, 6)){
                        $notice = 1;
                    } else {
                        $notice = 0;
                    }
                }
                imagedestroy($this->new_temp_image);
                return $notice;
        }
        
        public function add_watermark($watermark){
            $watermark_file_type = strtolower((pathinfo($watermark,PATHINFO_EXTENSION)));
            $watermark_image = $this->create_image_from_file($watermark, $watermark_file_type);
            $watermark_w = imagesx($watermark_image);
            $watermark_h = imagesy($watermark_image);
            $watermark_x = imagesx($this->new_temp_image) - $watermark_w - 10;            
            $watermark_y = imagesy($this->new_temp_image) - $watermark_h - 10;
            imagecopy($this->new_temp_image,$watermark_image,$watermark_x,$watermark_y,0,0,$watermark_w,$watermark_h);
            imagedestroy($watermark_image);

            

        }
        public function picture_date(){
            @$exif = exif_read_data($this->photo_to_upload["tmp_name"], "ANY_TAG", 0, true);
            // var_dump($exif); kontrollisin, kas pildi sees on andmeid
            $size = 20;
            $y = 20;
            $text_to_image = "allalaaditud pilt";
            $font = "/home/valter.rosenfeld/public_html/Veebirakendused2021/images/Courier_Regular.ttf";
            if(!empty($exif["DateTime"])){
                $this->photo_date = $exif["DateTime"];
                $text_color = imagecolorallocatealpha($this->new_temp_image, 255,255,255, 60);//valge, 60% alpha
                // Kirjutatakse tekst pildile, parameetriteks: pildiobjekt, teksti suurus (näiteks 14), nurk (teksti saab kaldu panna), x-koordinaat, y-koordinaat, teksti värv (eelnevalt defineeritud), TTF-fondi url, kirjutatav tekst
                imagettftext($this->new_temp_image, $size, 0, 10, $y, $text_color, $font,  $this->photo_date);
            } else {
                $this->photo_date = NULL;
            }
         }


    } //class lõppeb