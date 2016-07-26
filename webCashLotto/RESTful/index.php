<?php

	$start_mtime = microtime(true);

	include_once("./_common.php");
	
	include_once("$g4[path]/lib/session.class.php");
	
	include_once("$g4[path]/framework/epiphany/src/Epi.php");

	include_once("$g4[path]/lib/AESEncryption/padCrypt.php");
	include_once("$g4[path]/lib/AESEncryption/AES_Encryption.php");

	include_once("$g4[path]/lib/login.lib.php"); // 로그인 라이브러리
	
	
	header("Content-Type: text/html; charset=UTF-8");
	
	// $session = new session();
	// Set to true if using https
	// $session->start_session('_s', false);
	
	session_start();

	Epi::init('api', 'route');

	getRoute()->get('/', 'showEndpoints');
	getRoute()->post('/', 'showEndpoints');
	
	getRoute()->get('/register_user', 'registerUser');
	getRoute()->post('/register_user', 'registerUser');
	
	getRoute()->get('/unregister_user', 'unRegisterUser');
	getRoute()->post('/unregister_user', 'unRegisterUser');

	getRoute()->get('/login', 'loginUser');
	getRoute()->post('/login', 'loginUser');
	
	getRoute()->get('/find_temp_password', 'findTempPassword');
	getRoute()->post('/find_temp_password', 'findTempPassword');
	
	getRoute()->get('/find_user_id', 'findUserId');
	getRoute()->post('/find_user_id', 'findUserId');
	
	getRoute()->get('/change_password', 'changePassword');
	getRoute()->post('/change_password', 'changePassword');

	getRoute()->get('/check_duplicate', 'checkUserDuplicate');
	getRoute()->post('/check_duplicate', 'checkUserDuplicate');
	
	getRoute()->get('/get_app_version', 'getAppLastestVersion');
	getRoute()->post('/get_app_version', 'getAppLastestVersion');
	
	getRoute()->get('/get_notices', 'getNotices');	
	getRoute()->post('/get_notices', 'getNotices');
	
	getRoute()->get('/get_advertises', 'getAdvertisements');
	getRoute()->post('/get_advertises', 'getAdvertisements');
	
	getRoute()->get('/get_sysinfo', 'getSysinfo');
	getRoute()->post('/get_sysinfo', 'getSysinfo');
	
	getRoute()->get('/get_user_account', 'getUserAccount');
	getRoute()->post('/get_user_account', 'getUserAccount');
	
	getRoute()->get('/get_banners', 'getBanners');
	getRoute()->post('/get_banners', 'getBanners');
	
	
	
	// ---------------------------------------------
	// 테스트용
	// ---------------------------------------------
	
	getRoute()->get('/get_region_sigungu', 'getRegionSigungu');
	getRoute()->post('/get_region_sido', 'getRegionSido');
	
	getRoute()->get('/get_region_sido', 'getRegionSido');
	getRoute()->post('/get_region_sigungu', 'getRegionSigungu');
		
	getRoute()->get('/test', 'test');	
	getRoute()->post('/test', 'test');	
	
	getRoute()->get('/test_PHP', 'testPHP');
	getRoute()->post('/test_PHP', 'testPHP');

	
	getRoute()->run();



/*
 * ******************************************************************************************
 * Define functions and classes which are executed by EpiCode based on the $_['routes'] array
 * ******************************************************************************************
 */

function showEndpoints()
{
  echo '';
}

function getUserAccount()
{
	// $start_mtime = microtime(true);
	global $start_mtime;

	// ---------------------------------------------
	// 파이미터 확인
	// ---------------------------------------------
	$p_request = mysql_prep($_REQUEST["request"]);
	$p_user_id = mysql_prep($_REQUEST["user_id"]);
	$p_current_time_millis = mysql_prep($_REQUEST["timestamp"]);


	// 파이미터 확인 (필수항목)
	if (!param_check($p_user_id, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 페이지번호 없음", $start_mtime); return; }
	if (!param_check($p_current_time_millis, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 시간값 없음", $start_mtime); return; }

	// 쿼리 전처리

	// ---------------------------------------------
	// 공지사항 쿼리
	// ---------------------------------------------
	
	$sql = "SELECT
				db_user_id as mUserId,
				db_current as mCurrent,
				db_total as mTotal,
				db_date_created as mCreatedDate,
				db_date_updated as mUpdatedDate
			FROM cl_user_account WHERE db_user_id = '$p_user_id';";

	debug_log("$sql" . "<br>");

	$result = sql_query($p_request, $sql, $start_mtime);
	$num_of_users = sql_num_rows($result);
	
	
	if ($num_of_users > 2) {
		echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE,  "중복된 사용자명('$p_user_id')입니다.\n고객센터로 문의바랍니다.", $start_mtime);
		return;
	} else if ($num_of_users == 0) {
		echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE,  "등록되지 않은 사용자명('$p_user_id')입니다.\n고객센터로 문의바랍니다.", $start_mtime);
		return;
	}

	echo sqljson_result2json($p_request, $result, $start_mtime);
}

function getBanners()
{
	// $start_mtime = microtime(true);
	global $start_mtime;

	// ---------------------------------------------
	// 파이미터 확인
	// ---------------------------------------------
	$p_request = mysql_prep($_REQUEST["request"]);
	$p_current_time_millis = mysql_prep($_REQUEST["timestamp"]);


	// 파이미터 확인 (필수항목)
	if (!param_check($p_current_time_millis, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 시간값 없음", $start_mtime); return; }

	// 쿼리 전처리


	// ---------------------------------------------
	// 공지사항 쿼리
	// ---------------------------------------------
	
	$sql = "SELECT
				db_banner_id as mBannerId,
				db_type as mActionType,
				db_title as mTitle,
				db_image_url as mImageUrl,
				db_image_postback_url as mPostBackUrl,
				db_uri as mUri,
				db_date_updated as mUpdatedDate
			FROM cl_banner;";

	debug_log("$sql" . "<br>");

	$result = sql_query($p_request, $sql, $start_mtime);

	echo sqljson_result2json($p_request, $result, $start_mtime);
}

function getNotices()
{
	// $start_mtime = microtime(true);
	global $start_mtime;
	
	// ---------------------------------------------
	// 파이미터 확인
	// ---------------------------------------------
	$p_request = mysql_prep($_REQUEST["request"]);
	$p_page_no = mysql_prep($_REQUEST["page_no"]);
	$p_current_time_millis = mysql_prep($_REQUEST["timestamp"]);
	

	// 파이미터 확인 (필수항목)
	if (!param_check($p_page_no, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 페이지번호 없음", $start_mtime); return; }
	if (!param_check($p_current_time_millis, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 시간값 없음", $start_mtime); return; }
	
	// 쿼리 전처리
	$page_count = 10;								// 한번에 읽어가는 공지사항 갯수
	$start_pos = ($p_page_no - 1) * $page_count;	// 시작레코드

	
	// ---------------------------------------------
	// 공지사항 쿼리
	// ---------------------------------------------

	$sql = "SELECT 
				db_notice_id as mNoticeId, 
				db_notice_type as mNoticeType, 
				db_notice_title as mContent, 
				db_notice_content as mContent, 
				db_date_updated as mWriteDate
				FROM cl_notice ORDER BY db_notice_id DESC LIMIT $start_pos, $page_count;";

	debug_log("$sql" . "<br>");

	$result = sql_query($p_request, $sql, $start_mtime);

	echo sqljson_result2json($p_request, $result, $start_mtime);
}

function getAppLastestVersion()
{
	// $start_mtime = microtime(true);
	global $start_mtime;

	// ---------------------------------------------
	// 파이미터 확인
	// ---------------------------------------------
	$p_request = mysql_prep($_REQUEST["request"]);
	$p_current_time_millis = mysql_prep($_REQUEST["timestamp"]);

	// 파이미터 확인 (필수항목)
	if (!param_check($p_current_time_millis, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 시간값 없음", $start_mtime); return; }


	// ---------------------------------------------
	// 버젼정보 쿼리
	// ---------------------------------------------

	$sql = "SELECT db_sys_value as mAppLastestVersion FROM cl_sysinfo WHERE db_sys_var = 'mAppLastestVersion';";

	debug_log("$sql");

	$result = sql_query($p_request, $sql, $start_mtime);

	echo sqljson_result2json($p_request, $result, $start_mtime);

}


function getAdvertisements()
{
	// $start_mtime = microtime(true);
	global $start_mtime;

	// ---------------------------------------------
	// 파이미터 확인
	// ---------------------------------------------
	$p_request = mysql_prep($_REQUEST["request"]);
	$p_current_time_millis = mysql_prep($_REQUEST["timestamp"]);

	// 파이미터 확인 (필수항목)
	if (!param_check($p_current_time_millis, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 시간값 없음", $start_mtime); return; }


	// ---------------------------------------------
	// 버젼정보 쿼리
	// ---------------------------------------------

	$sql = "SELECT
				db_ad_id as mAdId,
				db_ad_type as mAdType,
				db_ad_uri as mUri,
				db_ad_image_path as mImagePath,
				db_ad_reward as mReward,
				db_ad_reward_view as mRewardView,
				db_ad_category as mCategory,
				db_ad_age_from as mAgeFrom,
				db_ad_age_to as mAgeTo,
				db_ad_sex as mSex,
				db_ad_prefer_time_from as mPreferTimeFrom,
				db_ad_prefer_time_to as mPreferTimeTo,
				db_ad_sido as mResidenceSido,
				db_ad_sigungu as mResidenceSigungu,
				db_ad_priority as mPriority,
				db_ad_ordering as mOrdering,
				db_ad_longi as mLati,
				db_ad_lati as mLongi,
				db_ad_range as mRange,
				IFNULL(db_ad_action_count_filter, -1) as mActionCountFilter,
				IFNULL(db_ad_campaign_id, -1) as mAdCampaignId,
				IFNULL(db_ad_coupon_type, -1) as mCouponType,
				IFNULL(db_ad_cpa_package_name, '') as mCpaPackageName,
				IFNULL(db_ad_cpf_filter, '') as mCpfFilter,
				IFNULL(db_ad_dDay_filter, -1) as mDdayFilter,
				IFNULL(db_ad_marriage, -1) as mMarriage,
				IFNULL(db_ad_multi_app_target_filter, '') as mMultiAppTargetFilter,
				IFNULL(db_ad_package_check, '') as mPackageCheck,
				IFNULL(db_ad_popup_message, '') as mPopupMessage,
				IFNULL(db_ad_pre_install_filter, '') as mPreInstallFilter,
				IFNULL(db_ad_region_filter, '') as db_ad_region_filter,
				IFNULL(db_ad_seg_filter, '') as mSegFilter,
				IFNULL(db_ad_submessage, '') as mSubMessage,
				IFNULL(db_ad_target_device, '') as mTargetDevice,
				IFNULL(db_ad_title, '') as mTitle,
				IFNULL(db_ad_tstore_url, '') as mTstoreUrl
				FROM cl_advertise;";

	debug_log("$sql");

	$result = sql_query($p_request, $sql, $start_mtime);

	echo sqljson_result2json($p_request, $result, $start_mtime);

}


function getSysinfo()
{
	// $start_mtime = microtime(true);
	global $start_mtime;

	// ---------------------------------------------
	// 파이미터 확인
	// ---------------------------------------------
	$p_request = mysql_prep($_REQUEST["request"]);
	$p_current_time_millis = mysql_prep($_REQUEST["timestamp"]);

	// 파이미터 확인 (필수항목)
	if (!param_check($p_current_time_millis, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 시간값 없음", $start_mtime); return; }


	// ---------------------------------------------
	// 버젼정보 쿼리
	// ---------------------------------------------

	$sql = "SELECT db_sys_var, db_sys_value FROM cl_sysinfo WHERE db_using = 'Y';";

	debug_log("$sql");

	$result = sql_query($p_request, $sql, $start_mtime);

	$ret = array();
	while ($row = mysql_fetch_assoc($result)) {
		$ret[$row["db_sys_var"]] = urlencode($row["db_sys_value"]);
	}

	$result2_json = new Result2Json();

	$result2_json->addHead($p_request, mysql_num_rows($result), _CLRS_SUCCESS, "");
	$result2_json->addExecutionTime($start_mtime);
	$result2_json->addJson('datalist', $ret);

	echo $result2_json->getjson();
}

function registerUser()
{
	// $start_mtime = microtime(true);
	global $start_mtime;
	
	// ---------------------------------------------
	// 파이미터 확인
	// ---------------------------------------------
	
	$p_request = mysql_prep($_REQUEST["request"]);
	$p_user_id = mysql_prep($_REQUEST["user_id"]);
	$p_pw = mysql_prep($_REQUEST["pw"]);
	$p_email = mysql_prep($_REQUEST["email"]);
	$p_sex = mysql_prep($_REQUEST["sex"]);
	$p_marriage = mysql_prep($_REQUEST["marriage"]);
	$p_birth_year = mysql_prep($_REQUEST["birth_year"]);
	$p_region1 = mysql_prep($_REQUEST["region1"]);
	$p_region2 = mysql_prep($_REQUEST["region2"]);
	$p_agree_provision = mysql_prep($_REQUEST["agree_prov"]); 
	$p_agree_pers_info = mysql_prep($_REQUEST["agree_pers"]);
	$p_intro_id = mysql_prep($_REQUEST["intro_id"]);
	
	$p_phone_no = mysql_prep($_REQUEST["phone_no"]);
	
	$p_dev_imei = mysql_prep($_REQUEST["dev_imei"]);
	$p_dev_net_oper = mysql_prep($_REQUEST["dev_net_oper"]);
	$p_dev_net_oper_nm = mysql_prep($_REQUEST["dev_net_oper_nm"]);
	$p_dev_manufac = mysql_prep($_REQUEST["dev_manufac"]);
	$p_dev_model = mysql_prep($_REQUEST["dev_model"]);
	$p_dev_ver = mysql_prep($_REQUEST["dev_ver"]);
	$p_dev_sdk_ver = mysql_prep($_REQUEST["dev_sdk_ver"]);
	$p_dev_sim_no = mysql_prep($_REQUEST["dev_sim_no"]);
	$p_dev_android_id = mysql_prep($_REQUEST["dev_android_id"]);
		
	$p_current_time_millis = mysql_prep($_REQUEST["timestamp"]);
	
				
	// 파이미터 확인 (필수항목)
	if (!param_check($p_user_id, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 사용자 ID 없음", $start_mtime); return; }
	if (!param_check($p_pw, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 비밀번호 없음", $start_mtime); return; }
	if (!param_check($p_email, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 이메일 없음", $start_mtime); return; }
	if (!param_check($p_sex, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 성별구분 없음", $start_mtime); return; }
	if (!param_check($p_birth_year, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 생년웡 없음", $start_mtime); return; }
	if (!param_check($p_region1, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 주거지역 없음", $start_mtime); return; }
	if (!param_check($p_region2, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 주거지역 없음", $start_mtime); return; }
	if (!param_check($p_agree_provision, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 사용약관 동의 없음", $start_mtime); return; }
	if (!param_check($p_agree_pers_info, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 개인정보 동의 없음", $start_mtime); return; }

	if (!param_check($p_phone_no, false, true))	{ echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 전화번호 없음", $start_mtime); 	return; }
	if (!param_check($p_dev_imei, false, true))	{ echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : deviceID 없음", $start_mtime); 	return; }
	
	if (!param_check($p_current_time_millis, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 시간값 없음", $start_mtime); return; }
	
	// 파이미터 확인 (필드값 체크)
	if (!param_check($p_user_id, false, false, "/^[a-zA-Z]\w{4,20}$/u")) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, "사용자아이디는  4~20자리이며, '알파벳,숫자'만 사용가능합니다.", $start_mtime); return; }
	if (!param_check($p_pw, false, false, "/^[a-zA-Z0-9!@#$%^*+=_-]{6,20}$/u")) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, "비밀번호는 6~20자리이며, '알파벳,숫자,특수문자(!@#$%^*+=_-)'만 사용가능합니다..", $start_mtime); return; }
	if (!param_check($p_email, false, false, "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/u")) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, "(" . $p_email . ") 이메일주소를 확인해주세요.", $start_mtime); return; }
	if (!param_check($p_birth_year, false, false, "/^(1|2)\d{3}$/u")) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, "출생년도(YYYY)을  확인해주세요.", $start_mtime); return; }
	if (!param_check($p_sex, false, false, "/^(M|W)$/u")) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, "성별을 선택해주세요.", $start_mtime); return; }
	if (!param_check($p_marriage, false, false, "/^(M|S)$/u")) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, "결혼여부를 선택해주세요.", $start_mtime); return; }
	// if (!param_check($p_phone_no, false, false, "/^(010|011|016|017|018|019)-\d{3,4}-\d{4}$/u"))	{ echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(입력값 오류) : 입력된 전화번호를 확인해주세요."); return; }
	
	// 기본값 설정
	if (!param_check($p_intro_id, 'cashlotto')) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 기본값 설정 에러 : 추천인", $start_mtime); return; }

	
	// ---------------------------------------------
	// 사용자 ID 중복 확인
	// ---------------------------------------------
	$sql = "SELECT db_user_id FROM cl_user 
				WHERE db_user_id = '$p_user_id';";

	debug_log("$sql");

	$result = sql_query($p_request, $sql, $start_mtime);
	$num_of_users = sql_num_rows($result);


	if ($num_of_users > 0) {
		echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, "이미 사용중인 사용자명('$p_user_id')입니다.", $start_mtime);
		return;
	}
	
	
	// ---------------------------------------------
	// 사용자 ID 등록
	// ---------------------------------------------

	// 신규사용자 비번에 대한 해쉬정보 생성
	$pw_hash = create_hash($p_pw);

	// 현재시간 획득
	$c_datetime = convertMicrotimeToDate(microtime(true));

	$sql = "INSERT into cl_user 
				SET db_user_id = '$p_user_id',
					db_pw_hash = '$pw_hash',
					db_email = '$p_email',
					db_birth_year = '$p_birth_year',
					db_sex = '$p_sex',
					db_marriage = '$p_marriage',
					db_region1 = '$p_region1',
					db_region2 = '$p_region2',
					db_agree_provision = '$p_agree_provision',
					db_agree_pers_info = '$p_agree_pers_info',
					db_intro_id = '$p_intro_id',
					db_date_reg = '$c_datetime',
					db_date_updated = '$c_datetime';";

	debug_log("$sql");

	sql_query($p_request, $sql, $start_mtime);


	// ---------------------------------------------
	// 적립금액 정보 저장
	// ---------------------------------------------
	
	$sql = "INSERT into cl_user_account
				SET db_user_id = '$p_user_id',
					db_current = 0,
					db_total = 0,
					db_date_created = '$c_datetime',
					db_date_updated = '$c_datetime';";
	
	debug_log("$sql");
	
	sql_query($p_request, $sql, $start_mtime);
	
	
	
	// ---------------------------------------------
	// Device 정보 저장
	// ---------------------------------------------
	
	$sql = "INSERT into cl_device_log 
				SET db_user_id = '$p_user_id',
					db_phone_no = '$p_phone_no',
					db_dev_imei = '$p_dev_imei',
					db_dev_net_oper = '$p_dev_net_oper',
					db_dev_net_oper_nm = '$p_dev_net_oper_nm',
					db_dev_manufac = '$p_dev_manufac',
					db_dev_model = '$p_dev_model',
					db_dev_ver = '$p_dev_ver',
					db_dev_sdk_ver = '$p_dev_sdk_ver',
					db_dev_sim_no = '$p_dev_sim_no',
					db_dev_android_id = '$p_dev_android_id',
					db_date_created = '$c_datetime';";

	debug_log("$sql");

	sql_query($p_request, $sql, $start_mtime);
	
	$_SESSION["user_id"] = $p_user_id;
	
	echo sqljson_returnstatus2json($p_request, _CLRS_SUCCESS, "정상적으로 사용자('$p_user_id') 등록이 되었습니다.", $start_mtime);
	
	
	/*

	// ---------------------------------------------
	// 사용자 정보 확인 및 자동로그인용 정보 Return
	// ---------------------------------------------

	$sql = "SELECT db_user_id, db_pw_hash, db_email FROM cl_user
				WHERE db_user_id = '$p_user_id';";

	debug_log("$sql");

	$result = sql_query($p_request, $sql, $start_mtime);

	echo sqljson_result2json($p_request, $result, $start_mtime);
	
	*/
	
}

function unRegisterUser()
{
	// $start_mtime = microtime(true);
	global $start_mtime;
	
	// ---------------------------------------------
	// 파이미터 확인
	// ---------------------------------------------
	$p_request = mysql_prep($_REQUEST["request"]);
	$p_user_id = mysql_prep($_REQUEST["user_id"]);
	$p_pw = mysql_prep($_REQUEST["pw"]);
	
	// 파이미터 확인 (필수항목)
	if (!param_check($p_user_id, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 사용자 ID 없음", $start_mtime); return; }
	if (!param_check($p_pw, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 패스워드 없음", $start_mtime); return; }
	
	
	// ---------------------------------------------
	// 사용자 ID 확인
	// ---------------------------------------------

	$sql = "SELECT * FROM cl_user WHERE db_user_id = '$p_user_id';";

	debug_log("$sql");

	$result = sql_query($p_request, $sql, $start_mtime);
	$num_of_users = sql_num_rows($result);


	if ($num_of_users > 2) {
		echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE,  "이미 사용중인 사용자명('$p_user_id')입니다.", $start_mtime);
		return;
	} else if ($num_of_users == 0) {
		echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE,  "등록되지 않은 사용자명('$p_user_id')입니다.", $start_mtime);
		return;
	}
	
	// ---------------------------------------------
	// 사용자 패스워드 확인 : 입력된 pw값과 DB pw_hash값과 비교
	// ---------------------------------------------

	$row = sql_fetch_array($p_request, $result);

	if (!validate_password($p_pw, $row['db_pw_hash'])) {
		echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, "사용자 비밀번호를 잘못 입력하셨습니다.", $start_mtime);
		return;
	}


	// 현재시간 획득
	$c_datetime = convertMicrotimeToDate(microtime(true));
	
	// ---------------------------------------------
	// 사용자 탈퇴 정보 Log
	// ---------------------------------------------
	$v_user_id = $row['db_user_id'];
	$v_email = $row['db_email'];
	$v_birth_year = $row['db_birth_year'];
	$v_marriage = $row['db_marriage'];
	$v_sex = $row['db_sex'];
	$v_region1 = $row['db_region1'];
	$v_region2 = $row['db_region2'];
	$v_intro_id = $row['db_intro_id'];
	$v_db_phone_no = $row['db_phone_no'];
	$v_db_date_reg = $row['db_date_reg'];
	
	$sql = "INSERT into cl_unregister_user 
				SET db_user_id = '$v_user_id',
					db_email = '$v_email',
					db_birth_year = '$v_birth_year',
					db_marriage = '$v_marriage',
					db_sex = '$v_sex',
					db_region1 = '$v_region1',
					db_region2 = '$v_region2',
					db_intro_id = '$v_intro_id',
					db_phone_no = '$v_db_phone_no',
					db_date_reg = '$v_db_date_reg',
					db_date_unreg = '$c_datetime';";
	
	debug_log("$sql");

	sql_query($p_request, $sql, $start_mtime);

	
	// ---------------------------------------------
	// 사용자 가입정보 삭제
	// ---------------------------------------------
	
	$sql = "DELETE FROM cl_user WHERE db_user_id = '$p_user_id';";
	debug_log("$sql");
	sql_query($p_request, $sql, $start_mtime);

	$sql = "DELETE FROM cl_user_account WHERE db_user_id = '$p_user_id';";
	debug_log("$sql");
	sql_query($p_request, $sql, $start_mtime);

	
	// ---------------------------------------------
	// 처리결과 Return
	// ---------------------------------------------

	echo sqljson_returnstatus2json($p_request, _CLRS_SUCCESS, "요청하신  사용자님('$p_user_id')의 회원탈퇴가 정상처리 되었습니다.", $start_mtime);	
}

function loginUser()
{
	// $start_mtime = microtime(true);
	global $start_mtime;
	
	// ---------------------------------------------
	// 파이미터 확인
	// ---------------------------------------------
	$p_request = mysql_prep($_REQUEST["request"]);
	$p_user_id = mysql_prep($_REQUEST["user_id"]);
	$p_pw = mysql_prep($_REQUEST["pw"]);

	$p_phone_no = mysql_prep($_REQUEST["phone_no"]);
	$p_dev_imei = mysql_prep($_REQUEST["dev_imei"]);
	$p_dev_net_oper = mysql_prep($_REQUEST["dev_net_oper"]);
	$p_dev_net_oper_nm = mysql_prep($_REQUEST["dev_net_oper_nm"]);
	$p_dev_manufac = mysql_prep($_REQUEST["dev_manufac"]);
	$p_dev_model = mysql_prep($_REQUEST["dev_model"]);
	$p_dev_ver = mysql_prep($_REQUEST["dev_ver"]);
	$p_dev_sdk_ver = mysql_prep($_REQUEST["dev_sdk_ver"]);
	$p_dev_sim_no = mysql_prep($_REQUEST["dev_sim_no"]);
	$p_dev_android_id = mysql_prep($_REQUEST["dev_android_id"]);
		
	// 파이미터 확인 (필수항목)
	if (!param_check($p_user_id, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 사용자 ID 없음", $start_mtime); return; }
	if (!param_check($p_pw, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 패스워드 없음", $start_mtime); return; }
	
	
	// ---------------------------------------------
	// 사용자 ID 확인
	// ---------------------------------------------

	$sql = "SELECT db_user_id, db_pw_hash, db_email FROM cl_user
				WHERE db_user_id = '$p_user_id';";

	debug_log("$sql");

	$result = sql_query($p_request, $sql, $start_mtime);
	$num_of_users = sql_num_rows($result);


	if ($num_of_users > 2) {
		echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE,  "이미 사용중인 사용자명('$p_user_id')입니다.", $start_mtime);
		return;
	} else if ($num_of_users == 0) {
		echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE,  "등록되지 않은 사용자명('$p_user_id')입니다.", $start_mtime);
		return;
	}
		
	// ---------------------------------------------
	// 사용자 패스워드 확인 : 입력된 pw값과 DB pw_hash값과 비교
	// ---------------------------------------------

	$row = sql_fetch_array($p_request, $result);

	if (!validate_password($p_pw, $row['db_pw_hash'])) {
		echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, "사용자 비밀번호를 잘못 입력하셨습니다.", $start_mtime);
		return;
	}

	// ---------------------------------------------
	// Device 정보 저장
	// ---------------------------------------------
	
	// 현재시간 획득
	$c_datetime = convertMicrotimeToDate(microtime(true));
	
	$sql = "INSERT into cl_device_log 
				SET db_user_id = '$p_user_id',
					db_phone_no = '$p_phone_no',
					db_dev_imei = '$p_dev_imei',
					db_dev_net_oper = '$p_dev_net_oper',
					db_dev_net_oper_nm = '$p_dev_net_oper_nm',
					db_dev_manufac = '$p_dev_manufac',
					db_dev_model = '$p_dev_model',
					db_dev_ver = '$p_dev_ver',
					db_dev_sdk_ver = '$p_dev_sdk_ver',
					db_dev_sim_no = '$p_dev_sim_no',
					db_dev_android_id = '$p_dev_android_id',
					db_date_created = '$c_datetime';";

	debug_log("$sql");

	sql_query($p_request, $sql, $start_mtime);
	
	// ---------------------------------------------
	// 사용자 정보 확인 및 자동로그인용 정보 Return
	// ---------------------------------------------

	$sql = "SELECT 
				db_user_id as mUserId, 
				db_email as mEmail, 
				db_sex as mSex, 
				db_marriage as mMarriage, 
				db_birth_year as mBirthYear, 
				db_region1 as mResidence1,
				db_region2 as mResidence2
				FROM cl_user WHERE db_user_id = '$p_user_id';";

	debug_log("$sql");

	$result = sql_query($p_request, $sql, $start_mtime);

	$_SESSION["user_id"] = $p_user_id;
	
	// 사용자 Profile 정보 Return
	echo sqljson_result2json($p_request, $result, $start_mtime);
	
}

function findUserId()
{
	// $start_mtime = microtime(true);
	global $start_mtime;
	
	// ---------------------------------------------
	// 파이미터 확인
	// ---------------------------------------------
	$p_request = mysql_prep($_REQUEST["request"]);
	$p_email = mysql_prep($_REQUEST["email"]);
	$p_current_time_millis = mysql_prep($_REQUEST["timestamp"]);
	
	// 파이미터 확인 (필수항목)
	if (!param_check($p_email, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 이메일 없음", $start_mtime); return; }
	if (!param_check($p_current_time_millis, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 시간값 없음", $start_mtime); return; }
	
	
	// ---------------------------------------------
	// 사용자 ID 검색 및 정보 Return
	// ---------------------------------------------

	$sql = "SELECT 
				db_user_id as mUserId
				FROM cl_user WHERE db_email = '$p_email'";

	debug_log("$sql");

	$result = sql_query($p_request, $sql, $start_mtime);
	$num_of_users = sql_num_rows($result);

	if ($num_of_users == 0) {
		echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE,  "등록되지 않은 이메일주소('$p_email')입니다.", $start_mtime);
		return;
	}


	echo sqljson_result2json($p_request, $result, $start_mtime);
}

function changePassword()
{
	// $start_mtime = microtime(true);
	global $start_mtime;
	
	// ---------------------------------------------
	// 파이미터 확인
	// ---------------------------------------------
	$p_request = mysql_prep($_REQUEST["request"]);
	$p_user_id = mysql_prep($_REQUEST["user_id"]);
	$p_cur_pw = mysql_prep($_REQUEST["cur_pw"]);
	$p_new_pw = mysql_prep($_REQUEST["new_pw"]);
	$p_current_time_millis = mysql_prep($_REQUEST["timestamp"]);
	
	// 파이미터 확인 (필수항목)
	if (!param_check($p_user_id, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 사용자 ID 없음", $start_mtime); return; }
	if (!param_check($p_cur_pw, false, true))	{ echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 현재 패스워드 없음", $start_mtime); return; }
	if (!param_check($p_new_pw, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 신규 패스워드 없음", $start_mtime); return; }

	if (!param_check($p_current_time_millis, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 시간값 없음", $start_mtime); return; }
	
	
	// ---------------------------------------------
	// 사용자 ID 중복 확인
	// ---------------------------------------------

	$sql = "SELECT db_user_id, db_pw_hash, db_email FROM cl_user 
				WHERE db_user_id = '$p_user_id';";

	debug_log("$sql");

	$result = sql_query($p_request, $sql, $start_mtime);
	$num_of_users = sql_num_rows($result);


	if ($num_of_users > 2) {
		echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE,  "이미 사용중인 사용자명('$p_user_id')입니다.", $start_mtime);
		return;
	} else if ($num_of_users == 0) {
		echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE,  "등록되지 않은 사용자명('$p_user_id')입니다.", $start_mtime);
		return;
	}
	
	// ---------------------------------------------
	// 현재 패스워드와의 동일여부 확인
	// ---------------------------------------------

	$row = sql_fetch_array($p_request, $result);

	if (!validate_password($p_cur_pw,  $row['db_pw_hash'])) {
		echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, "사용자 비밀번호를 잘못 입력하셨습니다.", $start_mtime);
		return;
	}
	
	// ---------------------------------------------
	// 신규 패스워드로 변경
	// ---------------------------------------------

	// 신규 패스워드에 대한 해쉬정보 생성
	$pw_hash = create_hash($p_new_pw);

	// 현재시간 획득
	$c_datetime = convertMicrotimeToDate(microtime(true));
		
	$sql = "UPDATE cl_user
				SET db_pw_hash = '$pw_hash',
					db_date_updated = '$c_datetime'
				WHERE db_user_id = '$p_user_id';";


	debug_log("$sql");

	sql_query($p_request, $sql, $start_mtime);

	// ---------------------------------------------
	// 사용자 정보 확인 및 자동로그인용 정보 Return
	// ---------------------------------------------

	echo sqljson_returnstatus2json($p_request, _CLRS_SUCCESS, "요청하신 비밀번호변경되었습니다.", $start_mtime);
	
}


function findTempPassword()
{
	// $start_mtime = microtime(true);
	global $start_mtime;
	
	// ---------------------------------------------
	// 파이미터 확인
	// ---------------------------------------------
	$p_request = mysql_prep($_REQUEST["request"]);
	$p_user_id = mysql_prep($_REQUEST["user_id"]);
	$p_email = mysql_prep($_REQUEST["email"]);
	$p_current_time_millis = mysql_prep($_REQUEST["timestamp"]);
	
	// 파이미터 확인 (필수항목)
	if (!param_check($p_user_id, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 사용자 ID 없음", $start_mtime); return; }
	if (!param_check($p_email, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 이메일 없음", $start_mtime); return; }
	if (!param_check($p_current_time_millis, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 시간값 없음", $start_mtime); return; }
	
	
	// ---------------------------------------------
	// 사용자 ID 확인 (db_user_id가 Unique하므로써 중복확인보다는 사용자 확인용으로)
	// ---------------------------------------------

	$sql = "SELECT db_user_id, db_email FROM cl_user 
				WHERE db_user_id = '$p_user_id' AND db_email = '$p_email';";

	debug_log("$sql");

	$result = sql_query($p_request, $sql, $start_mtime);
	$num_of_users = sql_num_rows($result);


	if ($num_of_users > 2) {
		echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, "중복된 사용자ID($p_user_id)입니다.", $start_mtime);
		return;
	} else if ($num_of_users == 0) {
		echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, "미등록된 사용자ID($p_user_id)입니다.", $start_mtime);
		return;
	}
	
	// ---------------------------------------------
	// 사용자 패스워드 초기화
	// ---------------------------------------------

	// 임시비밀번호 생성
	$temp_pw =  rand(100000,999999);

	debug_log("임시번호 : $temp_pw");


	// ---------------------------------------------
	// 임시비밀번호 이메일 전송
	// ---------------------------------------------

	$msg = '';

	try {
		$status = send_mail_pw($p_email, $p_user_id, $temp_pw);
	} catch (phpmailerException $e) {
		$msg = "phpmailerException : " . strip_tags($e->errorMessage());
    } catch (Exception $e) {
		$msg = "Exception : " . strip_tags($e->getMessage());
    }

	if(!$status)							
	{
		echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, "이메일 전송 실패. error msg : " . $msg, $start_mtime);
		return;
	} 


	// ---------------------------------------------
	// 사용자 패스워드 초기화
	// ---------------------------------------------

	// 신규사용자 임시비밀번호에 대한 해쉬정보 생성
	$pw_hash = create_hash($temp_pw);

	// 현재시간 획득
	$c_datetime = convertMicrotimeToDate(microtime(true));

	$sql = "UPDATE cl_user 
				SET db_pw_hash = '$pw_hash',
					db_date_updated = '$c_datetime'
				WHERE db_user_id = '$p_user_id' AND db_email = '$p_email';";


	debug_log("$sql");

	sql_query($p_request, $sql, $start_mtime);


	// ---------------------------------------------
	// 사용자 정보 확인 및 자동로그인용 정보 Return
	// ---------------------------------------------
	
	echo sqljson_returnstatus2json($p_request, _CLRS_SUCCESS, "요청하신 이메일로 임시비밀번호를 전송했습니다.", $start_mtime);
	
}

function checkUserDuplicate()
{
	// $start_mtime = microtime(true);
	global $start_mtime;
	
	// ---------------------------------------------
	// 파이미터 확인
	// ---------------------------------------------
	$p_request = mysql_prep($_REQUEST["request"]);
	$p_user_id = mysql_prep($_REQUEST["user_id"]);
	$p_current_time_millis = mysql_prep($_REQUEST["timestamp"]);
	
	// 파이미터 확인 (필수항목)
	if (!param_check($p_user_id, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 사용자 ID 없음", $start_mtime); return; }
	if (!param_check($p_current_time_millis, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 시간값 없음", $start_mtime); return; }
	
	
	// ---------------------------------------------
	// 사용자 ID 검색 및 정보 Return
	// ---------------------------------------------

	$sql = "SELECT db_user_id FROM cl_user WHERE db_user_id = '$p_user_id'";

	debug_log("$sql");

	$result = sql_query($p_request, $sql, $start_mtime);
	$num_of_users = sql_num_rows($result);

	if ($num_of_users > 0) {
		echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE,  "이미 등록중인 사용자ID('$p_email')입니다.", $start_mtime);
		return;
	}

	echo sqljson_returnstatus2json($p_request, _CLRS_SUCCESS,  "", $start_mtime);
}

function getRegionSigungu()
{
	// $start_mtime = microtime(true);
	global $start_mtime;

	$p_request = mysql_prep($_REQUEST["request"]);
	$p_sido = mysql_prep($_REQUEST["sido"]);
	$p_current_time_millis = mysql_prep($_REQUEST["timestamp"]);

	debug_log("p_sido : $p_sido");
	debug_log("p_current_time_millis : $p_current_time_millis");


	// 파이미터 확인 (필수항목)
	if (!param_check($p_sido, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 시도코드 없음", $start_mtime); return; }
	//if (!param_check($p_current_time_millis, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 시간값 없음"); return; }



	// ---------------------------------------------
	// 시군구 코드 검색 및 정보 Return
	// ---------------------------------------------

	$sql = "SELECT db_sigungu FROM cl_code_region where db_sido = '$p_sido' ORDER BY order_sigungu;";

	debug_log("$sql");

	$result = sql_query($p_request, $sql, $start_mtime);

	echo sqljson_result2json($p_request, $result, $start_mtime);
}

function getRegionSido()
{
	// $start_mtime = microtime(true);
	global $start_mtime;

	// ---------------------------------------------
	// 파이미터 확인
	// ---------------------------------------------
	$p_request = mysql_prep($_REQUEST["request"]);
	$p_current_time_millis = mysql_prep($_REQUEST["timestamp"]);

	debug_log("p_current_time_millis : $p_current_time_millis" . "<br>");

	// 파이미터 확인 (필수항목)
	if (!param_check($p_current_time_millis, false, true)) { echo sqljson_returnstatus2json($p_request, _CLRS_FAILURE, " 파리미터 에러(필수항목) : 시간값 없음", $start_mtime); return; }

	// ---------------------------------------------
	// 시도 코드 검색 및 정보 Return
	// ---------------------------------------------

	$sql = "SELECT DISTINCT db_sido FROM cl_code_region ORDER BY order_sido;";

	debug_log("$sql");

	$result = sql_query($p_request, $sql, $start_mtime);

	echo sqljson_result2json($p_request, $result, $start_mtime);
}

function test()
{
	global $start_mtime;
	
	$sql = "SELECT db_sys_var, db_sys_value FROM cl_sysinfo WHERE db_using = 'Y';";

	// ---------------------------------------------
	// 버젼정보 쿼리
	// ---------------------------------------------

	$sql = "SELECT db_sys_var, db_sys_value FROM cl_sysinfo WHERE db_using = 'Y';";
	
	debug_log("$sql");

	$result = sql_query($p_request, $sql, $start_mtime);
	
	$ret = array();
	while ($row = mysql_fetch_assoc($result)) {
		$ret[$row["db_sys_var"]] = urlencode($row["db_sys_value"]);
	}
		
	$result2_json = new Result2Json();
		
	$result2_json->addHead($request, mysql_num_rows($result), _CLRS_SUCCESS, "");
	$result2_json->addExecutionTime($start_mtime);
	$result2_json->addJson('datalist', $ret);
	
	echo $result2_json->getjson();
}

function testPHP()
{
	// ---------------------------------------------
	// 파이미터 확인
	// ---------------------------------------------
	$p_dev_imei = mysql_prep($_REQUEST["dev_imei"]);
	$p_click_info = mysql_prep($_REQUEST['click_info[]']);
	$p_user_info = mysql_prep($_REQUEST["user_info[]"]);
	$p_current_time_millis = mysql_prep($_REQUEST["timestamp"]);
	
	echo "p_dev_imei : $p_dev_imei";
	echo "p_click_info[count] : $p_click_info[count]";
	echo "p_user_info[id] : $p_user_info[id]";
	
}

function getRSAKeyGen()
{
	
}



