<?php
	//session_start();
	require_once "classes/SessionManager.class.php";
	SessionManager::sessionStart("vr", 0, "/~valter.rosenfeld/", "tigu.hk.tlu.ee");
	require_once "../../../conf.php";
	// require_once("fnc_general.php");
	require_once "fnc_user.php";
	//require_once "fnc_general.php";

	//klassi näide
	require_once "classes/Test.class.php";
	$test_object = new Test(5);
	echo "Avalik number on ", $test_object->non_secret;
	$test_object->reveal();
	// eemaldab classi unsetiga, uuesti ei lase kutsuda välja
	unset($test_object);
	// echo "Avalik number on ", $test_object->non_secret;

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

	
	
	 //sisselogimine
	 $notice = null;
	 $email = null;
	 $email_error = null;
	 $password_error = null;
	 if(isset($_POST["login_submit"])) {
		 //kontrollime, kas email ja password põhimõtteliselt olemas

		 $notice = sign_in($_POST["email_input"], $_POST["password_input"]);


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
	<?php
		echo $myname;
		
	?>
	
	</h1>
	<p>See leht on valminud õppetöö raames!</p>
	
	
	<hr>
	<h2>Logi sisse</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label>E-mail (kasutajatunnus):</label><br>
		<input type="email" name="email_input" value="<?php echo $email; ?>"><span><?php echo $email_error; ?></span><br>
		<label>Salasõna:</label><br>
		<input name="password_input" type="password"><span><?php echo $password_error; ?></span><br>
		<input name="login_submit" type="submit" value="Logi sisse!"><span><?php echo $notice; ?></span>
	</form>
	<p> Loo endale <a href="add_user.php">kasutajakonto!</a></p>

	<hr>
	<?php
		echo $t2nanep2ev;
		echo $timehtml;
		echo $semesterdurhtml;
		echo $semesterprogress;
		
		
		
	
	?>
	<img width=295 height=197 src="<?php echo $picsdir .$randomphoto1;  ?>" alt="Vaade Haapsalus">
	<img width=295 height=197 src="<?php echo $picsdir .$randomphoto2;  ?>" alt="Vaade Haapsalus">
	<img width=295 height=197 src="<?php echo $picsdir .$randomphoto3;  ?>" alt="Vaade Haapsalus">

</body>
</html>
