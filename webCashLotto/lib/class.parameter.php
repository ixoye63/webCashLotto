<?php
/**
 * class Parmeter
 * 한 페이지에서 파라미터가 여러개 사용될 때 지저분하고 관리가 힘든 파라미터를 관리하기 위한 클래스.. :)
 * Author : lainTT(2003/03/25)
 */

class Parameter
{
	var $param = array(); // 파라미터가 담길 배열
	var $param_full = array(); // 항시 모든 파라미터가 담겨있을 임시 배열

	// 생성자 (생성할 파라미터를 배열로..)
	function Parameter($arry)
	{
		foreach($arry as $x=>$y) {
			$this->param[$x] = trim($y);
		}
		$this->param_full = $this->param;
	}

	// 파라미터 타당성 검사
	// base_val - value가 없을 경우 기본값
	// required - 필수여부(true || false)
	// __EXPRESSION__ - 정규식(해당 정규식에 일치할 것[preg style])
	// len - 길이(int -> 반드시 일치, array(int1, int2) -> int1에서 int2사이 길이일것)
	function check($key, $base_val = false, $required = false, $__EXPRESSION__ = false, $len = false)
	{
		// 필수 체크
		if($required !== false) {
			if(empty($this->param_full[$key]))
			{
				// $this->error('파라미터 ('.$key.') 항목은 필수입니다.');
				return false;
			}
		}

		// 길이 체크
		if(is_array($len)) { // 배열($len[0]에서 $len[1]사이)
			if((strlen($this->param_full[$key]) < $len[0] || strlen($this->param_full[$key]) > $len[1]) && $this->param_full[$key])
			{
				// $this->error('파라미터 ('.$key.') 항목의 길이가 잘못되었습니다.');
				return FALSE;
			}
		} else if($len !== false) { // 길이(반드시 $len 이어야함)
			if(strlen($this->param_full[$key]) != $len && $this->param_full[$key] !== false)
				// $this->error('파라미터 ('.$key.') 항목의 길이가 잘못되었습니다.');
				return false;
		}

		// 정규식 체크
		if($__EXPRESSION__ !== false) {
			if(!preg_match($__EXPRESSION__, $this->param_full[$key]) && $this->param_full[$key] !== false)
			{
				// $this->error('파라미터 ('.$key.') 항목 인자값 오류');
				return false;
			}
			
		}

		// 기본값 대입
		if(empty($this->param_full[$key]) && $base_val !== false) {
			$this->param[$key] = $base_val;
			$this->param_full[$key] = $base_val;
		}
		
		return true;
	}

	// 파라미터 초기화
	function init()
	{
		$this->param = $this->param_full;
	}

	// 파라미터 선언
	function set($key, $val)
	{
		$this->param[$key] = $val;
	}

	// 파라미터 추가
	function add($key, $val)
	{
		$this->param[$key] = $val;
		$this->param_full[$key] = $val;
	}

	// 파라미터 멤버 삭제(여러개 삭제일 경우 $key가 배열이 된다.)
	function del($key)
	{
		if(is_array($key)) { // 배열일 경우
			foreach($key as $x=>$y) unset($this->param[$y]);
		} else { // 하나일 경우
			unset($this->param[$key]);
		}
	}

	// 파라미터 가져오기
	function get($key)
	{
		return $this->param_full[$key];
	}

	// 파라미터를 get 형식으로 출력 (결과ex. a=1&b=2&c=3)
	function printGet()
	{
		$HTML = ''; // return 값
		$i = 0; // counter
		foreach($this->param as $x=>$y) {
			if($i!=0) $HTML .= '&'; // 맨 처음만 빼고 구별자 넣어줌
			$HTML .= $x.'='.$y;
			$i++;
		}
		
		return $HTML;
	}

	// 파라미터를 post 형식으로 출력 (결과ex. <input type="hidden" name="a" value="1"><input type="hidden" name="b" value="2">)
	function printPost()
	{
		$HTML = ''; // return 값
		foreach($this->param as $x=>$y) $HTML .= '<input type="hidden" name="'.$x.'" value="'.$y.'">';
		
		return $HTML;
	}

	// 에러 처리
	function error($msg)
	{
		die($msg);
	}
}
