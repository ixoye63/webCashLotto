<?
// 이 상수가 정의되지 않으면 각각의 개별 페이지는 별도로 실행될 수 없음
define("_CASHLOTTO_", TRUE);

// 디렉토리
$g4['admin']          = "adm";
$g4['admin_path']     = $g4['path'] . "/" . $g4['admin'];


// 자주 사용하는 값
// 서버의 시간과 실제 사용하는 시간이 틀린 경우 수정하세요.
// 하루는 86400 초입니다. 1시간은 3600초
// 6시간이 빠른 경우 time() + (3600 * 6);
// 6시간이 느린 경우 time() - (3600 * 6);
$g4['server_time'] = time();
$g4['time_ymd']    = date("Y-m-d", $g4['server_time']);
$g4['time_his']    = date("H:i:s", $g4['server_time']);
$g4['time_ymdhis'] = date("Y-m-d H:i:s", $g4['server_time']);

//
// 테이블 명
// (상수로 선언한것은 함수에서 global 선언을 하지 않아도 바로 사용할 수 있기 때문)
//
$g4['table_prefix']        = "cl_"; // 테이블명 접두사
$g4['write_prefix']        = $g4['table_prefix'] . "write_"; // 게시판 테이블명 접두사

$g4['user_table']          = $g4['table_prefix'] . "user";          // 사용자정보 설정 테이블
$g4['code_region_table']   = $g4['table_prefix'] . "code_region";   // 사용자 사는지역(시군구 정보) 테이블

//$g4['zip_table']           = $g4['table_prefix'] . "zip";           // 우편번호 테이블

//
// 기타
//

// www.sir.co.kr 과 sir.co.kr 도메인은 서로 다른 도메인으로 인식합니다. 쿠키를 공유하려면 .sir.co.kr 과 같이 입력하세요. 
// 이곳에 입력이 없다면 www 붙은 도메인과 그렇지 않은 도메인은 쿠키를 공유하지 않으므로 로그인이 풀릴 수 있습니다.
$g4['cookie_domain'] = "";

$g4['charset'] = "utf-8";

$g4['phpmyadmin_dir'] = $g4['admin'] . "/phpMyAdmin/";

// config.php 가 있는곳의 웹경로. 뒤에 / 를 붙이지 마세요.
// 예) http://g4.sir.co.kr
$g4['url'] = "";

$config[cf_email_use] = true;

?>
