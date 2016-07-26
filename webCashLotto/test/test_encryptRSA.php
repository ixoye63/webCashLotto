<?

	$start_mtime = microtime(true);

	include_once("./_common.php");
	include_once("$g4[path]/lib/login.lib.php"); // 로그인 라이브러리
	
	include_once("$g4[path]/lib/AESEncryption/padCrypt.php");
	include_once("$g4[path]/lib/AESEncryption/AES_Encryption.php");
	
	include_once("$g4[path]/lib/phpseclib/Crypt/RSA.php");
	include_once("$g4[path]/lib/phpseclib/Crypt/AES.php");
	include_once("$g4[path]/lib/phpseclib/Crypt/Random.php");
	include_once("$g4[path]/lib/phpseclib/Math/BigInteger.php");
	
	// ini_set('session.save_path', realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/cashlotto/tmp'));


	header("Content-Type: text/html; charset=UTF-8");
	
	// ---------------------------------------------
	// 파이미터 확인
	// ---------------------------------------------
	$p_request = mysql_prep($_REQUEST["request"]);
	$p_sido = mysql_prep($_REQUEST["sido"]);
	$p_current_time_millis = mysql_prep($_REQUEST["timestamp"]);

	debug_log("p_sido : $p_sido");
	debug_log("p_current_time_millis : $p_current_time_millis");
	

	// 파이미터 확인 (필수항목)
	if (!param_check($p_sido, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, "->> 파리미터 에러(필수항목) : 시도코드 없음"); return; }
	if (!param_check($p_current_time_millis, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, "->> 파리미터 에러(필수항목) : 시간값 없음"); return; }
	
	// $pri_file = "$g4[path]/pem/rsa_private.pem";
	$pri_file = "$g4[path]/pem/key.pem";
	debug_log("pri_file : $pri_file");
	
	$rsa = new Crypt_RSA();
	$rsa->loadKey(file_get_contents($pri_file));
	$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
	$privatekey = $rsa->getPrivateKey();
	
	debug_log("privatekey : $privatekey");
	
	
	// 후처리
	$p_sido			  = $rsa->decrypt(base64_decode($p_sido));

	
	
	// ---------------------------------------------
	// 시군구 코드 검색 및 정보 Return
	// ---------------------------------------------

	$sql = "SELECT db_sigungu FROM cl_code_region where db_sido = '$p_sido' ORDER BY order_sigungu;";

	debug_log("$sql");

	$result = sql_query($p_request, $sql, $start_mtime);

	$json = sqljson_result2json($p_request, $result, $start_mtime);

	
	echo $json;

?>
