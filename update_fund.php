<?php
/*
 * 更新所有的基金
 */
require_once __DIR__.'/class/db.php';
require_once __DIR__.'/class/curl.php';
require_once __DIR__.'/class/price.php';

// 抓出所有的基金
$stmt = $db->prepare("SELECT * FROM category");
$stmt->execute();
$data = $stmt->fetchAll();
// 更新所有基金
foreach ($data as $value) {
	update_fund($db, $value['fundeName']);
}