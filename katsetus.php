<?php
	$myname = "Valter Rosenfeld";
	$currenttime = date("d.m.Y H:i:s");
	$timehtml = "\n <p>Lehe avamise hetkel oli aeg: " .$currenttime . ". </p>";
	$semesterbegin = new DateTime("2021-1-25");
	$semesterend = new DateTime("2021-6-30");
	$semesterduration = $semesterbegin->diff($semesterend);
	$semesterdurationdays = $semesterduration->format("%r%a");
	$semesterdurhtml = "\n <p>2021 kevadsemestri kestus on " .$semesterdurationdays ." päeva.</p> \n";
	$today = new DateTime("now");
	$fromsemesterbegin = $semesterbegin->diff($today);
	$fromsemesterbegindays = $fromsemesterbegin->format("%r%a");
	$n2dalap2evad=['Pühapäev','Esmaspäev','Teisipäev','Kolmapäev','Neljapäev','Reede','Laupäev'];   
	$n2dalap2eva_number=date('w');                                                    
    $t2nanep2ev="<p> Täna on ". $n2dalap2evad[$n2dalap2eva_number].".</p>"; 

	
	if($fromsemesterbegindays <= $semesterdurationdays){
	$semesterprogress = "\n" .'<p>Semester edeneb: <meter min="0" max="' .$semesterdurationdays .'" value="' .$fromsemesterbegindays .'"></meter>.</p>' ."\n";
	}
	elseif($semesterbegindays < 0) {
	$semesterprogress = "\n <p> Semester pole alanud </p> \n";
	}
	else {
	$semesterprogress = "\n <p>Semester on lõppenud. </p> \n";
	

	}
 /* meter min ="0" max="156" */

/* loeme piltide kataloogi sisu */

	$picsdir = "../pics/";
	$allfiles = array_slice(scandir($picsdir), 2);
	/* echo $allfiles[5]; */
	/* var_dump($allfiles); */
	$allowedphototypes = ["image/jpeg", "image/png"];
	$picfiles = [];
	$numbritekogu = [];

	/* */

	foreach($allfiles as $file){
		$fileinfo = getimagesize($picsdir .$file);
		if(isset($fileinfo["mime"])){
			if(in_array($fileinfo["mime"], $allowedphototypes)){
				array_push($picfiles, $file);

			}

		}


	}

	$photocount = count($picfiles);

	do {
		$photonum = mt_rand(0, $photocount-1);
		if(!(in_array($photonum, $numbritekogu))) {  /* photonum ehk nõel, ja numbritekogu on heinakuhi */
		

		array_push($numbritekogu, $photonum);
	
		}
	}while (count($numbritekogu) < 3);

		$randomphoto1 = $picfiles[$numbritekogu[0]];
		$randomphoto2 = $picfiles[$numbritekogu[1]];
		$randomphoto3 = $picfiles[$numbritekogu[2]];

	
	
	

?>




<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2021</title>
</head>
<body>
	<h1>
	<?php
		echo $myname;
		
	?>
	
	</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<?php
		echo $timehtml;
		echo $semesterdurhtml;
		echo $semesterprogress;
		echo $t2nanep2ev;
		
		
	
	?>
	<img src="<?php echo $picsdir .$randomphoto1;  ?>"alt="Vaade Haapsalus">
	<img src="<?php echo $picsdir .$randomphoto2;  ?>" alt="Vaade Haapsalus">
	<img src="<?php echo $picsdir .$randomphoto3;  ?>" alt="Vaade Haapsalus">

</body>
</html>
