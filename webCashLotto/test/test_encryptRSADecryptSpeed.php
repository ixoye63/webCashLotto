<?

	$start_mtime = microtime(true);

	include_once("./_common.php");
	include_once("$g4[path]/lib/login.lib.php"); // 로그인 라이브러리
	
	include_once("$g4[path]/lib/AESEncryption/padCrypt.php");
	include_once("$g4[path]/lib/AESEncryption/AES_Encryption.php");
	
	header("Content-Type: text/html; charset=UTF-8");
	
	// ---------------------------------------------
	// 파이미터 확인
	// ---------------------------------------------
	$p_request = mysql_prep($_REQUEST["request"]);
	$p_encrypt_text = mysql_prep($_REQUEST["encrypt_text"]);
	$p_current_time_millis = mysql_prep($_REQUEST["timestamp"]);

	debug_log("p_current_time_millis : $p_current_time_millis");
	

	// 파이미터 확인 (필수항목)
	if (!param_check($p_current_time_millis, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, "->> 파리미터 에러(필수항목) : 시간값 없음"); return; }
	
	// $pri_file = "$g4[path]/pem/rsa_private.pem";
	$pri_file = "$g4[path]/pem/key.pem";
	debug_log("pri_file : $pri_file");
	
	$privatekey = openssl_pkey_get_private(file_get_contents($pri_file));
	
	debug_log("privatekey : $privatekey");	
	
	// 후처리
	openssl_private_decrypt(base64_decode($p_encrypt_text), $decrypted, $privatekey, OPENSSL_PKCS1_PADDING);
	$ret		  = $decrypted;

	
	$result2_json = new Result2Json();
	
	$result2_json->addHead($p_request, 1, _CLRS_SUCCESS, "");
	$result2_json->addExecutionTime($start_mtime);
	$result2_json->addJson('datalist', $ret);
	

	
	echo $result2_json->getjson();

?>
