<?php

header("Content-Type: text/html; charset=UTF-8");

	
$start_time = microtime(true);
$end_time = microtime(true);
$diff_time = $end_time - $start_time;

echo sprintf("start_time : %s<br/>", convertMicrotimeToDate($start_time));
echo sprintf("end_time : %s<br/>", convertMicrotimeToDate($end_time));
echo sprintf("diff_time : %f<br/>", sprintf("%.6f", $diff_time));


function convertMicrotimeToDate($time)
{
	$micro_time=sprintf("%06d", ($time - floor($time)) * 1000000);
	$date = new DateTime( date('Y-m-d H:i:s.'.$micro_time, $time) );
	return sprintf("%s", $date->format("Y-m-d H:i:s.u"));
}





