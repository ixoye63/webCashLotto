<html>
<head>
<title>PHPMailer - SMTP basic test with authentication</title>
</head>
<body>

<?php

//error_reporting(E_ALL);
// error_reporting(E_STRICT);

// date_default_timezone_set('Asia/Seoul');

require_once('../lib/PHPMailer/class.phpmailer.php');

$mail             = new PHPMailer();


$body = "Test... 테스트...";

$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host       = "ssl://smtp.naver.com"; // SMTP server
// $mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Host       = "ssl://smtp.naver.com"; // sets the SMTP server
$mail->Port       = 465;                    // set the SMTP port for the GMAIL server
$mail->Username   = "hjkodct@naver.com"; // SMTP account username
$mail->Password   = "work4u123";        // SMTP account password

$mail->CharSet = "UTF-8"; 

$mail->SetFrom('hjkodct@naver.com', '장정훈');

$mail->AddReplyTo("hjkodct@naver.com","정훈장");

$mail->Subject    = "한글 깨짐 테스트";

//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

$mail->MsgHTML($body);

$address = "hjkodct@naver.com";
$mail->AddAddress($address, "장정훈");

// $mail->AddAttachment("images/phpmailer.gif");      // attachment
// $mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}

?>

</body>
</html>
