<?
	include_once("./_common.php");
	include_once("$g4[path]/lib/login.lib.php"); // 로그인 라이브러리


	$p_request = urldecode($_GET["request"]);
	$p_id = urldecode($_GET["id"]);
	$p_smartphone = urldecode($_GET["smartphone"]);
	$p_nick = urldecode($_GET["nick"]);
	$p_birth = urldecode($_GET["birth"]);
	$p_sex = urldecode($_GET["sex"]);
	$p_region = urldecode($_GET["region"]);
	$p_email = urldecode($_GET["email"]);
	$p_pw = $_GET["pw"];
	
	
	// 신규사용자 비번에 대한 해쉬정보 생성

	$hash = create_hash($p_pw);
	echo "$p_pw -> $hash(" . strlen($hash) . ")"  . "<br>";
	$is_same = validate_password($p_pw, $hash);
	echo "is_same -> $is_same"  . "<br>";

	$p_pw = "1235";
	$hash = create_hash($p_pw);
	echo "$p_pw -> $hash(" . strlen($hash) . ")"  . "<br>";
	$is_same = validate_password($p_pw, $hash);
	echo "is_same -> $is_same"  . "<br>";

	$p_pw = "1236";
	$hash = create_hash($p_pw);
	echo "$p_pw -> $hash(" . strlen($hash) . ")"  . "<br>";
	$is_same = validate_password($p_pw, $hash);
	echo "is_same -> $is_same"  . "<br>";

	$p_pw = "1237";
	$hash = create_hash($p_pw);
	echo "$p_pw -> $hash(" . strlen($hash) . ")"  . "<br>";
	$is_same = validate_password($p_pw, $hash);
	echo "is_same -> $is_same"  . "<br>";

	// sql_query($sql);

?>
