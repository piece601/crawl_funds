<?php

require_once __DIR__.'/class/db.php';
require_once __DIR__.'/class/success_lib.php';

// 抓出所有的基金
$stmt = $db->prepare("SELECT * FROM category");
$stmt->execute();
$data = $stmt->fetchAll();
// 更新所有基金
foreach ($data as $value) {
	success_batch($db, $value['fundeName'], '3');
}
echo "Done. \n";
// success_save($db, 'FTH05', '1');