<?php
	ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$hari = date("Y-m-d");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT post_author, post_title, guid FROM test.wp_posts WHERE post_date_gmt LIKE '$hari%'
		UNION 
		SELECT post_author, post_title, guid FROM test.wp_2_posts WHERE post_date_gmt LIKE '$hari%'
		UNION 
		SELECT post_author, post_title, guid FROM test.wp_4_posts WHERE post_date_gmt LIKE '$hari%'";

$result = $conn->query($sql);


	$result;
	if ($result->num_rows > 0) {
    // output data of each row
    	while($row = $result->fetch_assoc()) {
        	$data = "author: " . $row["post_author"]. "  Judul: " . "<a href='".$row['guid']."'".">".$row["post_title"]."</a>"."<br>";
        	$simpandata[]=$data;
    	}
	} else {
    echo "0 results";
	}

	$dataimplode = implode(" ",$simpandata);
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
    $mail->addAddress('ahmad.kdesain@gmail.com');
    $mail->addAddress('ahmad.inside775@gmail.com');
    $mail->addAddress('ahmad@kdesain.com');
    $mail->addAddress('wijayapandu12@gmail.com');
    $mail->addAddress('gustoagogo@gmail.com');
    $mail->addAddress('muhammad.mahdy89@gmail.com');
    $mail->addAddress('lintangbnastiti@gmail.com');
    $mail->addAddress('fauzpurw@gmail.com');
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