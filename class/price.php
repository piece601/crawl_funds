<?php

require_once __DIR__.'/curl.php';
require_once __DIR__.'/success_lib.php';

function update_fund($db = null, $fundeName = null)
{
	if ( $db == null || $fundeName == null) {
		echo "請放入 db 以及 fundName \n";
		return;
	}

	$stmt = $db->prepare("SELECT * FROM price WHERE fundeName = ? ORDER BY priceId DESC limit 1");
	$stmt->execute([$fundeName]);
	// 本地最新資料
	$localNewest = $stmt->fetch();

	$query = spide($fundeName, $localNewest['date'], date("Y-m-d"));
	$dates = $query['dates'];
	$values = $query['values'];

	foreach ($dates as $key => $date) {
		// YYYYMMDD 轉成 YYYY-MM-DD
		$date = DateTime::createFromFormat('Ymd', $date)->format('Y-m-d');
		if ($localNewest['date'] < $date) {
			$stmt = $db->prepare("INSERT INTO price (fundeName, fundName, price, date) VALUES (?, ?, ?, ?)");
			$stmt->execute([
				$localNewest['fundeName'],
				$localNewest['fundName'],
				$values[$key],
				$date
			]);
			success_rank_insert($db, $localNewest['fundeName'], $date, '3');
			echo 'finish. '.$localNewest['fundName'].' '.$localNewest['fundeName'].' '.$date."\n";
		}
	}
	// echo "Update finish. \n";
}