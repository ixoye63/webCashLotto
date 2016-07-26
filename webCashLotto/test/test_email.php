<?
	include_once("./_common.php");
	include_once("$g4[path]/lib/login.lib.php"); // 로그인 라이브러리
	include_once("$g4[path]/lib/mailer.lib.php"); // 로그인 라이브러리

	header("Content-Type: text/html; charset=UTF-8");
	
	// ---------------------------------------------
	// 파이미터 확인
	// ---------------------------------------------
	$p_request = mysql_prep($_REQUEST["request"]);
	$p_user_id = mysql_prep($_REQUEST["user_id"]);
	$p_email = mysql_prep($_REQUEST["email"]);

	debug_log("p_request-> $p_request");
	debug_log("p_user_id-> $p_user_id");
	debug_log("p_email -> $p_email");


	// ---------------------------------------------
	// 사용자 패스워드 초기화
	// ---------------------------------------------

	// 임시비밀번호 생성
	$temp_pw =  rand(100000,999999);

	debug_log("임시비밀번호 -> " . $temp_pw);


	// ---------------------------------------------
	// 이메일 전송 및 전송결과 Return
	// ---------------------------------------------

	$msg = '';

	try {
		$status = send_mail_pw($p_email, $p_user_id, $temp_pw);
	} catch (phpmailerException $e) {
		$msg = "phpmailerException : " . strip_tags($e->errorMessage());
    } catch (Exception $e) {
		$msg = "Exception : " . strip_tags($e->getMessage());
    }

	if($status)							
	{
		echo sqljson_returnstatus2json($p_request, _CLRS_SUCCESS, "메세지전송을 완료. Sending mail is success.");
	} else {
		echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, "메세지전송을 실패. Sending mail is error : " . $msg);
	}
?>
