<?
	include_once("./_common.php");
	include_once("$g4[path]/lib/coupon.lib.php"); // 쿠폰 라이브러리

	header("Content-Type: text/html; charset=UTF-8");
	
	// ---------------------------------------------
	// 파이미터 확인
	// ---------------------------------------------
	$p_request = mysql_prep($_REQUEST["request"]);

	// 파이미터 확인 (필수항목)

	// Random
	for($i=0; $i<10; $i++) {
		$coupon_no = get_coupon_no();				
		debug_log("coupon_no : $coupon_no");
	}
	
	// 쿠폰용
	for($i=0; $i<10; $i++) {
		$coupon_no = get_coupon_no('aaaa-addd-adaa-ddaa');				
		debug_log("coupon_no : $coupon_no");
	}

	// 바코드용
	for($i=0; $i<10; $i++) {
		$coupon_no = get_coupon_no('dddd-dddd-dddd-dddd');				
		debug_log("coupon_no : $coupon_no");
	}

	
?>
