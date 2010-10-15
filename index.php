<?php
header("Location: read.php");

if (file_exists("install.php")) {
	header("Location: install.php");
}
?>
