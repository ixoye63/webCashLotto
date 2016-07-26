<?

	$start_mtime = microtime(true);

	include_once("./_common.php");
	include_once("$g4[path]/lib/session.class.php");
	
	header("Content-Type: text/html; charset=UTF-8");
	
	$p_request = mysql_prep($_REQUEST["request"]);
	$p_android_id = mysql_prep($_REQUEST["android_id"]);
	

	$sql = "SELECT * FROM cl_user ORDER BY db_user_id LIMIT 1;";
	$result = sql_query($p_request, $sql, $start_mtime);

	echo sqljson_result2json($p_request, $result, $start_mtime);

	sql_free_result($result);

?>
