    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require ('vendor/phpmailer/phpmailer/PHPMailerAutoload.php');
    require ('b.php');
   
    $mail = new PHPMailer;
    // Konfigurasi SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'budayasaya@gmail.com';
    $mail->Password = 'SegaraAnak';
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
    // Konten/isi email
    $mailContent = isiemail(); var_dump($mailContent);
    /*$mail->Body = $mailContent;
    // Kirim email
    if(!$mail->send()){
        echo 'Pesan tidak dapat dikirim.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }else{
        echo 'Pesan telah terkirim';
    }