<?
if (!defined('_CASHLOTTO_')) exit;

/*************************************************************************
**
**  SQL 관련 함수 모음
**
*************************************************************************/

// DB 연결
function sql_connect($host, $user, $pass)
{
	return @mysql_connect($host, $user, $pass);
}


// DB 선택
function sql_select_db($db, $connect)
{
    return @mysql_select_db($db, $connect);
}


// mysql_query 와 mysql_error 를 한꺼번에 처리
function sql_query($request, $sql, $start_mtime, $error=TRUE)
{
	if ($error)
        $result = @mysql_query($sql) or die(sqljson_db_error($request, $sql, $start_mtime));
    else
        $result = @mysql_query($sql);

    return $result;
}


// 결과값에서 한행 row를 얻는다.
function sql_fetch_row($request, $result)
{
    $row = @mysql_fetch_row($result);
    return $row;
}

// 결과값에서 한행 array row를 얻는다.
function sql_fetch_array($request, $result)
{
    $row = @mysql_fetch_array($result);
    return $row;
}


// 결과값에서 한행 연관배열(이름으로)로 얻는다.
function sql_fetch_assoc($result)
{
    $row = @mysql_fetch_assoc($result);
    return $row;
}

// 쿼리 Result의 레코드 갯수를 얻는다.
function sql_num_rows($result)
{
    $num_rows = @mysql_num_rows($result);
    return $num_rows;
}

// $result에 대한 메모리(memory)에 있는 내용을 모두 제거한다.
// sql_free_result()는 결과로부터 얻은 질의 값이 커서 많은 메모리를 사용할 염려가 있을 때 사용된다.
// 단, 결과 값은 스크립트(script) 실행부가 종료되면서 메모리에서 자동적으로 지워진다.
function sql_free_result($result)
{
    return @mysql_free_result($result);
}

// 
// https://gist.github.com/XachMoreno/1504031
// use this function to clean values going into mysql
function mysql_prep($value)
{
	// magic quotes gpc가 PHP 세팅에서 on으로 되어있는지 확인 
    // gpc는 get, post, cookie의 약자임
	$magic_quotes_active = get_magic_quotes_gpc(); //boolean - true if the quotes thing is turned on

	// PHP 버전 4.3.0 이상에서만 존재하는 mysql_real_escape_string 함수의 사용이 가능한지 확인
	$new_enough_php = function_exists("mysql_real_escape_string");
		
	if($new_enough_php)
	{
		if($magic_quotes_active)
		{
			$value = stripslashes($value);//if its a new version of php but has the quotes thing running, then strip the slashes it puts in
		}
		$value = mysql_real_escape_string($value);//if its a new version use the function to deal with characters
	}
	else
		if(!$magic_quotes_active)//If its an old version, and the magic quotes are off use the addslashes function
		{
			$value = addslashes($value);
		}
		
	return $value;
}

function sqljson_result2json($request, $result, $start_mtime)
{
	$result2_json = new Result2Json();
		
	$result2_json->addHead($request, mysql_num_rows($result), _CLRS_SUCCESS, "");
	$result2_json->addExecutionTime($start_mtime);
	$result2_json->addResult('datalist', $result);

	return $result2_json->getjson();
}
	
function sqljson_returnstatus2json($request, $status, $msg, $start_mtime)
{
	$result2_json = new Result2Json();
		
	$result2_json->addHead($request, 0, $status, $msg);
	$result2_json->addExecutionTime($start_mtime);
	
	return $result2_json->getjson();
}
			
function sqljson_db_error($request, $sql, $start_mtime)
{ 
	$result2_json = new Result2Json();
		
	$sql_errno = mysql_errno();
	$sql_errmsg = iconv('euc-kr', 'utf-8', mysql_error());
	$msg = "sql error : [sql stat] '$sql', [err msg] ($sql_errno) - '$sql_errmsg'";
		
	$result2_json->addHead($request, 0, _CLRS_FAILURE, $msg);
	$result2_json->addExecutionTime($start_mtime);
	
	echo $result2_json->getjson();
}	
	
?>