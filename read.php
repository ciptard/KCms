<?php
include "class.Main.php";
include "settings.php";

if (!isset($_GET['page'])) {
	$_GET['page'] = $index_page;
}

if (file_exists("install.php")) {
	die("Delete your install.php");
}
$kcms = new KCms;
$kcms->CreateMenu();
echo $kcms->ReadPage($_GET['page']);
?>
