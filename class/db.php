<?php
$dsn = "mysql:host=localhost;dbname=fund_db";
$db = new PDO($dsn, 'root', 'coppqyt', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));