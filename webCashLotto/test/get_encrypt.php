<?
	include_once("./_common.php");
	include_once("$g4[path]/lib/login.lib.php"); // 로그인 라이브러리
	
	include_once("$g4[path]/lib/AESEncryption/padCrypt.php");
	include_once("$g4[path]/lib/AESEncryption/AES_Encryption.php");


	// mcrypt_module_open('rijndael-128', '', 'ecb', '');
	// echo mcrypt_get_block_size('rijndael-128', 'ecb');

	$key 	          = "k1t2m3h4o5w7s8kt9m8h7o6w5s4mhows";
	$iv               = substr($key, 0, 16);
	
	$AES = new AES_Encryption($key, $iv, 'PKCS5', 'ecb');
	$conf = $AES->getConfiguration();
	
	echo json_encode($conf)
	
?>
