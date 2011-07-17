<?php
include "class.Main.php";
include "settings.php";

if (!isset($_GET['id'])) {
	$_GET['id'] = $index_id;
}

if (file_exists("install.php")) {
	die("Delete your install.php");
}
$kcms = new KCms;
$read = $kcms->Main($_GET['id']);
?>
