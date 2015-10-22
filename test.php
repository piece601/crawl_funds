<?php

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://www.moneydj.com/funddj/yp/yp011001.djhtm?a=FTZE9');
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($ch);
$data = iconv("BIG5","UTF-8", $data);
curl_close($ch);
$start = strpos($data, '<td class="t3t2" >');
echo substr($data, $start+18, 9);