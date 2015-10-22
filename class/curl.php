<?php

function spide($fundeName, $startDate = '1980-1-1', $endDate = null)
{
	if ( $endDate === null) {
		$endDate = date("Y-m-d");
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://www.moneydj.com/funddj/bcd/BCDNavList.djbcd?a='. $fundeName .'&B='. $startDate .'&C='. $endDate .'&D=');
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($ch);
	curl_close($ch);
	// 轉換成陣列模式
	$spacePosition = strpos($data, ' ');
	$dates = substr($data, 0, $spacePosition);
	$values = substr($data, $spacePosition, strlen($data));
	$dates = explode(',', $dates);
	$values = explode(',', $values);

	return [
		'dates' => $dates,
		'values' => $values
	];
}


function check_class($fundeName, $class = '股票型')
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://www.moneydj.com/funddj/yp/yp011001.djhtm?a='.$fundeName);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($ch);
	$data = iconv("BIG5","UTF-8", $data);
	curl_close($ch);
	$start = strpos($data, '<td class="t3t2" >');
	if ( substr($data, $start+18, 9) == $class ) {
		return true;
	}	
	return false;
}