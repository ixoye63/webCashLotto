<?
if (!defined('_CASHLOTTO_')) exit;

//include_once("$g4[path]/lib/mailer.lib.php");
//include_once("$g4[path]/lib/PHPMailer/class.phpmailer.php");
include_once("$g4[path]/lib/PHPMailer/PHPMailerAutoload.php");

function send_mail($email, $name, $title, $content) 
{
	// ----------------------------------------
	// 변수 초기화
	// ----------------------------------------

	$to_email = $email;							// 받는 사람의 이메일 : 예) test@naver.com
	$to_name = $name;							// 받는 사람의 이름 : 예)홍길동
	$from_name = "캐쉬로또관리자";				// 보내는 사람 이름
	$from_email = "cashlotto@cashlotto.com";	// 보내는 사람 이메일

	$smtp_use = 'ssl://smtp.naver.com';			//네이버 메일 사용시
	// $smtp_use = 'ssl://smtp.gmail.com';		//구글 메일 사용시 주석제거

	if ($smtp_use == 'ssl://smtp.naver.com') { 
		$smtp_mail_id = "hjkodct@naver.com";	// SMTP 사용자 이름 : 예)test@naver.com 혹은 test@gmail.com 등의 형식
		$smtp_mail_pw = "hjkodct!23";

		$from_email = $smtp_mail_id;			//네이버메일은 보내는 id로만 전송이가능함

	} else if ($smtp_use == 'ssl://smtp.gmail.com') {
		$smtp_mail_id = "ixoye63@gmail.com";	
		$smtp_mail_pw = "hjko!23!";	

	} else {
		return 0;
	}


	$alt_body = "요청하신 캐시로또 사용자 '$user_id'의 비밀번호 초기화가 완료되었습니다.";
	$return_status = 0;

	$mail = new PHPMailer(true);  

	$mail->IsSMTP();
	// $mail->SMTPDebug  = 1;					// enables SMTP debug information (for testing)
														// 1 = errors and messages
														// 2 = messages only
	$mail->SMTPAuth   = true;					// enable SMTP authentication
	$mail->Host       = $smtp_use;				// sets the SMTP server
	$mail->Port       = 465;                    // set the SMTP port for the GMAIL server
	$mail->Username   = $smtp_mail_id;			// SMTP account username
	$mail->Password   = $smtp_mail_pw;

    $mail->CharSet	  = "UTF-8";  
	$mail->Encoding	  = "base64";

	
	$mail->SetFrom($from_email, $from_name);	// 보내는 사람 email 주소와 표시될 이름 (표시될 이름은 생략가능)
	$mail->AddAddress($to_email, $to_name);		// 받을 사람 email 주소와 표시될 이름 (표시될 이름은 생략가능)
  
	$mail->AddReplyTo($from_email, $from_name);

    $mail->Subject	  = $title;                 // 메일 제목
	$mail->AltBody    = $alt_body;				// 메일 요약내용
    $mail->MsgHTML($content);                   // 메일 내용 (HTML 형식도 되고 그냥 일반 텍스트도 사용 가능함)

	/*

        echo "======================================>>" . "<br>";;
		echo "mail->Host : $mail->Host" . "<br>";
		echo "mail->Port : $mail->Port" . "<br>";
		echo "mail->SMTPAuth : $mail->SMTPAuth" . "<br>";
		echo "mail->SMTPSecure : $mail->SMTPSecure" . "<br>";
		echo "mail->Username : $mail->Username" . "<br>";
		echo "mail->Password : $mail->Password" . "<br>";
		echo "mail->CharSet : $mail->CharSet" . "<br>";
		echo "mail->Encoding : $mail->Encoding" . "<br>";
		echo "from_email : $from_email" . "<br>";
		echo "from_name : $from_name" . "<br>";
		echo "to_email : $to_email" . "<br>";
		echo "to_name : $to_name" . "<br>";
		echo "title : $title" . "<br>";
		echo "content : $content" . "<br>";
        echo "======================================>>" . "<br>";;
	*/

	$return_status = $mail->Send();					// 실제로 메일을 보냄                            

    return $return_status;

}


function send_mail_pw($email, $user_id, $temp_pw)
{
	$title = "캐쉬로또 비밀번호 초기화";

	$content  = "안녕하세요." . "<br><br>";
	$content .= "함께하면 할수록 혜택이 더 커지는 캐시로또 입니다." . "<br>";
	$content .= "사용자 '$user_id'님께서 요청하신 비밀번호 초기화가 완료되었습니다." . "<br><br>";
	$content .= "- 임시비밀번호 : $temp_pw" . "<br><br>";
	$content .= "안전한 사용을 위하여 위의 임시비밀번호로 다시 로그인 하신 후," . "<br>";
	$content .= "초기화면>더보기>설정>계정관리에서 새로운 비밀번호로 변경하시고 사용하세요." . "<br><br>";
	$content .= "감사합니다." . "<br>";
	$content .= "캐시로또 드림" . "<br><br>";
	$content .= "(본 이메일은 발신전용입니다.)" . "<br>";

	//debug_log("send_mail_pw email : $email" . "<br>");
	//debug_log("send_mail_pw user_id : $user_id" . "<br>");
	//debug_log("send_mail_pw temp_pw : $temp_pw" . "<br>");
	//debug_log("send_mail_pw : $title" . "<br>");
	//debug_log("send_mail_pw : $content" . "<br>");

	
	$status = send_mail($email, $user_id, $title, $content);

	return $status;  
}

?>