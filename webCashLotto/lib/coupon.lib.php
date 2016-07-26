<?

// 쿠폰문자 발행
function get_coupon_default($charlen = 12, $coupon_string=false) {
	$letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$str_shu = '';
	
	if ($coupon_string !== false)
		 $letters = $coupon_string;
		 
	$str_shu = str_shuffle($letters);
	
	return substr(str_shuffle($letters), 0, $charlen);
}

// patten : a:영문자, d:숫자, l=영문자+숫자
// $charlen = $pattern_len -> 뱐환 값 return
// $charlen > $pattern_len -> null return
function get_coupon_pattern($pattern = 'LLLL-LLLL-LLLL') {
	$letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$digit = '1234567890';
	$str_shu = '';

	// echo "pattern = $pattern" . "<br>";
	
	// 파라미터 체크
	$pattern_upper = strtoupper($pattern);
	$pattern_len = strlen($pattern);

	// echo "pattern_upper = $pattern_upper, pattern_len = $pattern_len" . "<br>";
	
	
	if(!preg_match("/^(A|D|L)[ADL-]+$/u", $pattern_upper)) {
		return null;
	}
	
		
	// 쿠폰값 생성
	for($i=0; $i<$pattern_len; $i++) {
		if (substr($pattern_upper, $i, 1) == 'A') {
			$str_shu .= substr(str_shuffle($alpha), 0, 1); 
		} else if (substr($pattern_upper, $i, 1) == 'D') {
			$str_shu .= substr(str_shuffle($digit), 0, 1);
		} else if (substr($pattern_upper, $i, 1) == 'L') {
			$str_shu .= substr(str_shuffle($letters), 0, 1);
		} else if (substr($pattern_upper, $i, 1) == '-') {
			$str_shu .= '-';
		}
	}
	

	return $str_shu;
}


function get_coupon_no($pattern=false) 
{ 
	if ($pattern !== false)
		return get_coupon_pattern($pattern);
	else 
		return get_coupon_pattern();
}


?>