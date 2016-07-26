<?
	$start_mtime = microtime(true);

	include_once("./_common.php");
	header("Content-Type: text/html; charset=UTF-8");
	
	$p_notice_id = urldecode($_GET["notice_id"]);
	
	$sql = "SELECT * FROM cl_notice WHERE db_notice_id = '$p_notice_id';";
	
	debug_log("$sql");

	$result = sql_query($p_request, $sql, $start_mtime);

	echo sqljson_result2json($p_request, $result, $start_mtime);

	sql_free_result($result);

?>
