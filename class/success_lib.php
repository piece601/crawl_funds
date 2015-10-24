<?php

function success_rank_insert($db, $fundeName, $date, $years)
{
	// 抓出這一段區間的所有淨值排序
	$stmt = $db->prepare("SELECT * FROM price WHERE fundeName = ? AND date <= ? AND date > ? ORDER BY price DESC");
	$stmt->execute([
		$fundeName,
		$date,
		date('Y-m-d',strtotime(str_replace('-', '/', $date) . '-'.$years.' years'))
	]);
	// 區間值
	$intervalData = $stmt->fetchAll();
	// 總數量
	$total = count($intervalData);
	// 如果區間都沒有東西，就回傳 false
	if ($total == 0) {
		return false;
	}
	$rank = 0;
	// 抓出 price
	foreach ($intervalData as $value) {
		if ($value['date'] == $date) {
			$price = $value['price'];
			$fundName = $value['fundName'];
			$fundeName = $value['fundeName'];
			break;
		}
		continue;
		return false;
	}
	foreach ($intervalData as $counter => $a) {
		if ( $price == $a['price'] ) {
			$rank = $counter + 1;
			break;
		}
		continue;
		return false;
	}
	$stmt = $db->prepare("INSERT INTO success (years, fundeName, fundName, fundDate, price, success_percent) VALUES (?, ?, ?, ?, ?, ?)");
	$stmt->execute([
		$years,
		$fundeName,
		$fundName,
		$date,
		$price,
		(float) $rank / $total * 100
	]);
	return true;
}

function success_batch($db, $fundeName = null, $years = null)
{
	if (  $fundeName == null || $years == null) {
		echo '請輸入 db 以及 fundeName 以及 years'."\n";
		return false;
	}
	// 抓出最老的資料
	$stmt = $db->prepare("SELECT * FROM price WHERE fundeName = ? ORDER BY priceId ASC LIMIT 1");
	$stmt->execute([$fundeName]);
	$data = $stmt->fetch();
	// 抓出要計算到最老資料的後幾年日期，避免未滿要求年數
	$limitYear = date('Y-m-d', strtotime(str_replace('-', '/', $data['date']) . '+'.$years.' years'));

	// 抓出所有淨值
	$stmt = $db->prepare("SELECT * FROM price WHERE fundeName = ? ORDER BY priceId DESC");
	$stmt->execute([$fundeName]);
	$data = $stmt->fetchAll();

	foreach ($data as $key => $value) {
		// 達到最低計算日期就 break 換下一黨基金
		if ($value['date'] < $limitYear) {
			break;
		}

		// 檢查成功率表裡面是否已經有，有就 continue
		$stmt = $db->prepare("SELECT * FROM success WHERE fundeName = ? AND years = ? AND fundDate = ?");
		$stmt->execute([$fundeName, $years, $value['date']]);
		if ( ! empty( $stmt->fetch() ) )
			continue;
		if ( ! success_rank_insert($db, $fundeName, $value['date'], $years) ) {
			echo "此基金爆炸了 \n";
			break;
		}
		// 抓出這一段區間的所有淨值排序
		// $stmt = $db->prepare("SELECT * FROM price WHERE fundeName = ? AND date <= ? AND date > ? ORDER BY price DESC");
		// $stmt->execute([
		// 	$fundeName,
		// 	$value['date'],
		// 	date('Y-m-d',strtotime(str_replace('-', '/', $value['date']) . '-'.$years.' years'))
		// ]);
		// // 區間值
		// $intervalData = $stmt->fetchAll();
		// // 總數量
		// $total = count($intervalData);
		// $rank = 0;
		// foreach ($intervalData as $counter => $a) {
		// 	if ( $value['price'] == $a['price'] ) {
		// 		$rank = $counter + 1;
		// 		break;
		// 	}
		// }
		// $stmt = $db->prepare("INSERT INTO success (years, fundeName, fundName, fundDate, price, success_percent) VALUES (?, ?, ?, ?, ?, ?)");
		// $stmt->execute([
		// 	$years,
		// 	$value['fundeName'],
		// 	$value['fundName'],
		// 	$value['date'],
		// 	$value['price'],
		// 	(float) $rank / $total * 100
		// ]);
		// var_dump($value['date']);
		// $stmt = $db->prepare("SELECT * FROM price WHERE fundeName = ?");
		echo 'finish. '.$value['fundName']. ' ' . $value['fundeName'] . ' ' .$value['date']."\n";	
	}
	echo $value['fundName']." All finish.\n";
}	