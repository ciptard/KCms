<?php

if (isset($_POST['pass']) && isset($_POST['user']) && isset($_POST['pages_path'])) {
	mkdir($_POST['pages_path']);
	$settings = '<?php
$pages_path = "'.addslashes($_POST['pages_path']).'";
$index_page = "Home";
$pass = "'.md5($_POST['pass']).'";
$user = "'.addslashes($_POST['user']).'";
$theme = "default";
?>';
	$home_page = "This is a sample page<br />
<br />
You can modify it if you go <a href=\"login.php\">here</a><br />
Enjoy KCms! : D<br />
<br />
";
	$fd = fopen($_POST['pages_path']."/Home", "w");
	fwrite($fd, $home_page);
	fclose($fd);
	$fd = fopen("settings.php", "w");
	fwrite($fd, $settings);
	fclose($fd);
	unlink("install.php");
} else {
	echo "<form method=\"POST\">\n";
	echo "<label>Pages_path: </label><input type=\"text\" value=\"kcms\" name=\"pages_path\"><br />\n";
	echo "<label>Pass: </label><input type=\"text\" name=\"pass\"><br />\n";
	echo "<label>User: </label><input type=\"text\" name=\"user\"><br />\n";
	echo "<input type=\"submit\" value=\"Install\">\n";
	echo "</form>";
}
?>
