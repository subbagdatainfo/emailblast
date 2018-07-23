<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//hanya mengizinkan akses dari localhost
$allowed_ip = '127.0.0.1';
if( $allowed_ip !== $_SERVER['REMOTE_ADDR'] ) {
    exit( 0 );
}
//config
$servername = "localhost";
$username = "kebudayaan";
$password = "c1kur4y";
$dbname = "abdulhadi_kebudayaan";

// koneksi
$conn = new mysqli($servername, $username, $password, $dbname);
$hari = date("Y-m-d");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//query sql memeriksa semua tabel main dan micro site
$sql = "SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_5_posts WHERE post_date_gmt LIKE '$hari%' UNION
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_6_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_7_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_8_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_9_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_10_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_11_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_12_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_13_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_14_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_15_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_16_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_17_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_18_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_19_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_20_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_22_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_24_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_26_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_27_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_28_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_29_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_30_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_31_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_32_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_33_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_34_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_35_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_36_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_37_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_38_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_39_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_58_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_59_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_60_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_61_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_63_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_64_posts WHERE post_date_gmt LIKE '$hari%' UNION 
		SELECT post_date, post_title, guid FROM abdulhadi_kebudayaan.kbd_posts WHERE post_date_gmt LIKE '$hari%'";

$result = $conn->query($sql);


	$result;
	if ($result->num_rows > 0) {
    // output data of each row
    	while($row = $result->fetch_assoc()) {
        	$data = $row['post_date']." | " . "<a href='".$row['guid']."'".">".$row["post_title"]."</a>"."<br>";
        	$simpandata[]=$data;
    	}
	} else {
    echo "0 results";
	}

	$dataimplode = implode(" ",$simpandata);
	if (!$dataimplode) {
		require ('vendor/phpmailer/phpmailer/PHPMailerAutoload.php');
     
	    $mail = new PHPMailer;
	    // Konfigurasi SMTP
	    $mail->isSMTP();
	    $mail->Host = 'smtp.gmail.com';
	    $mail->SMTPAuth = true;
	    $mail->Username = 'budayasaya@gmail.com';
	    $mail->Password = 'SahabatBudaya';
	    $mail->SMTPSecure = 'tls';
	    $mail->Port = 587;
	    $mail->setFrom('budayasaya@gmail.com', 'budayasaya');
	    $mail->addReplyTo('budayasaya@gmail.com', 'budayasaya');
	    // Menambahkan penerima
	    require ('listkebudayaan.php');
	    
	    // Subjek email
	    $mail->Subject = 'Berita Kebudayaan';
	    // Mengatur format email ke HTML
	    $mail->isHTML(true);
	    $mail->Body = $dataimplode;
	    if(!$mail->send()){
	        echo 'Pesan tidak dapat dikirim.';
	        echo 'Mailer Error: ' . $mail->ErrorInfo;
	    }else{
	        echo 'Pesan telah terkirim';
	    }

		$conn->close();
	}
    ?>