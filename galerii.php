<?php
    require_once "usesession.php";
    //require_once "fnc_user.php";
    require_once "../../../conf.php";


function db_info_grab(){
    $notice = 0;
    $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
    $stmt = $conn -> prepare("SELECT vr21_photos.vr21_photos_id, vr21_photos.vr21_photos_filename, vr21_photos.vr21_photos_alttext, vr21_users.vr21_users_firstname, 
    vr21_users.vr21_users_lastname FROM vr21_photos JOIN vr21_users ON vr21_photos.vr21_photos_userid = vr21_users.vr21_users_id WHERE vr21_photos.vr21_photos_privacy <= 3 
    AND vr21_photos.vr21_photos_deleted IS NULL GROUP BY vr21_photos.vr21_photos_id");
    echo $conn -> error;
    //$stmt -> bind_param("i", 3);
    $stmt -> bind_result($photo_id, $photo_filename, $photo_alt_text, $first_name, $last_name);
    $stmt -> execute();
    while ($stmt -> fetch()) { 
        echo '<div class="klassinimi">';
            echo '<img src= ../upload_photos_normal/'.$photo_filename.' alt='.$photo_alt_text.' class="thumb" data-fn='.$photo_filename.' data-id='.$photo_id.'>';
            echo '<div class ="nimi">'.$first_name.' '.$last_name.'</div>'; 
            echo '</div>'."\n";
    }


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>



<style>

h1{
    font-size:2.5em;
    color: gray;
    
}    

/* gridi variant leitud quackit.comist ja modifitseeritud */
.grid { 
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
  grid-gap: 10px 10px;
  align-items: flex;
  padding:10px;
  
  }
.grid img {
  border: 1px solid #ccc;
  max-width: 100%;
}

/* Ülemine menüü riba*/
.nav_riba {
    background-color: #333;
    width: auto;  
    display: flex;
    justify-content: center;
    
}
   
.nav_riba a {
    
    color: #f2f2f2;
    padding: 14px 16px;
    text-decoration: none;
    font-size: 20px;
}
  
 
.nav_riba a:hover {
    background-color: rgb(155, 122, 122);
    color: black;
}

.nimi{
   font-size:20px;
   border-color: red;
   

}
body{
    background-color: #f0f0f0;
}
</style>
    
</head>
<body >
<h1> Galerii  </h1>
<div class="nav_riba">
    <a class="active" href="home.php">Pealehele</a>
    <a href="page_add_news.php">Lisa uudiseid</a>
    <a href="upload_photo.php">Lae pilt!</a>
</div>

<main class="grid">
<?php
db_info_grab()
?>
</main>
</body>
</html>